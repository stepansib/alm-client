<?php
/**
 * @author Bogdan SOOS <bogdan.soos@dynweb.org>.
 * @created 10/5/18 3:22 PM
 * @version 1.0
 */

namespace StepanSib\AlmClient;

use SimpleXMLElement;
use StepanSib\AlmClient\Exception\AlmEntityParametersManagerException;

/**
 * Class AlmFolderManager
 */
class AlmFolderManager
{

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var SimpleXMLElement */
    protected $folders;

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
     * @param string $type
     * @param int $start
     * @param int $pageSize
     * @param bool $refresh force download
     * @return mixed
     */
    public function getFolders($type, $start = 1, $pageSize = 500, $refresh = false)
    {
        if (null === $this->folders || $refresh !== false) {
            try {
                $this->refreshFolders($type, $start, $pageSize);
            } catch (Exception\AlmCurlException $e) {
            } catch (AlmEntityParametersManagerException $e) {
            } catch (Exception\AlmException $e) {
            }
        }

        return $this->folders;
    }


    /**
     * @param string $type folder type test-folders|test-set-folders
     * @param int $start
     * @param int $pageSize
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    protected function refreshFolders($type, $start = 1, $pageSize = 500)
    {
        $this->curl->exec($this->routes->getFoldersUrl($type, $start, $pageSize));
        $xml = simplexml_load_string($this->curl->getResult());

        if (false === $xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $this->folders = $xml;
    }
}
