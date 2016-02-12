<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 11:24
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmAuthenticationException;

class AlmAuthenticator
{

    /** @var  AlmCurl */
    protected $curl;

    /** @var  AlmCurlCookieStorage */
    protected $cookieStorage;

    /** @var  AlmRoutes */
    protected $routes;

    /** @var  string */
    protected $userName;

    /** @var  string */
    protected $password;

    /**
     * AlmAuthenticator constructor.
     * @param $userName
     * @param $password
     * @param AlmCurl $almCurl
     * @param AlmCurlCookieStorage $cookieStorage
     * @param AlmRoutes $routes
     */
    public function __construct($userName, $password, AlmCurl $almCurl, AlmCurlCookieStorage $cookieStorage, AlmRoutes $routes)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->curl = $almCurl;
        $this->cookieStorage = $cookieStorage;
        $this->routes = $routes;

        return $this;
    }

    /**
     * Tries to login with credentials specified
     *
     * @return $this
     * @throws AlmAuthenticationException
     */
    public function login()
    {
        try {
            $headers = array("GET /HTTP/1.1", "Authorization: Basic " . base64_encode($this->userName . ":" . $this->password));

            $isValid = $this->curl->createCookie()->setHeaders($headers)->exec($this->routes->getLoginUrl())->isResponseValid();
            $this->curl->close();

            if (!$isValid) {
                $this->cookieStorage->deleteCurlCookieFile();
            }
        } catch (\Exception $e) {
            $this->cookieStorage->deleteCurlCookieFile();
            throw new AlmAuthenticationException('Authentication error : ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Simple checks - whether user is authenticated in ALM
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        try {
            $this->curl->exec($this->routes->getAuthenticationCheckUrl());
            if ($this->curl->isResponseValid()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Logout method
     */
    public function logout()
    {
        try {
            $this->curl->exec($this->routes->getLogoutUrl());
            $this->curl->close();
            $this->cookieStorage->deleteCurlCookieFile();
        } catch (\Exception $e) {
            throw new AlmAuthenticationException('Authentication error : ' . $e->getMessage());
        }
        return $this;
    }

}
