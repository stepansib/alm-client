<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 11:24
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmConnectionException;

class AlmAuthenticator
{

    /** @var array */
    protected $connectionOptions;

    /** @var  AlmCurl */
    protected $curl;

    /** @var  AlmCurlCookieStorage */
    protected $cookieStorage;

    /** @var  AlmRoutes */
    protected $routes;

    /**
     * AlmAuthenticator constructor.
     * @param array $connectionOptions
     * @param AlmCurl $almCurl
     * @param AlmCurlCookieStorage $cookieStorage
     * @param AlmRoutes $routes
     */
    public function __construct(array $connectionOptions, AlmCurl $almCurl, AlmCurlCookieStorage $cookieStorage, AlmRoutes $routes)
    {
        $this->connectionOptions = $connectionOptions;
        $this->curl = $almCurl;
        $this->cookieStorage = $cookieStorage;
        $this->routes = $routes;

        return $this;
    }

    /**
     * @return array
     */
    public function getConnectionOptions()
    {
        return $this->connectionOptions;
    }

    /**
     * Tries to login with credentials specified in $connectionOptions array
     *
     * @return bool
     */
    public function login()
    {

        $headers = array("GET /HTTP/1.1", "Authorization: Basic " . base64_encode($this->connectionOptions['username'] . ":" . $this->connectionOptions['password']));
        $curl = $this->getCurl($this->routes->getLoginUrl(), false, $headers);

        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookieStorage->createCurlCookieFile());

        curl_exec($curl);
        $response = curl_getinfo($curl);

        $this->curlClose();

        if ($response['http_code'] == '200') {
            return true;
        } else {
            $this->cookieStorage->deleteCurlCookieFile();
            return false;
        }
    }

    /*
    public function getDefect($defectId)
    {

        $defectUrl = "/qcbin/rest/domains/" . $this->connectionOptions['domain'] . "/projects/" . $this->connectionOptions['project'] . "/defects/" . $defectId;
        $curl = $this->getCurl($defectUrl);

        $xml = simplexml_load_string(curl_exec($curl));
        print_r($xml);

        $this->curlClose();
    }
    */

    /**
     * Simple checks - whether user is authenticated in ALM
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        if ($this->cookieStorage->isCurlCookieFileExist()) {
            $curl = $this->getCurl($this->routes->getIsAuthenticatedUrl());

            curl_exec($curl);
            $response = curl_getinfo($curl);

            $this->curlClose();

            if ($response['http_code'] == '401') {
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

        return;
    }

}