<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 21:40
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmExceptionGenerator;
use StepanSib\AlmClient\Exception\AlmQueryException;

class AlmQuery
{
    const ENTITY_DEFECT = 'defects';
    const ENTITY_TEST = 'tests';

    /** @var AlmCurl */
    protected $curl;

    /** @var array */
    protected $criterias;

    /** @var  string */
    protected $entity;

    /** @var AlmRoutes */
    protected $routes;

    /** @var AlmEntityExtractor */
    protected $entityExtractor;

    /**
     * AlmQuery constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     * @param AlmEntityExtractor $entityExtractor
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes, AlmEntityExtractor $entityExtractor)
    {
        $this->criterias = array();
        $this->curl = $curl;
        $this->routes = $routes;
        $this->entityExtractor = $entityExtractor;
    }

    /**
     * @param $entityType
     * @return $this
     */
    public function select($entityType)
    {
        $this->entity = $entityType;
        return $this;
    }

    /**
     * @param $param
     * @param $criteria
     * @return $this
     */
    public function where($param, $criteria)
    {
        array_push($this->criterias, $param . '[' . $criteria . ']');
        return $this;
    }

    /**
     * @return array
     */
    public function execute()
    {
        return $this->extractToArray(simplexml_load_string($this->executeRaw()));
    }

    /**
     * @return mixed
     * @throws AlmQueryException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function executeRaw()
    {
        return $this->curl->exec($this->getQueryUrl())->getResult();
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return array
     */
    protected function extractToArray(\SimpleXMLElement $xml)
    {
        $arr = array();

        foreach ($xml->Entity as $entity) {
            array_push($arr, $this->entityExtractor->extract($entity));
        }

        return $arr;
    }

    /**
     * @return string
     * @throws AlmQueryException
     */
    public function getQueryUrl()
    {
        if (null == $this->entity) {
            throw new AlmQueryException('Query selection entity type not specified');
        } else {
            $url = $this->routes->getEntityUrl() . '/' . $this->entity;
            if (count($this->criterias) > 0) {
                $url .= '?query={' . implode(';', $this->criterias) . '}';
            }
            return $url;
        }
    }

}
