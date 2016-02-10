<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 21:40
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmExceptionGenerator;

class AlmQuery
{

    /** @var AlmCurl */
    protected $curl;

    /** @var array */
    protected $connectionOptions;

    /** @var array */
    protected $criterias;

    /** @var  string */
    protected $entity;

    /** @var AlmRoutes */
    protected $routes;

    public function __construct($connectionOptions, AlmCurl $curl, AlmRoutes $routes)
    {
        $this->criterias = array();
        $this->curl = $curl;
        $this->routes = $routes;
        $this->connectionOptions = $connectionOptions;
    }

    public function select($entityType)
    {
        $this->entity = $entityType;
        return $this;
    }

    public function where($param, $criteria)
    {
        array_push($this->criterias, $param . '[' . $criteria . ']');
        return $this;
    }

    public function execute()
    {
        if ($queryUrl = $this->getQueryUrl()) {
            if ($this->curl->exec($queryUrl)->getHttpCode() == '200') {
                return $this->curl->getResult();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getQueryUrl()
    {
        if (null !== $this->entity) {
            $url = $this->routes->getEntityUrl() . '/' . $this->entity;
            if (count($this->criterias) > 0) {
                $url .= '?query={' . implode(';', $this->criterias) . '}';
            }
            return $url;
        } else {
            AlmExceptionGenerator::throwEntityTypeNotSpecified();
        }

        return false;
    }

}
