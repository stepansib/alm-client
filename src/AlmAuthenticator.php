<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 11:24
 */

namespace StepanSib\AlmClient;

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
     * @return bool
     */
    public function login()
    {

        $headers = array("GET /HTTP/1.1", "Authorization: Basic " . base64_encode($this->userName . ":" . $this->password));

        $httpCode = $this->curl->createCookie()->exec($this->routes->getLoginUrl(), false, $headers)->getHttpCode();
        $this->curl->close();

        if ($httpCode == '200') {
            return true;
        } else {
            $this->cookieStorage->deleteCurlCookieFile();
            return false;
        }
    }

    /**
     * Simple checks - whether user is authenticated in ALM
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        if ($this->cookieStorage->isCurlCookieFileExist()) {
            $httpCode = $this->curl->exec($this->routes->getIsAuthenticatedUrl())->getHttpCode();
            $this->curl->close();

            if ($httpCode == '401') {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $this->curl->exec($this->routes->getLogoutUrl());
        $this->curl->close();
        $this->cookieStorage->deleteCurlCookieFile();

        return $this;
    }

}
