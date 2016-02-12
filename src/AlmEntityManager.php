<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 21:38
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\AlmEntityInterface;
use StepanSib\AlmClient\Exception\AlmEntityManagerException;

class AlmEntityManager
{

    const ENTITY_TYPE_DEFECT = 'defect';
    const ENTITY_TYPE_TEST = 'test';

    const HYDRATION_ENTITY = 'entity';
    const HYDRATION_NONE = 'none';

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var AlmEntityMapper */
    protected $entityMapper;

    /**
     * AlmEntityManager constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     * @param AlmEntityMapper $entityMapper
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes, AlmEntityMapper $entityMapper)
    {
        $this->routes = $routes;
        $this->curl = $curl;
        $this->entityMapper = $entityMapper;
    }

    /**
     * @param $entityType
     * @param array $criteria
     * @param string $hydration
     * @return AlmEntityInterface[]|string
     * @throws AlmEntityManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmEntityExtractorException
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

        $url = $this->routes->getEntityUrl() . '/' . $entityType . 's?query={' . implode(';', $criteriaProcessed) . '}';
        $resultRaw = $this->curl->exec($url)->getResult();

        switch ($hydration) {
            case self::HYDRATION_ENTITY:
                $xml = simplexml_load_string($resultRaw);

                $resultArray = array();
                foreach ($xml->Entity as $entity) {
                    array_push($resultArray, $this->entityMapper->extract($entity));
                }

                return $resultArray;
                break;
            case self::HYDRATION_NONE:
                return $resultRaw;
                break;
        }

        throw new AlmEntityManagerException('Incorrect hydration mode specified');

    }

    public function save(AlmEntityInterface $entity)
    {
        if (!$entity->isNew()) {
            throw new AlmEntityManagerException('Id cannot be specified for new entity');
        }

        $entityPlainXml = $this->entityMapper->pack($entity)->asXML();

        $this->processSave($entityPlainXml);
    }

    protected function processSave($entityPlainXml)
    {
        //Todo: implement save query execution
    }

}
