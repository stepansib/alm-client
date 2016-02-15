<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 14.02.2016
 * Time: 21:34
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityException;

class AlmEntity
{

    const ENTITY_TYPE_TEST = 'test';
    const ENTITY_TYPE_DEFECT = 'defect';

    protected $parameters;

    protected $parametersChanged;

    protected $type;

    public function __construct($type)
    {
        $this->parameters = array();
        $this->parametersChanged = array();
        $this->setType($type);
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTypePluralized()
    {
        return $this->getType().'s';
    }

    protected function getParameterKey($parameterName)
    {
        $parameters = $this->getParameters();

        if (isset($parameters[$parameterName])) {
            return $parameterName;
        }

        foreach ($parameters as $field => $value) {
            if (mb_strtolower($parameterName, 'utf-8') == mb_strtolower($field, 'utf-8')) {
                return $field;
            }
        }

    }

    public function setParameter($parameterName, $value, $paramChanged = true)
    {
        $parameterOriginalName = $this->getParameterKey($parameterName);

        if (null !== $parameterOriginalName) {
            $parameterName = $parameterOriginalName;
        }

        $this->parameters[$parameterName] = $value;
        if ($paramChanged) {
            $this->parametersChanged[$parameterName] = $value;
        }
        return $this;
    }

    public function getParameter($parameterName)
    {
        $parameterOriginalName = $this->getParameterKey($parameterName);

        if (null === $parameterOriginalName) {
            throw new AlmEntityException('Field name "' . $parameterName . '" not found');
        }

        return $this->parameters[$parameterOriginalName];
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParametersChanged()
    {
        return $this->parametersChanged;
    }

    public function isNew()
    {
        if (isset($this->parameters['id'])) {
            return false;
        }
        return true;
    }

    /**
     * @param $parameterName
     * @return mixed
     */
    public function __get($parameterName)
    {
        return $this->getParameter($parameterName);
    }

}