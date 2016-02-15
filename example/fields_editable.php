<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 13:21
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntity;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defectEditableFields = $almClient->getManager()->getEntityEditableParameters(AlmEntity::ENTITY_TYPE_DEFECT);

?>
    <pre>
    <?
    echo var_export($defectEditableFields, true);
    ?>
    </pre>
<?
