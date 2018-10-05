<?php
/**
 * @author Bogdan SOOS <bogdan.soos@dynweb.org>.
 * @created 10/5/18 3:22 PM
 * @version 1.0
 */
namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityParametersManagerException;

class AlmFolderManager
{

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var \SimpleXMLElement */
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
     * @return mixed
     */
    public function getFolders($type)
    {
        if (null === $this->folders) {
            try {
                $this->refreshFolders($type);
            } catch (Exception\AlmCurlException $e) {
            } catch (AlmEntityParametersManagerException $e) {
            } catch (Exception\AlmException $e) {
            }
        }

        return $this->folders;
    }


    /**
     * @param string $type folder type test-folders|test-set-folders
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    protected function refreshFolders($type)
    {
        $this->curl->exec($this->routes->getFoldersUrl($type));
        $xml = simplexml_load_string($this->curl->getResult());

        if (false === $xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $this->folders = $xml;
    }
}
