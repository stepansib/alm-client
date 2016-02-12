<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 11:44
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmCurlException;
use StepanSib\AlmClient\Exception\AlmException;

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

    /**
     * AlmCurl constructor.
     * @param AlmCurlCookieStorage $cookieStorage
     */
    public function __construct(AlmCurlCookieStorage $cookieStorage)
    {
        $this->cookieStorage = $cookieStorage;
        return $this;
    }

    /**
     * @return $this
     */
    private function curlInit()
    {
        if (null === $this->curl) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_HEADER, 0);
            curl_setopt($this->curl, CURLOPT_HTTPGET, 1);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5); //connection timeout
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 30); //overall timeout

            $this->clearResults();
        }

        return $this;
    }

    /**
     * @param $url
     * @param bool $useCookie
     * @param array $headers
     * @return $this
     * @throws AlmCurlException
     * @throws AlmException
     */
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

        $result = curl_exec($this->curl);

        if (curl_errno($this->curl) === 0) {
            $this->result = $result;
            $this->info = curl_getinfo($this->curl);

            if (!$this->isResponseValid()) {
                switch ($this->getHttpCode()) {
                    case '401':
                        throw new AlmException('401: unauthenticated request');
                        break;
                    case '403':
                        throw new AlmException('403: unauthenticated request');
                        break;
                    case '404':
                        throw new AlmException('404: resource not found');
                        break;
                    case '405':
                        throw new AlmException('405: method not supported by resource');
                        break;
                    case '406':
                        throw new AlmException('406: unsupported ACCEPT type');
                        break;
                    case '415':
                        throw new AlmException('415: unsupported request content type');
                        break;
                    case '500':
                        throw new AlmException('500: Internal server error');
                        break;
                }
            }
        } else {
            throw new AlmCurlException('Curl error: ' . curl_error($this->curl));
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string|null
     */
    public function getHttpCode()
    {
        if (null !== $this->getInfo()) {
            $info = $this->getInfo();
            return $info['http_code'];
        } else {
            return null;
        }
    }

    /**
     * @return $this
     * @throws AlmCurlException
     */
    public function createCookie()
    {
        $this->curlInit();

        if ($this->curl !== null) {
            curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookieStorage->createCurlCookieFile()->getCurlCookieFile());
        } else {
            throw new AlmCurlException('Curl not initialized');
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function clearResults()
    {
        $this->result = null;
        $this->info = null;

        return $this;
    }

    /**
     * @return $this
     */
    public function close()
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
            $this->curl = null;

            $this->clearResults();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isResponseValid()
    {
        if ($this->getHttpCode() == '200' || $this->getHttpCode() == '201') {
            return true;
        }
        return false;
    }
}
