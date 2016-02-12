<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 12.02.2016
 * Time: 22:45
 */

namespace StepanSib\AlmClient;

interface AlmEntityInterface
{

    public function setType($type);

    public function getType();

    public function setId($id);

    public function getId();

}