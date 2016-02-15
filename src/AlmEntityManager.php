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

    /**
     * AlmEntityManager constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes)
    {
        $this->routes = $routes;
        $this->curl = $curl;
        $this->entityExtractor = new AlmEntityExtractor(array());
        $this->entityLocker = new AlmEntityLocker($this->curl, $this->routes);
        $this->parametersManager = new AlmEntityParametersManager($this->curl, $this->routes);
    }

    /**
     * @return AlmEntityExtractor
     */
    public function getEntityExtractor()
    {
        return $this->entityExtractor;
    }

    /**
     * @return AlmEntityLocker
     */
    public function getEntityLocker()
    {
        return $this->entityLocker;
    }

    /**
     * @return AlmEntityParametersManager
     */
    public function getParametersManager()
    {
        return $this->parametersManager;
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
     */
    public function getOneBy($entityType, array $criteria)
    {
        $result = $this->getBy($entityType, $criteria, self::HYDRATION_ENTITY);
        return $result[0];
    }

    /**
     * @param $entityType
     * @param array $criteria
     * @param string $hydration
     * @return array|null|string
     * @throws AlmEntityManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function getBy($entityType, array $criteria, $hydration = self::HYDRATION_ENTITY)
    {
        $criteriaProcessed = array();

        if (count($criteria) == 0) {
            throw new AlmEntityManagerException('Criteria array cannot be empty');
        }

        foreach ($criteria as $key => $value) {
            array_push($criteriaProcessed, $key . '[' . $value . ']');
        }

        $url = $this->routes->getEntityUrl($this->pluralizeEntityType($entityType)) . '?query={' . implode(';', $criteriaProcessed) . '}';
        $resultRaw = $this->curl->exec($url)->getResult();

        switch ($hydration) {
            case self::HYDRATION_ENTITY:
                $xml = simplexml_load_string($resultRaw);

                $resultArray = array();
                foreach ($xml->Entity as $entity) {
                    array_push($resultArray, $this->entityExtractor->extract($entity));
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
     * @return AlmEntity
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function save(AlmEntity $entity)
    {

        $headers = array(
            'Accept: application/xml',
            'Content-Type: application/xml',
        );

        if ($entity->isNew()) {
            $entityXml = $this->entityExtractor->pack($entity);

            array_push($headers, 'POST /HTTP/1.1');

            $this->curl->setHeaders($headers)
                ->setPost($entityXml->asXML())
                ->exec($this->routes->getEntityUrl($entity->getTypePluralized()));

            $xml = simplexml_load_string($this->curl->getResult());

        } else {

            $ignoreCheckins = false;

            $entityXml = $this->entityExtractor->pack($entity, $this->parametersManager->getEntityEditableParameters($entity));

            $this->entityLocker->lockEntity($entity);
            try {
                $this->getEntityLocker()->checkOutEntity($entity);
            } catch (\Exception $e) {
                $ignoreCheckins = true;
            }

            array_push($headers, 'PUT /HTTP/1.1');

            $this->curl->setHeaders($headers)
                ->setPut($entityXml->asXML())
                ->exec($this->routes->getEntityUrl($entity->getTypePluralized(), $entity->id));

            $xml = simplexml_load_string($this->curl->getResult());

            if (!$ignoreCheckins) {
                $this->getEntityLocker()->checkInEntity($entity);
            }

            $this->entityLocker->unlockEntity($entity);

        }

        return $this->entityExtractor->extract($xml);

    }


}
