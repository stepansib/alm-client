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
), AlmEntityManager::HYDRATION_NONE);

echo $defectsRawResponse;
