<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 15:23
 */

namespace StepanSib\AlmClient\Exception;

class AlmEntityExtractorException extends AlmException
{

    public function setMessage($message)
    {
        $this->message = 'Entity extractor error: '.$message;
    }

}
