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
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defectsRawResponse = $almClient->getManager()->getBy(AlmEntityManager::ENTITY_TYPE_DEFECT, [
    '' => '>=50000',
    'status' => 'Open',
    'owner' => 'syudin',
], [], 250, 1, 'status', AlmEntityManager::HYDRATION_NONE);

echo $defectsRawResponse;
