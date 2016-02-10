<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 13:15
 */

namespace StepanSib\AlmClient\Exception;

class AlmExceptionGenerator
{

    public static function throwCurlConnectionTimeOut()
    {
        throw new AlmConnectionException('Сonnection timeout');
    }

    public static function throwCookieFileDoesNotExist()
    {
        throw new AlmConnectionException('Curl cookie file does not exist');
    }

    public static function throwCurlNotInitialized()
    {
        throw new AlmConnectionException('Curl not initialized');
    }

}
