<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 15:19
 */

namespace StepanSib\AlmClient\Exception;

use Exception;

/**
 * Class AlmEntityParametersManagerException
 */
class AlmEntityParametersManagerException extends Exception
{

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = 'Entity parameters error: ' . $message;
    }

}
