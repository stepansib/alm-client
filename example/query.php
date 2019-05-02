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
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defects = $almClient->getManager()->getBy(AlmEntityManager::ENTITY_TYPE_DEFECT, [
    //'id' => '='.$defectId,
    //'id' => $defectId,
    //'status' => 'Open',
    'owner' => 'syudin',
], [
    'name',
    'id',
    'priority',
    'owner',
    'status',
    'creation-time',
    'detected-by',
    'user-11',
],
    100,
    1
);

foreach ($defects as $defect) {

    // You can access entity field by getParameter method
    echo $defect->getParameter('id') . '<br/>';

    // or by magic method
    // echo $defect->id . '<hr/>';

    dump($defect);
    echo '<hr/>';
}
