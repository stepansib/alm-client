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
    'id' => '='.$defectId,
    //'status' => 'Open',
    //'owner' => 'syudin',
));

foreach ($defects as $defect) {

    // You can access entity field by getParameter method
    echo $defect->getParameter('id') . '<br/>';

    // or by magic method
    echo $defect->id . '<hr/>';

    // or simply iterate through all of the fields
    foreach ($defect->getParameters() as $field => $value) {
        echo $field . ': ' . $value . '<br/>';
    }
    echo '<hr/>';
}
