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
    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $project;

    /**
     * AlmRoutes constructor.
     * @param string $host API host
     * @param string $domain project domain
     * @param string $project project name
     */
    public function __construct($host, $domain, $project)
    {
        $this->host = $host;
        $this->domain = $domain;
        $this->project = $project;
    }

    public function getLoginUrl()
    {
        return $this->host . '/qcbin/api/authentication/sign-in';
    }

    public function getLogoutUrl()
    {
        return $this->host . '/qcbin/api/authentication/sign-out';
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

    /**
     * Get folders URL.
     *
     * @param string $folderType folder type
     * @param int $start start page
     * @param int $pageSize page size
     * @return string
     */
    public function getFoldersUrl($folderType, $start = 1, $pageSize = 500)
    {
        return sprintf(
            '%s/qcbin/rest/domains/%s/projects/%s/%s?page-size=%d&start-index=%d',
            $this->host,
            $this->domain,
            $this->project,
            $folderType,
            $pageSize,
            $start
        );
    }

    /**
     * Get attachments URL
     *
     * @param int $entityId Entity ID
     * @param string $entityType Entity type
     *
     * @return string
     */
    public function getAttachmentsUrl($entityId, $entityType)
    {
        return sprintf(
            '%s/qcbin/rest/domains/%s/projects/%s/%s/%d/attachments',
            $this->host,
            $this->domain,
            $this->project,
            $entityType,
            $entityId
        );
    }

    /**
     * Get attachments download URL
     *
     * @param int $entityId Entity ID
     * @param string $entityType Entity type
     * @param string $filename attachment file name
     * @return string
     */
    public function getAttachmentsDownloadUrl($entityId, $entityType, $filename)
    {
        return sprintf(
            '%s/qcbin/rest/domains/%s/projects/%s/%s/%d/attachments/%s',
            $this->host,
            $this->domain,
            $this->project,
            $entityType,
            $entityId,
            $filename
        );
    }

    /**
     * Get design steps URL
     *
     * @return string
     */
    public function getDesignStepsUrl()
    {
        return sprintf(
            '%s/qcbin/rest/domains/%s/projects/%s/design-steps',
            $this->host,
            $this->domain,
            $this->project
        );
    }
}
