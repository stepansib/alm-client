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
use StepanSib\AlmClient\AlmQuery;
use StepanSib\AlmClient\AlmEntity;

$almClient = new AlmClient($connectionParams);
$query = $almClient->getManager()->createQuery();

// Create query and get result URL
$plainQuery = $query->select(AlmQuery::ENTITY_DEFECT)
    ->where('id', '>=5000')
    ->where('status', 'Open')
    ->where('owner', 'syudin')
    ->getQueryUrl();

// Execute query and iterate result
$defects = $query->execute();

/** @var AlmEntity $defect */
foreach ($defects as $defect) {
    echo 'Id: ' . $defect->getId() . '<br/>';
    echo 'Status: ' . $defect->getStatus() . '<br/>';
    echo 'Owner: ' . $defect->getOwner() . '<br/>';
    echo 'Priority: ' . $defect->getPriority() . '<br/>';
    echo 'Name: ' . $defect->getName() . '<br/>';
    echo 'Description: ' . $defect->getDescription() . '<br/>';
    echo 'Comments: ' . $defect->getComments() . '<br/>';
    echo '<hr/>';
}
