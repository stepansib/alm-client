<?php
/**
 * @author Bogdan SOOS <bogdan.soos@dynweb.org>.
 * @created 10/11/18 5:12 PM
 * @version 1.0
 */

namespace StepanSib\AlmClient;

use Exception;
use StepanSib\AlmClient\Exception\AlmCurlException;
use StepanSib\AlmClient\Exception\AlmEntityException;
use StepanSib\AlmClient\Exception\AlmEntityParametersManagerException;
use StepanSib\AlmClient\Exception\AlmException;

/**
 * Class AlmAttachmentManager
 */
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
     *
     * @return array|AlmEntity[]|null
     * @throws AlmEntityParametersManagerException
     * @throws AlmCurlException
     * @throws AlmException
     */
    public function getAttachments($entityId, $entityType): ?array
    {
        $this->attachments = [];
        try {
            $this->curl->exec($this->routes->getAttachmentsUrl($entityId, $entityType));
            $xml = simplexml_load_string($this->curl->getResult());
        } catch (Exception $e) {
        }

        if (!$xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $extractor = new AlmEntityExtractor($this->almEntityParametersManager);
        foreach ($xml->Entity as $entity) {
            $this->attachments[] = $extractor->extract($entity);
        }

        return $this->attachments;
    }

    /**
     * @param $path
     * @param AlmEntity $attachment
     * @return string|null
     * @throws AlmCurlException
     * @throws AlmException
     * @throws AlmEntityException
     * @throws Exception
     */
    public function downloadAttachment(
        $path,
        AlmEntity $attachment
    )
    {
        $file = fopen($path . $attachment->getParameter('name'), 'w+');

        $this->curl
            ->setDownload($file)
            ->setHeaders(['Accept: application/octet-stream'])
            ->exec($this->routes->getAttachmentsDownloadUrl($attachment));

        fclose($file);

        $result = $this->curl->getResult();
        dump($result);

        return $result;
    }
}
