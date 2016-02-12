<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 22:23
 */

require 'config.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmQuery;

$almClient = new AlmClient($connectionParams);
$query = $almClient->getManager()->createQuery();

// Create query and get result URL
$plainQuery = $query->select(AlmQuery::ENTITY_DEFECT)
    ->where('id', '>=5000')
    ->where('status', 'Open')
    ->where('owner', 'syudin')
    ->getQueryUrl();

// Execute query and iterate result
if ($result = $query->execute()) {
    var_dump($result);

    //if ($result = $query->execute(AlmQuery::RETURN_STRING)) {
    //print_r(simplexml_load_string($result));
    //print_r($result);
    //echo $result;
}
