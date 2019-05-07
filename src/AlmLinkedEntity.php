<?php

namespace StepanSib\AlmClient;

/**
 * Class AlmLinkedEntity
 */
class AlmLinkedEntity
{

    /** @var int */
    protected $id;

    /** @var string */
    protected $type;

    /**
     * AlmLinkedEntity constructor.
     * @param int $id
     * @param string $type
     */
    public function __construct(int $id, string $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}