<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 12:58
 */

namespace StepanSib\AlmClient;

class AlmRoutes
{

    protected $hostUrl;

    protected $domain;

    protected $project;

    public function __construct(array $connectionOptions)
    {
        $this->hostUrl = $connectionOptions['host'];
        $this->domain = $connectionOptions['domain'];
        $this->project = $connectionOptions['project'];
    }

    public function getLoginUrl()
    {
        return $this->hostUrl . '/qcbin/authentication-point/authenticate';
    }

    public function getLogoutUrl()
    {
        return $this->hostUrl . '/qcbin/authentication-point/logout';
    }

    public function getIsAuthenticatedUrl()
    {
        return $this->hostUrl . '/qcbin/rest/is-authenticated';
    }

    public function getEntityUrl()
    {
        return $this->hostUrl . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project;
    }

}
