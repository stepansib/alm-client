<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 12:18
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

echo $almClient->getManager()->getEntityLockStatus($entity);