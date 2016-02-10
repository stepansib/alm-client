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

    const SESSION_COOKIE_FILE_KEY = "alm_cookie_file";

    /** @var array */
    protected $connectionOptions;

    /** @var  resource */
    protected $curl;

    /**
     * HPAlmApiClient constructor.
     * @param array $connectionOptions
     */
    public function __construct(array $connectionOptions)
    {
        $this->connectionOptions = $connectionOptions;
    }

    /**
     * Tries to login with credentials specified in $connectionOptions array
     *
     * @return bool
     */
    public function login()
    {

        $loginUrl = '/qcbin/authentication-point/authenticate';
        $headers = array("GET /HTTP/1.1", "Authorization: Basic " . base64_encode($this->connectionOptions['username'] . ":" . $this->connectionOptions['password']));
        $curl = $this->getCurl($loginUrl, false, $headers);

        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->createCurlCookieFile());

        curl_exec($curl);
        $response = curl_getinfo($curl);

        $this->curlClose();

        if ($response['http_code'] == '200') {
            return true;
        } else {
            $this->deleteCurlCookieFile();
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
        if ($this->isCurlCookieFileExist()) {
            $curl = $this->getCurl('/qcbin/rest/is-authenticated');

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

    private function createCurlCookieFile()
    {
        if (!$this->isCurlCookieFileExist()) {
            $_SESSION[self::SESSION_COOKIE_FILE_KEY] = tempnam("/tmp", "ALMCOOKIE");
        }

        return $_SESSION[self::SESSION_COOKIE_FILE_KEY];
    }

    private function deleteCurlCookieFile()
    {
        if ($this->isCurlCookieFileExist()) {
            unlink($_SESSION[self::SESSION_COOKIE_FILE_KEY]);
            unset($_SESSION[self::SESSION_COOKIE_FILE_KEY]);
        } else {
            $this->throwCookieFileDoesNotExist();
        }
    }

    private function getCurlCookieFile()
    {
        if ($this->isCurlCookieFileExist()) {
            return $_SESSION[self::SESSION_COOKIE_FILE_KEY];
        } else {
            $this->throwCookieFileDoesNotExist();
        }
    }

    private function isCurlCookieFileExist()
    {
        if (isset($_SESSION[self::SESSION_COOKIE_FILE_KEY])) {
            if (file_exists($_SESSION[self::SESSION_COOKIE_FILE_KEY])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $logoutUrl = '/qcbin/authentication-point/logout';
        $curl = $this->getCurl($logoutUrl);
        curl_exec($curl);

        $this->curlClose();
        $this->deleteCurlCookieFile();

        return;
    }

    /**
     * Generates CURL instance or returns existed one
     *
     * @param $url
     * @param bool $useCookie
     * @param array $headers
     * @return resource
     */
    private function getCurl($url, $useCookie = true, array $headers = array())
    {
        if ($this->curl === null) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_URL, $this->connectionOptions['host'] . $url);
            curl_setopt($this->curl, CURLOPT_HEADER, 0);
            curl_setopt($this->curl, CURLOPT_HTTPGET, 1);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 2); //connection timeout
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 5); //overall timeout
        }

        if (count($headers) > 0) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array());
        }

        if ($useCookie) {
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->getCurlCookieFile());
        } else {
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, null);
        }

        return $this->curl;
    }

    /**
     * Closes curl instance if created
     */
    private function curlClose()
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
            $this->curl = null;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws AlmConnectionException
     */
    protected function throwCookieFileDoesNotExist()
    {
        throw new AlmConnectionException('Curl cookie file does not exist');
    }

    /**
     * @throws AlmConnectionException
     */
    protected function throwCurlConnectionTimeOut()
    {
        throw new AlmConnectionException('Сonnection timeout');
    }

}