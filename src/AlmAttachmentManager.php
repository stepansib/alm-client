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
                $this->refreshAttachments($entityId, $entityType);
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
     * @param string $path path to save the file
     * @param string $filename file name
     * @return null|string
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function downloadAttachment($entityId, $entityType, $path, $filename, $attachementId)
    {
        $file = fopen($path . $filename, 'w+');
        $this
            ->curl
            ->setDownload($file)
            ->setHeaders(['Accept: application/octet-stream'])
            ->exec($this->routes->getAttachmentsDownloadUrl($entityId, $entityType, $attachementId));

        fclose($file);

        return $this->curl->getResult();
    }

    /**
     * @param int $entityId entity id
     * @param string $entityType entity type
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    protected function refreshAttachments($entityId, $entityType)
    {
        $this->curl->exec($this->routes->getAttachmentsUrl($entityId, $entityType));
        $xml = simplexml_load_string($this->curl->getResult());

        if (false === $xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $extractor = new AlmEntityExtractor();
        foreach ($xml->Entity as $entity){
            $this->attachments[] = $extractor->extract($entity);
        }
    }
}
