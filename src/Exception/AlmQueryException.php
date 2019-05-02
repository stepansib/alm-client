<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 11.02.2016
 * Time: 21:46
 */

namespace StepanSib\AlmClient\Exception;

use Exception;

/**
 * Class AlmQueryException
 */
class AlmQueryException extends Exception
{

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = 'Query error: ' . $message;
    }

}
