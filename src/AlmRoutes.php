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

    protected $host;

    protected $domain;

    protected $project;

    public function __construct($host, $domain, $project)
    {
        $this->host = $host;
        $this->domain = $domain;
        $this->project = $project;
    }

    public function getLoginUrl()
    {
        return $this->host . '/qcbin/authentication-point/authenticate';
    }

    public function getLogoutUrl()
    {
        return $this->host . '/qcbin/authentication-point/logout';
    }

    public function getAuthenticationCheckUrl()
    {
        return $this->host . '/qcbin/rest/is-authenticated';
    }

    public function getEntityUrl()
    {
        return $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project;
    }

}
