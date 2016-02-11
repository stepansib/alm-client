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

    /** @var AlmEntityExtractorInterface */
    protected $entityEtractor;

    public function __construct(AlmCurl $curl, AlmRoutes $routes, AlmEntityExtractorInterface $fieldMapper)
    {
        $this->routes = $routes;
        $this->curl = $curl;
        $this->setEntityExtractor($fieldMapper);
    }

    public function createQuery()
    {
        return new AlmQuery($this->curl, $this->routes, $this->entityEtractor);
    }

    public function setEntityExtractor(AlmEntityExtractorInterface $fieldMapper)
    {
        $this->entityEtractor = $fieldMapper;
        return $this;
    }

}
