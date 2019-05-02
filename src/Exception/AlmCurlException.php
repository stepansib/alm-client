<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 13:42
 */

namespace StepanSib\AlmClient\Exception;

use Exception;

/**
 * Class AlmCurlException
 */
class AlmCurlException extends Exception
{

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = 'Curl error: ' . $message;
    }

}
