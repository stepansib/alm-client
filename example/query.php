<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 22:23
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntity;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defects = $almClient->getManager()->getBy(AlmEntityManager::ENTITY_TYPE_DEFECT, array(
    'id' => '>=5000',
    'status' => 'Open',
    'owner' => 'syudin',
));

/** @var AlmEntity $defect */
foreach ($defects as $defect) {
    echo 'Type: ' . $defect->getType() . '<br/>';
    echo 'Id: ' . $defect->getId() . '<br/>';
    echo 'Status: ' . $defect->getStatus() . '<br/>';
    echo 'Owner: ' . $defect->getOwner() . '<br/>';
    echo 'Priority: ' . $defect->getPriority() . '<br/>';
    echo 'Name: ' . $defect->getName() . '<br/>';
    echo 'Description: ' . $defect->getDescription() . '<br/>';
    echo 'Comments: ' . $defect->getComments() . '<br/>';
    echo '<hr/>';
}
