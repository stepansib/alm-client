<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 11:44
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmExceptionGenerator;

Class AlmCurl
{

    /** @var resource */
    protected $curl;

    /** @var  mixed */
    protected $result;

    /** @var  mixed */
    protected $info;

    /** @var AlmCurlCookieStorage */
    protected $cookieStorage;

    public function __construct(AlmCurlCookieStorage $cookieStorage)
    {
        $this->cookieStorage = $cookieStorage;
    }

    private function curlInit()
    {
        if (null === $this->curl) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_HEADER, 0);
            curl_setopt($this->curl, CURLOPT_HTTPGET, 1);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 2); //connection timeout
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 5); //overall timeout

            $this->clearResults();
        }

        return;
    }

    public function exec($url, $useCookie = true, array $headers = array())
    {
        $this->curlInit();

        curl_setopt($this->curl, CURLOPT_URL, $url);

        if (count($headers) > 0) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array());
        }

        if ($useCookie) {
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookieStorage->getCurlCookieFile());
        } else {
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, null);
        }

        $this->result = curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl);

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function getHttpCode()
    {
        if (null !== $this->getInfo()) {
            $info = $this->getInfo();
            return $info['http_code'];
        } else {
            return null;
        }
    }

    public function createCookie()
    {
        $this->curlInit();

        if ($this->curl !== null) {
            curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookieStorage->createCurlCookieFile());
        } else {
            AlmExceptionGenerator::throwCurlNotInitialized();
        }

        return $this;
    }

    protected function clearResults()
    {
        $this->result = null;
        $this->info = null;
    }

    public function close()
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
            $this->curl = null;

            $this->clearResults();
        } else {
            AlmExceptionGenerator::throwCurlNotInitialized();
        }

        return $this;
    }
}
