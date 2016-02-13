<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 15:42
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\AlmEntityInterface;

class AlmEntity implements AlmEntityInterface
{

    /** @var  string */
    protected $type;

    /** @var integer */
    protected $id;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $description;

    /** @var  string */
    protected $comments;

    /** @var  string */
    protected $owner;

    /** @var  string */
    protected $detectedBy;

    /** @var  string */
    protected $status;

    /** @var  string */
    protected $priority;

    public function isNew()
    {
        if (null === $this->getId()) {
            return true;
        }
        return false;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->owner;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setDetectedBy($detectedBy)
    {
        $this->detectedBy = $detectedBy;
    }

    public function getDetectedBy()
    {
        return $this->detectedBy;
    }

}
