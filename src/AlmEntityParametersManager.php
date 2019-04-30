<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 15:07
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityParametersManagerException;

class AlmEntityParametersManager
{

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /** @var \SimpleXMLElement */
    protected $lists;

    /** @var array */
    protected $cachedFields = [];

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
     * @return mixed
     * @throws AlmEntityParametersManagerException
     */
    public function getLists()
    {
        if (null === $this->lists) {
            $this->refreshLists();
        }

        return $this->lists;
    }

    /**
     * @param $listId
     * @return array
     */
    protected function getListValues($listId)
    {
        $listItems = array();

        foreach ($this->getLists() as $list) {
            if ($list->Id == $listId) {
                foreach ($list->Items[0] as $listItem) {
                    array_push($listItems, (string)$listItem->attributes()->value);
                }
            }
        }

        return $listItems;
    }

    /**
     * @param $entityType
     * @param bool $onlyRequiredFields
     * @param bool $asXml
     * @return array|mixed
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function getEntityTypeFields($entityType, $onlyRequiredFields = false, $asXml = false)
    {
        $cacheKey = $entityType . ($onlyRequiredFields ? '1' : '0') . ($asXml ? '1' : '0');

        if (!isset($this->cachedFields[$cacheKey])) {

            $this->curl->exec($this->routes->getEntityFieldsUrl($entityType, $onlyRequiredFields));
            $xml = simplexml_load_string($this->curl->getResult());

            if (false === $xml) {
                throw new AlmEntityParametersManagerException('Cannot get entity required fields, server returned incorrect XML');
            }

            if ($asXml) {
                return $xml->asXML();
            }

            $fields = array();

            /** @var \SimpleXMLElement $field */
            foreach ($xml as $field) {
                $fieldData = array();

                $fieldData['label'] = (string)$field->attributes()->Label;
                $fieldData['editable'] = (string)$field->Editable[0] == "true" ? true : false;
                $fieldData['multiple'] = (string)$field->SupportsMultivalue[0] == "true" ? true : false;

                if (property_exists($field, 'List-Id')) {
                    $fieldData['list'] = $this->getListValues((string)$field->{'List-Id'});
                }

                $fields[(string)$field->attributes()->Name] = $fieldData;
            }

            $this->cachedFields[$cacheKey] = $fields;
        }

        return $this->cachedFields[$cacheKey];
    }

    /**
     * @param string $entityType
     * @return array
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function getEntityEditableParameters(string $entityType)
    {
        $arr = array();
        foreach ($this->getEntityTypeFields($entityType) as $fieldName => $fieldData) {
            if ($fieldData['editable']) {
                array_push($arr, $fieldName);
            }
        }
        return $arr;
    }

    /**
     * @throws AlmEntityParametersManagerException
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    protected function refreshLists()
    {
        $this->curl->exec($this->routes->getListsUrl());
        $xml = simplexml_load_string($this->curl->getResult());

        if (false === $xml) {
            throw new AlmEntityParametersManagerException('Cannot get lists data');
        }

        $this->lists = $xml;
    }
}
