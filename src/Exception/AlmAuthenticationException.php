<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 15:11
 */

namespace StepanSib\AlmClient\Exception;

class AlmAuthenticationException extends AlmException
{

    public function setMessage($message)
    {
        $this->message = 'Authentication error: '.$message;
    }

}