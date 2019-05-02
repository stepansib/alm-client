<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 14.02.2016
 * Time: 0:44
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defectFields = $almClient->getManager()->getParametersManager()->getEntityTypeFields(AlmEntityManager::ENTITY_TYPE_DEFECT);

echo dump($defectFields);