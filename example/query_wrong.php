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
use StepanSib\AlmClient\AlmQuery;
use StepanSib\AlmClient\AlmEntity;

$almClient = new AlmClient($connectionParams);
$query = $almClient->getManager()->createQuery();

// Execute wrong query
$defects = $query->select(AlmQuery::ENTITY_DEFECT)
    ->where('id', '=5000000')
    ->where('status', 'Open')
    ->where('owner', 'syudin')
    ->executeRaw();

var_dump($defects);