<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 15:11
 */

namespace StepanSib\AlmClient\Exception;

use Exception;

/**
 * Class AlmAuthenticationException
 */
class AlmAuthenticationException extends Exception
{

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = 'Authentication error: ' . $message;
    }

}
