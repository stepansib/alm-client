<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 22:39
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;

$almClient = new AlmClient($connectionParams);
echo $almClient->getAuthenticator()->login() ? "Authenticated" : "Not authenticated";
