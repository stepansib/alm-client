<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 13:42
 */

namespace StepanSib\AlmClient\Exception;

class AlmCurlException extends AlmException
{

    public function setMessage($message)
    {
        $this->message = 'Curl error: '.$message;
    }

}
