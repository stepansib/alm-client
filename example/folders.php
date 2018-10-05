<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 14.02.2016
 * Time: 1:19
 */

require 'config.php';
require 'header_xml.php';

use StepanSib\AlmClient\AlmClient;

$almClient = new AlmClient($connectionParams);
echo $almClient->getManager()->getFoldersManager()->getFolders('test-folders')->asXML();


$almClient = new AlmClient($connectionParams);
echo $almClient->getManager()->getFoldersManager()->getFolders('test-set-folders')->asXML();
