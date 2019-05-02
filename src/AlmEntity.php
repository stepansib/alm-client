<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 14.02.2016
 * Time: 21:34
 */

namespace StepanSib\AlmClient;

use StepanSib\AlmClient\Exception\AlmEntityException;

/**
 * Class AlmEntity
 */
class AlmEntity
{

    protected $parameters;

    protected $parametersChanged;

    protected $type;

    /**
     * AlmEntity constructor.
     * @param $type
     */
    public function __construct($type)
    {
        $this->parameters = [];
        $this->parametersChanged = [];
        $this->setType($type);
    }

    /**
     * @param $type
     * @return $this
     */
    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getTypePluralized()
    {
        return $this->getType() . 's';
    }

    /**
     * @param $parameterName
     * @return int|string
     */
    /**
     * @param $parameterName
     * @return int|string
     */
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

    /**
     * @param $parameterName
     * @param $value
     * @param bool $paramChanged
     * @return $this
     */
    /**
     * @param $parameterName
     * @param $value
     * @param bool $paramChanged
     * @return $this
     */
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

    /**
     * @param $parameterName
     * @return mixed
     * @throws AlmEntityException
     */
    /**
     * @param $parameterName
     * @return mixed
     * @throws AlmEntityException
     */
    public function getParameter($parameterName)
    {
        $parameterOriginalName = $this->getParameterKey($parameterName);

        if (null === $parameterOriginalName) {
            throw new AlmEntityException('Field name "' . $parameterName . '" not found');
        }

        return $this->parameters[$parameterOriginalName];
    }

    /**
     * @return array
     */
    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    /**
     * @return array
     */
    public function getParametersChanged()
    {
        return $this->parametersChanged;
    }

    /**
     * @return bool
     */
    /**
     * @return bool
     */
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
     * @throws AlmEntityException
     */
    public function __get($parameterName)
    {
        return $this->getParameter($parameterName);
    }

}
