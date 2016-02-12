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

    public function pack()
    {
        // TODO: Implement pack() method.
    }

    /**
     * @param \SimpleXMLElement $entityXml
     * @return AlmEntity
     */
    public function extract(\SimpleXMLElement $entityXml)
    {
        $entity = new AlmEntity();

        $entityXml = $entityXml->Fields[0];
        foreach ($entityXml->Field as $field) {
            foreach ($this->fieldsMapping as $xmlPropertyMapping => $entityPropertyMapping) {
                if ($field->attributes()->Name == $xmlPropertyMapping) {
                    $setter = 'set' . $entityPropertyMapping;
                    if (method_exists($entity, $setter)) {
                        $entity->$setter($field->Value[0]);
                    }
                }
            }
        }

        return $entity;
    }

}