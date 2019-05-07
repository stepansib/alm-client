<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 11.02.2016
 * Time: 16:33
 */

namespace StepanSib\AlmClient;

use SimpleXMLElement;

/**
 * Class AlmEntityExtractor
 */
class AlmEntityExtractor
{

    /** @var AlmEntityParametersManager */
    protected $almEntityParametersManager;

    /**
     * AlmEntityExtractor constructor.
     * @param AlmEntityParametersManager $almEntityParametersManager
     */
    public function __construct(AlmEntityParametersManager $almEntityParametersManager)
    {
        $this->almEntityParametersManager = $almEntityParametersManager;
    }

    /**
     * @param AlmEntity $entity
     * @param array $editableParameters
     * @return SimpleXMLElement
     */
    /**
     * @param AlmEntity $entity
     * @param array $editableParameters
     * @return SimpleXMLElement
     */
    public function pack(AlmEntity $entity, array $editableParameters = [])
    {
        $xml = new SimpleXMLElement('<Entity></Entity>');
        $xml->addAttribute('Type', $entity->getType());
        $xmlFields = $xml->addChild('Fields');

        $parameters = $entity->getParameters();

        foreach ($parameters as $field => $value) {
            $isParameterPackable = true;

            if (count($editableParameters) > 0 && !in_array($field, $editableParameters)) {
                $isParameterPackable = false;
            }

            if ($isParameterPackable) {
                $xmlField = $xmlFields->addChild('Field');
                $xmlField->addAttribute('Name', $field);
                if (is_array($value)) {
                    foreach ($value as $item) {
                        $xmlField->addChild('Value', $item);
                    }
                } else {
                    $xmlField->addChild('Value', $value);
                }
            }
        }

        return $xml;
    }

    /**
     * @param SimpleXMLElement $entityXml
     * @return AlmEntity
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmEntityParametersManagerException
     * @throws Exception\AlmException
     */
    public function extract(SimpleXMLElement $entityXml)
    {
        $entity = new AlmEntity((string)$entityXml->attributes()->Type);

        $entityFieldsData = $this->almEntityParametersManager->getEntityTypeFields(AlmEntityManager::ENTITY_TYPE_DEFECT);

        $entityXml = $entityXml->Fields[0];
        foreach ($entityXml->Field as $field) {
            $fieldName = (string)$field->attributes()->Name;

            $value = $this->processValueType($field->Value[0]);
            if (isset($entityFieldsData[$fieldName]) && $entityFieldsData[$fieldName]['multiple'] === true) {
                $value = [];
                foreach ($field->Value as $arrValue) {
                    $value[] = $this->processValueType($arrValue);
                }
            }

            $entity->setParameter($fieldName, $value, false);
        }

        return $entity;
    }

    /**
     * @param $value
     * @return int|string
     */
    public function processValueType($value)
    {
        $value = (string)$value;

        if (is_numeric($value)) {
            return (int)$value;
        }

        if (is_string($value)) {
            return (string)$value;
        }
    }

}
