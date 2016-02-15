<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 23:03
 */

require 'config.php';
require 'menu.php';
//require 'header_xml.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntity;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$entity = new AlmEntity(AlmEntity::ENTITY_TYPE_DEFECT);
$entity->setParameter('name', 'REST API test defect ' . date('d/m/Y H:i:s'))
    ->setParameter('detected-by', $connectionParams['username'])
    ->setParameter('owner', 'akorchagin')
    ->setParameter('creation-time', date('Y-m-d'))
    ->setParameter('user-05', 'Не определен')
    ->setParameter('description', 'REST API test defect description');

echo 'Entity created successfully<br/>';

$entity = $almClient->getManager()->save($entity);
echo 'Save entity id: ' . $entity->id . '<br/>';
