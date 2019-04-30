<?php
/**
 * @author Bogdan SOOS <bogdan.soos@dynweb.org>.
 * @created 10/5/18 3:22 PM
 * @version 1.0
 */
namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityParametersManagerException;

class AlmRunStepsManager
{

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var AlmEntityParametersManager */
    protected $almEntityParametersManager;

    /** @var \SimpleXMLElement */
    protected $steps;

    /**
     * AlmRunStepsManager constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     * @param AlmEntityParametersManager $almEntityParametersManager
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes,AlmEntityParametersManager $almEntityParametersManager)
    {
        $this->curl = $curl;
        $this->routes = $routes;
        $this->almEntityParametersManager = $almEntityParametersManager;
    }

    /**
     * @param int $runId
     * @param int $start
     * @param int $pageSize
     * @return mixed
     */
    public function getRunSteps($runId, $start = 1, $pageSize = 2000)
    {
        try {
            $this->curl->exec($this->routes->getRunStepsUrl($runId, $start, $pageSize));
            $xml = simplexml_load_string($this->curl->getResult());

            if (false === $xml) {
                throw new AlmEntityParametersManagerException('Cannot get lists data');
            }

            $extractor = new AlmEntityExtractor($this->almEntityParametersManager);
            foreach ($xml->Entity as $entity){
                $this->steps[] = $extractor->extract($entity);
            }
        } catch (Exception\AlmCurlException $e) {
        } catch (AlmEntityParametersManagerException $e) {
        } catch (Exception\AlmException $e) {
        }

        return $this->steps;
    }
}
