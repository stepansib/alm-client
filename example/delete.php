<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 21:23
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$entity = $almClient->getManager()->getOneBy(AlmEntityManager::ENTITY_TYPE_DEFECT, [
    'id' => $defectId
]);

$almClient->getManager()->delete($entity);

echo 'Entity has been deleted';
