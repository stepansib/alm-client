<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 21:38
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityManagerException;

/**
 * Class AlmEntityManager
 */
class AlmEntityManager
{

    const HYDRATION_ENTITY = 'array';
    const HYDRATION_NONE = 'none';
    const ENTITY_TYPE_TEST = 'test';
    const ENTITY_TYPE_REQUIREMENT = 'requirement';
    const ENTITY_TYPE_RESOURCE = 'resource';
    const ENTITY_TYPE_DEFECT = 'defect';
    const ENTITY_TYPE_DESIGN_STEP = 'design-step';
    const ENTITY_TYPE_TEST_SET = 'test-set';
    const ENTITY_TYPE_TEST_INSTANCE = 'test-instance';
    const ENTITY_TYPE_RUN = 'run';
    const ENTITY_TYPE_RUN_STEPS = 'run-step';

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var AlmEntityExtractor */
    protected $entityExtractor;

    /** @var AlmEntityLocker */
    protected $entityLocker;

    /** @var AlmEntityParametersManager */
    protected $parametersManager;

    /** @var AlmRunStepsManager */
    protected $folderManager;

    /** @var AlmAttachmentManager */
    protected $attachmentsManager;

    /** @var AlmDefectLinkManager */
    protected $defectLinkManager;

    /** @var AlmRunStepsManager */
    protected $runStepsManager;

    /**
     * AlmEntityManager constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes)
    {
        $this->routes = $routes;
        $this->curl = $curl;
        $this->entityExtractor = null;
        $this->entityLocker = null;
        $this->parametersManager = null;
        $this->folderManager = null;
        $this->attachmentsManager = null;
        $this->runStepsManager = null;
    }

    /**
     * @return AlmEntityExtractor
     */
    public function getEntityExtractor()
    {
        if ($this->entityExtractor === null || !($this->entityExtractor instanceof AlmEntityExtractor)) {
            $this->entityExtractor = new AlmEntityExtractor($this->getParametersManager());
        }
        return $this->entityExtractor;
    }

    /**
     * @return AlmEntityLocker
     */
    public function getEntityLocker()
    {
        if ($this->entityLocker === null || !($this->entityLocker instanceof AlmEntityLocker)) {
            $this->entityLocker = new AlmEntityLocker($this->curl, $this->routes);
        }

        return $this->entityLocker;
    }

    /**
     * @return AlmEntityParametersManager
     */
    public function getParametersManager()
    {
        if ($this->parametersManager === null || !($this->parametersManager instanceof AlmEntityParametersManager)) {
            $this->parametersManager = new AlmEntityParametersManager($this->curl, $this->routes);
        }

        return $this->parametersManager;
    }

    /**
     * @return AlmFolderManager
     */
    public function getFoldersManager(): AlmFolderManager
    {
        if ($this->folderManager === null || !($this->folderManager instanceof AlmFolderManager)) {
            $this->folderManager = new AlmFolderManager($this->curl, $this->routes);
        }

        return $this->folderManager;
    }

    /**
     * @return AlmAttachmentManager
     */
    public function getAttachmentManager(): AlmAttachmentManager
    {
        if ($this->attachmentsManager === null || !($this->attachmentsManager instanceof AlmAttachmentManager)) {
            $this->attachmentsManager = new AlmAttachmentManager(
                $this->curl,
                $this->routes,
                $this->getParametersManager()
            );
        }

        return $this->attachmentsManager;
    }

    /**
     * @return AlmDefectLinkManager
     */
    public function getDefectLinkManager(): AlmDefectLinkManager
    {
        if ($this->defectLinkManager === null || !($this->defectLinkManager instanceof AlmDefectLinkManager)) {
            $this->defectLinkManager = new AlmDefectLinkManager(
                $this->curl,
                $this->routes,
                $this->getParametersManager(),
                $this->getEntityExtractor()
            );
        }

        return $this->defectLinkManager;
    }

    /**
     * @return AlmRunStepsManager
     */
    public function getRunStepsManager()
    {
        if ($this->runStepsManager === null || !($this->runStepsManager instanceof AlmRunStepsManager)) {
            $this->runStepsManager = new AlmRunStepsManager(
                $this->curl,
                $this->routes,
                $this->getParametersManager()
            );
        }

        return $this->runStepsManager;
    }

    /**
     * @param $entityType
     * @return string
     */
    protected function pluralizeEntityType($entityType)
    {
        $entity = new AlmEntity($entityType);
        return $entity->getTypePluralized();
    }

    /**
     * @param $entityType
     * @param array $criteria
     * @return AlmEntity
     * @throws AlmEntityManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmEntityParametersManagerException
     * @throws Exception\AlmException
     */
    public function getOneBy($entityType, array $criteria)
    {
        $result = $this->getBy($entityType, $criteria);
        return $result[0];
    }

    /**
     * @param $entityType
     * @param array $criteria
     * @param array $fields
     * @param int $perPage
     * @param int $page
     * @param string $orderBy
     * @param string $hydration
     * @return array|null|string
     * @throws AlmEntityManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmEntityParametersManagerException
     * @throws Exception\AlmException
     */
    public function getBy($entityType, array $criteria, array $fields = [], $perPage = 250, $page = 1, $orderBy = '{id[DESC]}', $hydration = self::HYDRATION_ENTITY)
    {
        $fieldsList = '';
        if (count($fields)) {
            $fieldsList = '&fields=' . implode(',', $fields);
        }

        if ($page > 1) {
            $page = $perPage * ($page - 1) + 1;
        }

        $criteriaProcessed = [];

        if (count($criteria) == 0) {
            throw new AlmEntityManagerException('Criteria array cannot be empty');
        }

        foreach ($criteria as $key => $value) {
            array_push($criteriaProcessed, $key . '[' . rawurlencode($value) . ']');
        }

        $url = $this->routes->getEntityUrl($this->pluralizeEntityType($entityType)) . '?query={' . implode(';', $criteriaProcessed) . '}' . $fieldsList . '&page-size=' . $perPage . '&start-index=' . $page . '&order-by=' . $orderBy;
        $resultRaw = $this->curl->exec($url)->getResult();

        switch ($hydration) {
            case self::HYDRATION_ENTITY:
                $xml = simplexml_load_string($resultRaw);

                $resultArray = [];
                foreach ($xml->Entity as $entity) {
                    array_push($resultArray, $this->getEntityExtractor()->extract($entity));
                }

                return $resultArray;
                break;
            case self::HYDRATION_NONE:
                return $resultRaw;
                break;
        }

        throw new AlmEntityManagerException('Incorrect hydration mode specified');

    }

    /**
     * @param AlmEntity $entity
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function delete(AlmEntity $entity)
    {
        $this->curl->setHeaders(['DELETE /HTTP/1.1'])
            ->setDelete()
            ->exec($this->routes->getEntityUrl($entity->getTypePluralized(), $entity->id));

    }

    /**
     * @param AlmEntity $entity
     * @return AlmEntity
     * @throws AlmEntityManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     * @throws Exception\AlmEntityParametersManagerException
     */
    public function save(AlmEntity $entity)
    {

        $headers = [
            'Accept: application/xml',
            'Content-Type: application/xml',
        ];

        if ($entity->isNew()) {
            $entityXml = $this->getEntityExtractor()->pack($entity);

            array_push($headers, 'POST /HTTP/1.1');

            $this->curl->setHeaders($headers)
                ->setPost($entityXml->asXML())
                ->exec($this->routes->getEntityUrl($entity->getTypePluralized()));

            $xml = simplexml_load_string($this->curl->getResult());

        } else {

            $entityXml = $this->getEntityExtractor()->pack($entity, $this->getParametersManager()->getEntityEditableParameters($entity->getType()));

            if ($this->getEntityLocker()->isEntityLocked($entity)) {
                if (!$this->getEntityLocker()->isEntityLockedByMe($entity)) {
                    throw new AlmEntityManagerException('Entity is locked by someone');
                }
            } else {
                $this->getEntityLocker()->lockEntity($entity);
            }

            if ($this->isEntityVersioning($entity)) {
                $this->getEntityLocker()->checkOutEntity($entity);
            }

            array_push($headers, 'PUT /HTTP/1.1');

            $this->curl->setHeaders($headers)
                ->setPut($entityXml->asXML())
                ->exec($this->routes->getEntityUrl($entity->getTypePluralized(), $entity->id));

            $xml = simplexml_load_string($this->curl->getResult());

            if ($this->isEntityVersioning($entity)) {
                $this->getEntityLocker()->checkInEntity($entity);
            }

            $this->getEntityLocker()->unlockEntity($entity);

        }

        return $this->getEntityExtractor()->extract($xml);

    }


    /**
     * @param AlmEntity $entity
     * @return bool
     */
    public function isEntityVersioning(AlmEntity $entity)
    {
        if ($entity->getType() == AlmEntityManager::ENTITY_TYPE_TEST
            || $entity->getType() == AlmEntityManager::ENTITY_TYPE_REQUIREMENT
            || $entity->getType() == AlmEntityManager::ENTITY_TYPE_RESOURCE
        ) {
            return true;
        }
        return false;
    }
}
