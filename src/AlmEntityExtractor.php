<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 11.02.2016
 * Time: 16:33
 */

namespace StepanSib\AlmClient;

class AlmEntityExtractor
{

    public function pack(AlmEntity $entity, array $editableParameters = array())
    {
        $xml = new \SimpleXMLElement('<Entity></Entity>');
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
                $xmlField->addChild('Value', $value);
            }
        }

        return $xml;
    }

    /**
     * @param \SimpleXMLElement $entityXml
     * @return AlmEntity
     */
    public function extract(\SimpleXMLElement $entityXml)
    {
        $entity = new AlmEntity($entityXml->attributes()->Type);

        $entityXml = $entityXml->Fields[0];
        foreach ($entityXml->Field as $field) {
            $entity->setParameter((string)$field->attributes()->Name, $field->Value[0], false);
        }

        return $entity;
    }

}
