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

    /** @var \SimpleXMLElement */
    protected $steps;

    /**
     * AlmEntityLocker constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes)
    {
        $this->curl = $curl;
        $this->routes = $routes;
    }

    /**
     * @param int $runId
     * @param int $start
     * @param int $pageSize
     * @param bool $refresh force download
     * @return mixed
     */
    public function getRunSteps($runId, $start = 1, $pageSize = 2000, $refresh = false)
    {
        if (null === $this->steps || $refresh !== false) {
            try {
                $this->refreshSteps($runId, $start, $pageSize);
            } catch (Exception\AlmCurlException $e) {
            } catch (AlmEntityParametersManagerException $e) {
            } catch (Exception\AlmException $e) {
            }
        }

        return $this->steps;
    }


    /**
     * @param int $runId RUN ID
     * @param int $start
     * @param int $pageSize
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    protected function refreshSteps($runId, $start = 1, $pageSize = 2000)
    {
        $this->curl->exec($this->routes->getRunStepsUrl($runId, $start, $pageSize));
        $xml = simplexml_load_string($this->curl->getResult());

        if (false === $xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $this->steps = $xml;
    }
}
