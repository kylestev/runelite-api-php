<?php

namespace Kylestev\RuneLite\API\Model;

class GameItem
{
    /** @var int */
    public $id;

    /** @var int */
    public $quantity;

    public function __construct(int $id, int $quantity)
    {
        $this->id = $id;
        $this->quantity = $quantity;
    }
}
