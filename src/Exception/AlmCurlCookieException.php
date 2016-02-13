<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 15:31
 */

namespace StepanSib\AlmClient\Exception;

class AlmCurlCookieException extends \Exception
{

    public function setMessage($message)
    {
        $this->message = 'Curl cookie storage error: ' . $message;
    }

}
