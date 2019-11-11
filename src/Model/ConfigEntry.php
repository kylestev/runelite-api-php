<?php

namespace Kylestev\RuneLite\API\Model;

class ConfigEntry
{
    /** @var string */
    public $key;

    /** @var string */
    public $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
