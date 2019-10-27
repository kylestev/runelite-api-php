<?php

namespace Kylestev\RuneLite\API;

use GuzzleHttp\Client;

class RuneLiteAPI
{
    public function __construct(string $token, string $apiVersion)
    {
        $this->token = $token;
        $this->client = new Client([
            'base_uri' => 'https://api.runelite.net/'.$apiVersion.'/',
            'headers' => [
                'User-Agent' => '@kylestev/runelite-api-php',
                'RUNELITE-AUTH' => $this->token,
            ],
        ]);
    }

    public function getGrandExchangeHistory()
    {
        return json_decode($this->client->get('ge')->getBody());
    }

    public function getLootTrackerHistory()
    {
        return json_decode($this->client->get('loottracker')->getBody());
    }
}
