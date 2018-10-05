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
        return $this->host . '/qcbin/authentication/sign-in';
    }

    public function getLogoutUrl()
    {
        return $this->host . '/qcbin/authentication/sign-out';
    }

    public function getAuthenticationCheckUrl()
    {
        return $this->host . '/qcbin/rest/is-authenticated';
    }

    public function getEntityUrl($entityType, $entityId = null)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $entityType;
        if (null !== $entityId) {
            $url .= '/' . $entityId;
        }
        return $url;
    }

    public function getEntityFieldsUrl($entityType, $onlyRequiredFields)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/customization/entities/' . $entityType . '/fields';
        if ($onlyRequiredFields) {
            $url .= '?required=true';
        }
        return $url;
    }

    public function getListsUrl($listId = null)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/customization/lists';
        if (null !== $listId) {
            $url .= '?id=' . $listId;
        }
        return $url;
    }

    public function getEntityCheckoutUrl($entityType, $entityId)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $entityType . '/' . $entityId . '/versions/check-out';
        return $url;
    }

    public function getEntityCheckinUrl($entityType, $entityId)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $entityType . '/' . $entityId . '/versions/check-in';
        return $url;
    }

    public function getEntityLockUrl($entityType, $entityId)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $entityType . '/' . $entityId . '/lock';
        return $url;
    }

    public function getFoldersUrl($folderType)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $folderType;
        return $url;
    }
}
