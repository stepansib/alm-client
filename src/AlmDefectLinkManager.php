<?php
/**
 * @author Bogdan SOOS <bogdan.soos@dynweb.org>.
 * @created 10/11/18 5:12 PM
 * @version 1.0
 */

namespace StepanSib\AlmClient;

use Exception;

/**
 * Class AlmDefectLinkManager
 */
class AlmDefectLinkManager
{
    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var AlmEntityParametersManager */
    protected $almEntityParametersManager;

    /** @var AlmEntityExtractor */
    protected $almEntityExtractor;

    /** @var AlmEntity[] */
    protected $attachments = null;

    /**
     * AlmDefectLinkManager constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     * @param AlmEntityParametersManager $almEntityParametersManager
     * @param AlmEntityExtractor $almEntityExtractor
     */
    public function __construct(
        AlmCurl $curl,
        AlmRoutes $routes,
        AlmEntityParametersManager $almEntityParametersManager,
        AlmEntityExtractor $almEntityExtractor
    )
    {
        $this->curl = $curl;
        $this->routes = $routes;
        $this->almEntityParametersManager = $almEntityParametersManager;
        $this->almEntityExtractor = $almEntityExtractor;
    }

    /**
     * @param int $defectId
     * @return array|AlmLinkedEntity[]
     */
    public function getDefectLinks(int $defectId): array
    {
        $linkedDefects = [];

        try {
            $this->curl->exec($this->routes->getDefectLinksUrl($defectId));
            $xml = simplexml_load_string($this->curl->getResult());
        } catch (Exception $e) {
        }

        if (!is_null($xml)) {
            foreach ($xml->children() as $nodeName => $linkedEntityData) {
                $type = null;
                $id = null;
                foreach ($linkedEntityData as $field => $value) {
                    $field = (string)$field;
                    $value = $this->almEntityExtractor->processValueType($value);

                    if ($field === 'second-endpoint-id') {
                        $id = $value;
                    }
                    if ($field === 'second-endpoint-type') {
                        $type = $value;
                    }
                }
                $linkedDefects[] = new AlmLinkedEntity($id, $type);
            }
        }

        return $linkedDefects;
    }

}
