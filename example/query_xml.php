<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 13:44
 */

require 'config.php';
require 'header_xml.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntity;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defectsRawResponse = $almClient->getManager()->getBy(AlmEntityManager::ENTITY_TYPE_DEFECT, array(
    'id' => '='.$defectId,
), array(
    'name',
    'id',
    'priority',
    'owner',
    'status',
    'creation-time',
    'detected-by',
    'user-11',
),2501,1,'{id[DESC]}', AlmEntityManager::HYDRATION_NONE);

echo $defectsRawResponse;
