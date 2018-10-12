<?php
/**
 * @author Bogdan SOOS <bogdan.soos@dynweb.org>.
 * @created 10/11/18 5:12 PM
 * @version 1.0
 */
namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityParametersManagerException;

class AlmAttachmentManager
{
    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var \SimpleXMLElement */
    protected $attachments = null;

    /**
     * AlmAttachmentManager constructor.
     *
     * @param AlmCurl $curl curl client
     * @param AlmRoutes $routes routes object
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes)
    {
        $this->curl = $curl;
        $this->routes = $routes;
    }

    /**
     * @param int $entityId entity id
     * @param string $entityType entity type
     * @param bool $refresh force download
     * @return null|\SimpleXMLElement
     */
    public function getAttachments($entityId, $entityType, $refresh = false)
    {
        if ($this->attachments === null || $refresh !== false)
        {
            try {
                $this->refreshFolders($entityId, $entityType);
            } catch (Exception\AlmCurlException $e) {
            } catch (AlmEntityParametersManagerException $e) {
            } catch (Exception\AlmException $e) {
            }
        }

        return $this->attachments;
    }

    /**
     * @param int $entityId entity id
     * @param string $entityType entity type
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    protected function refreshFolders($entityId, $entityType)
    {
        $this->curl->exec($this->routes->getAttachmentsUrl($entityId, $entityType));
        $xml = simplexml_load_string($this->curl->getResult());

        if (false === $xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $this->attachments = $xml;
    }
}
