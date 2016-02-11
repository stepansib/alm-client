<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 11.02.2016
 * Time: 16:29
 */

namespace StepanSib\AlmClient;

interface AlmEntityExtractorInterface
{

    public function __construct($entityClass, array $fieldsMapping);

    public function fromXml(\SimpleXMLElement $entityXml);

    public function toXml();

}