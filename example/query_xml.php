<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 13:44
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
header("Content-type: text/xml");
echo $query->executeRaw();
