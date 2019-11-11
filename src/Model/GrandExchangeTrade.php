<?php

namespace Kylestev\RuneLite\API\Model;

class GrandExchangeTrade
{
    /** @var bool */
    public $buy;

    /** @var int */
    public $itemId;

    /** @var int */
    public $quantity;

    /** @var int */
    public $price;

    /** @var int */
    public $timestamp;

    public function __construct(
        bool $buy,
        int $itemId,
        int $quantity,
        int $price,
        int $timestamp
    )
    {
        $this->buy = $buy;
        $this->itemId = $itemId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->timestamp = $timestamp;
    }
}
