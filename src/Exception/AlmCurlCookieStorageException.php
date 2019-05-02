<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 15:31
 */

namespace StepanSib\AlmClient\Exception;

use Exception;

/**
 * Class AlmCurlCookieStorageException
 */
class AlmCurlCookieStorageException extends Exception
{

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = 'Curl cookie storage error: ' . $message;
    }

}
