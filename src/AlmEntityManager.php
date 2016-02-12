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


    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var AlmEntityExtractor */
    protected $entityExtractor;

    /**
     * AlmEntityManager constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     * @param AlmEntityExtractor $entityExtractor
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes, AlmEntityExtractor $entityExtractor)
    {
        $this->routes = $routes;
        $this->curl = $curl;
        $this->entityExtractor = $entityExtractor;
    }

    /**
     * @return AlmQuery
     */
    public function createQuery()
    {
        return new AlmQuery($this->curl, $this->routes, $this->entityExtractor);
    }

    public function create(AlmEntityInterface $entity)
    {
        if ($entity->getId() !== null) {
            throw new AlmEntityManagerException('Id cannot be specified for new entity');
        }

        $entityXml = $this->entityExtractor->pack($entity)->asXML();
        echo $entityXml;
    }

}
