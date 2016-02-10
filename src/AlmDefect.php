<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.02.2016
 * Time: 15:42
 */

namespace StepanSib\AlmClient;

class AlmDefect
{

    const STATUS_NEW = "New";
    const STATUS_OPEN = "Open";
    const STATUS_TESTED = "Tested";
    const STATUS_FIXED = "Fixed";
    const STATUS_ACCEPTED = "Accepted";

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
    protected $status;

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

}
