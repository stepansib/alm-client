<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 11.02.2016
 * Time: 16:33
 */

namespace StepanSib\AlmClient;

class AlmEntityExtractor implements AlmEntityExtractorInterface
{

    /** @var array */
    protected $fieldsMapping;

    /** @var  string */
    protected $className;

    public function __construct($entityClass, array $fieldsMapping)
    {
        $this->fieldsMapping = $fieldsMapping;
        $this->className = $entityClass;
    }

    public function toXml()
    {
        // TODO: Implement toXml() method.
    }

    /**
     * @param \SimpleXMLElement $entityXml
     * @return AlmEntity
     */
    public function fromXml(\SimpleXMLElement $entityXml)
    {
        $entity = new AlmEntity();

        $entityXml = $entityXml->Fields[0];
        foreach ($entityXml->Field as $field) {
            //echo $field->attributes()->Name . ': ' . $field->Value[0] . '<br/>';
            foreach ($this->fieldsMapping as $xmlProperty => $entityProperty) {
                if ($field->attributes()->Name == $xmlProperty) {
                    $setter = 'set' . $xmlProperty;
                    if (method_exists($entity, $setter)) {
                        $entity->$setter($field->Value[0]);
                    }
                }
            }
        }

        return $entity;

    }

}