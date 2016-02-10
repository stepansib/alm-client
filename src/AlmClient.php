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

    /**
     * AlmClient constructor.
     * @param array $connectionOptions
     */
    public function __construct(array $connectionOptions)
    {
        $this->cookieStorage = new AlmCurlCookieStorage();
        $this->curl = new AlmCurl($this->cookieStorage);
        $this->routes = new AlmRoutes($connectionOptions);
        $this->authenticator = new AlmAuthenticator($connectionOptions, $this->curl, $this->cookieStorage, $this->routes);
    }

    /**
     * @return AlmAuthenticator
     */
    public function getAuthenticator()
    {
        return $this->authenticator;
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

}