<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 10:46
 */

require 'config.php';
require 'menu.php';
//require 'header_xml.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntity;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$entity = $almClient->getManager()->getOneBy(AlmEntity::ENTITY_TYPE_DEFECT, array(
    'id' => $defectId
));

$entity->setParameter('description', 'changed desc 2');
//$entity->setParameter('has-others-linkage', 'N');

//echo $almClient->getManager()->getEntityExtractor()->pack($entity, $almClient->getManager()->getEntityEditableParameters($entity))->asXML();
$almClient->getManager()->save($entity);
