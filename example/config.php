<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 22:19
 */

error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/connection_params.php')) {
    require_once __DIR__ . '/connection_params.php';
} else {
    $connectionParams = array(
        'host' => 'http://your.alm.server.com:8080',
        'domain' => 'your_domain',
        'project' => 'your_project_name',
        'username' => 'your_user_name',
        'password' => 'your_password',
    );
}


?>
