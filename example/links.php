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

$almClient = new AlmClient($connectionParams);
$defectLinkManager = $almClient->getManager()->getDefectLinkManager();

dump($defectLinkManager->getDefectLinks(2));
dump($defectLinkManager->getDefectLinks(20));
//dump($defectLinkManager->getDefectLinks(3661));
//dump($defectLinkManager->getDefectLinks(3664));

