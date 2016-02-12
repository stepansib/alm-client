<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 11.02.2016
 * Time: 16:33
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityExtractorException;

class AlmEntityExtractor
{

    /** @var array */
    protected $fieldsMapping;

    /** @var  string */
    protected $className;

    /**
     * AlmEntityExtractor constructor.
     * @param $entityClass
     * @param array $fieldsMapping
     */
    public function __construct($entityClass, array $fieldsMapping)
    {
        $this->fieldsMapping = $fieldsMapping;
        $this->className = $entityClass;
    }

    public function pack()
    {

    }

    /**
     * @param \SimpleXMLElement $entityXml
     * @return AlmEntity
     * @throws AlmEntityExtractorException
     */
    public function extract(\SimpleXMLElement $entityXml)
    {
        try {
            $entity = new AlmEntity();
            $entityXml = $entityXml->Fields[0];
            foreach ($entityXml->Field as $field) {
                foreach ($this->fieldsMapping as $xmlPropertyMapping => $entityPropertyMapping) {
                    if ($field->attributes()->Name == $xmlPropertyMapping) {
                        $setter = 'set' . $entityPropertyMapping;
                        if (method_exists($entity, $setter)) {
                            $entity->$setter($field->Value[0]);
                        } else {
                            throw new \Exception('Setter \'' . $setter . '\' not found in ' . get_class($entity));
                        }
                    }
                }
            }
            return $entity;
        } catch (\Exception $e) {
            throw new AlmEntityExtractorException($e->getMessage());
        }

    }

}