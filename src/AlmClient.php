<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 12:43
 */

namespace StepanSib\AlmClient;

Class AlmClient
{

    /** @var  AlmAuthenticator */
    protected $authenticator;

    /** @var  AlmCurl */
    protected $curl;

    /** @var  AlmCurlCookieStorage */
    protected $cookieStorage;

    /** @var  AlmRoutes */
    protected $routes;

    /** @var  AlmEntityManager */
    protected $manager;

    /** @var  AlmEntityExtractorInterface */
    protected $entityEtractor;

    /**
     * AlmClient constructor.
     * @param array $connectionOptions
     */
    public function __construct(array $connectionOptions)
    {
        $this->entityEtractor = new AlmEntityExtractor(get_class(new AlmEntity()), array(
            'id' => 'id',
            'owner' => 'owner',
            'name' => 'name',
            'description' => 'description',
            'dev-comments' => 'comments',
            'priority' => 'priority',
            'status' => 'status',
        ));
        $this->cookieStorage = new AlmCurlCookieStorage();
        $this->curl = new AlmCurl($this->cookieStorage);
        $this->routes = new AlmRoutes($connectionOptions['host'], $connectionOptions['domain'], $connectionOptions['project']);
        $this->authenticator = new AlmAuthenticator($connectionOptions['username'], $connectionOptions['password'], $this->curl, $this->cookieStorage, $this->routes);
        $this->manager = new AlmEntityManager($this->curl, $this->routes, $this->entityEtractor);
    }

    /**
     * @return AlmAuthenticator
     */
    public function getAuthenticator()
    {
        return $this->authenticator;
    }

    /**
     * @return AlmEntityExtractor|AlmEntityExtractorInterface
     */
    public function getEntityEtractor()
    {
        return $this->entityEtractor;
    }

    /**
     * @return AlmCurl
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @return AlmRoutes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return AlmCurlCookieStorage
     */
    public function getCookieStorage()
    {
        return $this->cookieStorage;
    }

    /**
     * @return AlmEntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }

}
