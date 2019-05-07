<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 12:58
 */

namespace StepanSib\AlmClient;

/**
 * Class AlmRoutes
 */
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


    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->host . '/qcbin/authentication-point/authenticate';
    }

    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getLogoutUrl()
    {
        return $this->host . '/qcbin/authentication-point/logout';
    }

    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getAuthenticationCheckUrl()
    {
        return $this->host . '/qcbin/rest/is-authenticated';
    }

    /**
     * @param $entityType
     * @param null $entityId
     * @return string
     */
    /**
     * @param $entityType
     * @param null $entityId
     * @return string
     */
    public function getEntityUrl($entityType, $entityId = null)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $entityType;
        if (null !== $entityId) {
            $url .= '/' . $entityId;
        }
        return $url;
    }

    /**
     * @param $entityType
     * @param $onlyRequiredFields
     * @return string
     */
    /**
     * @param $entityType
     * @param $onlyRequiredFields
     * @return string
     */
    public function getEntityFieldsUrl($entityType, $onlyRequiredFields)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/customization/entities/' . $entityType . '/fields';
        if ($onlyRequiredFields) {
            $url .= '?required=true';
        }
        return $url;
    }

    /**
     * @param null $listId
     * @return string
     */
    /**
     * @param null $listId
     * @return string
     */
    public function getListsUrl($listId = null)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/customization/lists';
        if (null !== $listId) {
            $url .= '?id=' . $listId;
        }
        return $url;
    }

    /**
     * @param $entityType
     * @param $entityId
     * @return string
     */
    /**
     * @param $entityType
     * @param $entityId
     * @return string
     */
    public function getEntityCheckoutUrl($entityType, $entityId)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $entityType . '/' . $entityId . '/versions/check-out';
        return $url;
    }

    /**
     * @param $entityType
     * @param $entityId
     * @return string
     */
    /**
     * @param $entityType
     * @param $entityId
     * @return string
     */
    public function getEntityCheckinUrl($entityType, $entityId)
    {
        $url = $this->host . '/qcbin/rest/domains/' . $this->domain . '/projects/' . $this->project . '/' . $entityType . '/' . $entityId . '/versions/check-in';
        return $url;
    }

    /**
     * @param $entityType
     * @param $entityId
     * @return string
     */
    /**
     * @param $entityType
     * @param $entityId
     * @return string
     */
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
        return $this->getEntityUrl($entityType . 's', $entityId) . '/attachments';
    }

    /**
     * Get defect links
     *
     * @param int $defectId
     * @return string
     */
    public function getDefectLinksUrl(int $defectId)
    {
        return $this->getEntityUrl('defects', $defectId) . '/defect-links';
    }

    /**
     * @param AlmEntity $attachment
     * @return string
     * @throws Exception\AlmEntityException
     */
    public function getAttachmentsDownloadUrl(AlmEntity $attachment)
    {
        return $this->getEntityUrl(
                $attachment->getParameter('parent-type') . 's',
                $attachment->getParameter('parent-id')
            ) . '/attachments/' . $attachment->getParameter('name');
    }

    /**
     * Get design steps URL
     *
     * @return string
     */
    public function getDesignStepsUrl(): string
    {
        return sprintf(
            '%s/qcbin/rest/domains/%s/projects/%s/design-steps',
            $this->host,
            $this->domain,
            $this->project
        );
    }

    /**
     * Get run steps URL
     *
     * @param int $runId Run for which we need to get steps
     * @return string
     */
    public function getRunStepsUrl($runId): string
    {
        return sprintf(
            '%s/qcbin/rest/domains/%s/projects/%s/runs/%d/run-steps',
            $this->host,
            $this->domain,
            $this->project,
            $runId
        );
    }
}
