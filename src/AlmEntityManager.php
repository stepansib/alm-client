<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 21:38
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityManagerException;

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
    const ENTITY_TYPE_RUN = 'run';
    const ENTITY_TYPE_RUN_STEPS= 'run-step';

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
    }

    /**
     * @return AlmEntityExtractor
     */
    public function getEntityExtractor()
    {
        if ($this->entityExtractor === null || !($this->entityExtractor instanceof AlmEntityExtractor)){
            $this->entityExtractor = new AlmEntityExtractor([]);
        }
        return $this->entityExtractor;
    }

    /**
     * @return AlmEntityLocker
     */
    public function getEntityLocker()
    {
        if ($this->entityLocker === null || !($this->entityLocker instanceof AlmEntityLocker)){
            $this->entityLocker = new AlmEntityLocker($this->curl, $this->routes);
        }

        return $this->entityLocker;
    }

    /**
     * @return AlmEntityParametersManager
     */
    public function getParametersManager()
    {
        if ($this->parametersManager === null || !($this->parametersManager instanceof  AlmEntityParametersManager)){
            $this->parametersManager = new AlmEntityParametersManager($this->curl, $this->routes);
        }

        return $this->parametersManager;
    }

    /**
     * @return AlmRunStepsManager
     */
    public function getFoldersManager()
    {
        if ($this->folderManager === null || !($this->folderManager instanceof AlmRunStepsManager)){
            $this->folderManager = new AlmRunStepsManager($this->curl, $this->routes);
        }

        return $this->folderManager;
    }

    /**
     * @return AlmAttachmentManager
     */
    public function getAttachmentManager()
    {
        if ($this->attachmentsManager === null || !($this->attachmentsManager instanceof AlmAttachmentManager)){
            $this->attachmentsManager = new AlmAttachmentManager($this->curl, $this->routes);
        }

        return $this->attachmentsManager;
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
     * @param string $hydration
     * @param array $fields
     * @return array|null|string
     * @throws AlmEntityManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function getBy($entityType, array $criteria, array $fields = array(), $perPage = 250, $page = 1, $orderBy = '{id[DESC]}', $hydration = self::HYDRATION_ENTITY)
    {
        $fieldsList = '';
        if (count($fields)) {
            $fieldsList = '&fields=' . implode(',', $fields);
        }

        if ($page > 1) {
            $page = $perPage * ($page - 1) + 1;
        }

        $criteriaProcessed = array();

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

                $resultArray = array();
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
        $this->curl->setHeaders(array('DELETE /HTTP/1.1'))
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

        $headers = array(
            'Accept: application/xml',
            'Content-Type: application/xml',
        );

        if ($entity->isNew()) {
            $entityXml = $this->getEntityExtractor()->pack($entity);

            array_push($headers, 'POST /HTTP/1.1');

            $this->curl->setHeaders($headers)
                ->setPost($entityXml->asXML())
                ->exec($this->routes->getEntityUrl($entity->getTypePluralized()));

            $xml = simplexml_load_string($this->curl->getResult());

        } else {

            $entityXml = $this->getEntityExtractor()->pack($entity, $this->getParametersManager()->getEntityEditableParameters($entity));

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
