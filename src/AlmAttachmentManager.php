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

    /** @var AlmEntityParametersManager */
    protected $almEntityParametersManager;

    /** @var AlmEntity[] */
    protected $attachments = null;

    /**
     * AlmAttachmentManager constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     * @param AlmEntityParametersManager $almEntityParametersManager
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes, AlmEntityParametersManager $almEntityParametersManager)
    {
        $this->curl = $curl;
        $this->routes = $routes;
        $this->almEntityParametersManager = $almEntityParametersManager;
    }

    /**
     * @param int $entityId entity id
     * @param string $entityType entity type
     * @return AlmEntity[]
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function getAttachments($entityId, $entityType): ?array
    {
        $this->attachments = [];
        try {
            $this->curl->exec($this->routes->getAttachmentsUrl($entityId, $entityType));
            $xml = simplexml_load_string($this->curl->getResult());
        } catch (\Exception $e){}

        if (!$xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $extractor = new AlmEntityExtractor($this->almEntityParametersManager);
        foreach ($xml->Entity as $entity){
            $this->attachments[] = $extractor->extract($entity);
        }

        return $this->attachments;
    }

    /**
     * @param string $path path to save the file
     * @param string $filename file name
     * @param int $attachmentId attachment id
     * @return null|string
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function downloadAttachment($path, $filename, $attachmentId)
    {
        $file = fopen($path . $filename, 'w+');
        $this
            ->curl
            ->setDownload($file)
            ->setHeaders(['Accept: application/octet-stream'])
            ->exec($this->routes->getAttachmentsDownloadUrl($attachmentId));

        fclose($file);

        return $this->curl->getResult();
    }
}
