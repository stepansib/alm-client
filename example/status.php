<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 11:35
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;

$almClient = new AlmClient($connectionParams);
echo $almClient->getAuthenticator()->isAuthenticated() ? "Authenticated" : "Not authenticated";
