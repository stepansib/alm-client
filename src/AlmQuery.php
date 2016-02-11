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

    /** @var AlmEntityExtractorInterface */
    protected $entityExtractor;

    /**
     * AlmQuery constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     * @param AlmEntityExtractorInterface $entityExtractor
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes, AlmEntityExtractorInterface $entityExtractor)
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
        $resultArray = array();

        if ($queryUrl = $this->getQueryUrl()) {
            if ($this->curl->exec($queryUrl)->getHttpCode() == '200') {
                $resultArray = $this->extractToArray(simplexml_load_string($this->curl->getResult()));
            }
        }

        return $resultArray;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return array
     */
    protected function extractToArray(\SimpleXMLElement $xml)
    {
        $arr = array();

        foreach ($xml->Entity as $entity) {
            array_push($arr, $this->entityExtractor->fromXml($entity));
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
