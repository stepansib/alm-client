<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 22:13
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntity;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defectsRawResponse = $almClient->getManager()->getBy(AlmEntity::ENTITY_TYPE_DEFECT, array(
    '' => '>=50000',
    'status' => 'Open',
    'owner' => 'syudin',
), AlmEntityManager::HYDRATION_NONE);

echo $defectsRawResponse;