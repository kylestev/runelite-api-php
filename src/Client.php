<?php

namespace Kylestev\RuneLite\API;

use Exception;
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

    public static function getCurrentVersion()
    {
        $client = new Client([
            'base_uri' => 'http://static.runelite.net/api/http-service/',
        ]);

        $body = $client->get('')->getBody();

        $matches = [];
        preg_match('#<title>(?:[^<]+)API (\d+\.\d+\.\d+)</title>#', $body, $matches);

        if (!array_key_exists(1, $matches)) {
            throw new Exception('Unable to get current version');
        }

        return sprintf('runelite-%s', $matches[1]);
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
