<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 22:23
 */

require 'config.php';

//header("Content-Type:text/xml");

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmQuery;

$almClient = new AlmClient($connectionParams);
$query = $almClient->getManager()->createQuery();

$plainQuery = $query->select(AlmQuery::ENTITY_DEFECT)
    ->where('id', '>=5000')
    ->where('status', 'Open')
    ->where('owner', 'syudin')
    //->where('priority', '5*')
    ->getQueryUrl();

if ($result = $query->execute()) {
    var_dump($result);
//if ($result = $query->execute(AlmQuery::RETURN_STRING)) {
    //print_r(simplexml_load_string($result));
    //print_r($result);

    //echo $result;
}
