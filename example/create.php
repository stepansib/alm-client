<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 23:03
 */

require 'config.php';
//require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmQuery;
use StepanSib\AlmClient\AlmEntity;

$almClient = new AlmClient($connectionParams);

$entity = new AlmEntity();
$entity->setType(AlmQuery::ENTITY_DEFECT);
$entity->setOwner('syudin');
$entity->setName(date('d/m/Y H:i:s') . ': test defect by REST API client');
$entity->setDescription('A very long test description');
$entity->setComments('');
$entity->setPriority('5-Urgent');
$entity->setStatus('New');

header("Content-type: text/xml");
$almClient->getManager()->create($entity);