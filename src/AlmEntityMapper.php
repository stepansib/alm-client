<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 11.02.2016
 * Time: 16:33
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityExtractorException;
use StepanSib\AlmClient\AlmEntityInterface;

class AlmEntityMapper
{

    /** @var array */
    protected $fieldsMapping;

    /** @var  string */
    protected $entityClass;

    /**
     * AlmEntityExtractor constructor.
     * @param $entityClass
     * @param array $fieldsMapping
     */
    public function __construct($entityClass, array $fieldsMapping)
    {
        $this->fieldsMapping = $fieldsMapping;
        $this->entityClass = $entityClass;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function pack(AlmEntityInterface $entity)
    {
        $xml = new \SimpleXMLElement('<Entity></Entity>');
        $xml->addAttribute('Type', $entity->getType());
        $xmlFields = $xml->addChild('Fields');


        foreach (array_flip($this->fieldsMapping) as $entityPropertyMapping => $xmlPropertyMapping) {
            $getter = 'get' . $entityPropertyMapping;
            if (!method_exists($entity, $getter)) {
                throw new AlmEntityExtractorException('Getter \'' . $getter . '\' not found in ' . get_class($entity));
            }

            $xmlField = $xmlFields->addChild('Field');
            $xmlField->addAttribute('Name', $xmlPropertyMapping);
            $xmlField->addChild('Value', $entity->$getter());
        }

        return $xml;
    }

    /**
     * @param \SimpleXMLElement $entityXml
     * @return AlmEntity
     * @throws AlmEntityExtractorException
     */
    public function extract(\SimpleXMLElement $entityXml)
    {
        /** @var AlmEntityInterface $entity */
        $entity = new $this->entityClass();
        $entity->setType($entityXml->attributes()->Type);

        $entityXml = $entityXml->Fields[0];
        foreach ($entityXml->Field as $field) {
            foreach ($this->fieldsMapping as $xmlPropertyMapping => $entityPropertyMapping) {
                if ($field->attributes()->Name == $xmlPropertyMapping) {
                    $setter = 'set' . $entityPropertyMapping;
                    if (!method_exists($entity, $setter)) {
                        throw new AlmEntityExtractorException('Setter \'' . $setter . '\' not found in ' . get_class($entity));
                    }
                    $entity->$setter($field->Value[0]);
                }
            }
        }
        return $entity;
    }

}
