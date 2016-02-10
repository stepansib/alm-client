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

    public function __construct(array $connectionOptions)
    {
        $this->cookieStorage = new AlmCurlCookieStorage();
        $this->curl = new AlmCurl($this->cookieStorage);
        $this->authenticator = new AlmAuthenticator($connectionOptions);
    }

}