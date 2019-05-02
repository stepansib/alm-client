<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 15:23
 */

namespace StepanSib\AlmClient\Exception;

use Exception;

/**
 * Class AlmEntityExtractorException
 */
class AlmEntityExtractorException extends Exception
{

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = 'Entity extractor error: ' . $message;
    }

}
