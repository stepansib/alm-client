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

    const ENTITY_DEFECT = 'defects';

    const ENTITY_TEST = 'tests';

    /** @var array */
    protected $connectionOptions;

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    public function __construct(array $connectionOptions, AlmCurl $curl, AlmRoutes $routes)
    {
        $this->routes = $routes;
        $this->curl = $curl;
        $this->connectionOptions = $connectionOptions;
    }

    public function createQuery()
    {
        return new AlmQuery($this->connectionOptions, $this->curl, $this->routes);
    }

}
