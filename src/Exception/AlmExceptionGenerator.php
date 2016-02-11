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

    public static function throwCurlError($err)
    {
        throw new AlmException('Curl error: ' . $err);
    }

    public static function throwCookieFileDoesNotExist()
    {
        throw new AlmException('Curl cookie file does not exist');
    }

    public static function throwCurlNotInitialized()
    {
        throw new AlmException('Curl not initialized');
    }

    public static function throwEntityTypeNotSpecified()
    {
        throw new AlmException('Entity not specified');
    }

}