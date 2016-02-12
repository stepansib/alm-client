<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 22:37
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;

$almClient = new AlmClient($connectionParams);
$almClient->getAuthenticator()->logout();

echo 'Logged out';
