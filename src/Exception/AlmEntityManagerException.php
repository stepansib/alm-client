<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 22:27
 */

namespace StepanSib\AlmClient\Exception;

class AlmEntityManagerException extends \Exception
{

    public function setMessage($message)
    {
        $this->message = 'Entity manager error: '.$message;
    }

}
