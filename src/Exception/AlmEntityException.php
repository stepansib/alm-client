<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 14.02.2016
 * Time: 22:16
 */

namespace StepanSib\AlmClient\Exception;

class AlmEntityException extends \Exception
{

    public function setMessage($message)
    {
        $this->message = 'ALM entity error: ' . $message;
    }

}
