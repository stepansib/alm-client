<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 21:38
 */

namespace StepanSib\AlmClient;

class AlmEntityManager
{

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var AlmEntityExtractor */
    protected $entityExtractor;

    public function __construct(AlmCurl $curl, AlmRoutes $routes, AlmEntityExtractor $entityExtractor)
    {
        $this->routes = $routes;
        $this->curl = $curl;
        $this->entityExtractor = $entityExtractor;
    }

    public function createQuery()
    {
        return new AlmQuery($this->curl, $this->routes, $this->entityExtractor);
    }

}
