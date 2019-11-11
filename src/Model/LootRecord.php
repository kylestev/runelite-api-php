<?php

namespace Kylestev\RuneLite\API\Model;

class LootRecord
{
    /** @var string */
    public $eventId;

    /** @var string */
    public $type;

    /** @var array<GameItem> */
    public $drops;

    /** @var int */
    public $timestamp;

    public function __construct(
        string $eventId,
        string $type,
        array $drops,
        int $timestamp
    )
    {
        $this->eventId = $eventId;
        $this->type = $type;
        $this->drops = $drops;
        $this->timestamp = $timestamp;
    }
}
