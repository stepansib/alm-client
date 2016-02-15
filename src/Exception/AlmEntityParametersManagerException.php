<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 15:19
 */

namespace StepanSib\AlmClient\Exception;

class AlmEntityParametersManagerException extends \Exception
{

    public function setMessage($message)
    {
        $this->message = 'Entity parameters error: ' . $message;
    }

}
