<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 13:44
 */

require 'config.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defectsRawResponse = $almClient->getManager()->getBy(AlmEntityManager::ENTITY_TYPE_DEFECT, array(
    'id' => '>=5000',
    'status' => 'Open',
    'owner' => 'syudin',
), AlmEntityManager::HYDRATION_NONE);

header("Content-type: text/xml");
echo $defectsRawResponse;
