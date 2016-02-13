<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 13.02.2016
 * Time: 16:50
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);

$defectRequiredFields = $almClient->getManager()->getEntityRequiredFields(AlmEntityManager::ENTITY_TYPE_DEFECT);

/*
foreach ($defectRequiredFields as $fieldName => $fieldLabel) {
    echo $fieldName . ': ' . $fieldLabel . '<br/>';
}
*/

?>
<pre>
    <?
    echo var_export($defectRequiredFields, true);
    ?>
    </pre>
