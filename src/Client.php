<?php

namespace Kylestev\RuneLite\API;

use Exception;
use GuzzleHttp\Client;
use Kylestev\RuneLite\API\Model\GrandExchangeTrade;

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

    public function getGrandExchangeHistoryPage(int $page = 1, int $limit = 500)
    {
        $history = $this->getGrandExchangeHistory($limit, ($page - 1) * $limit);

        return (object) [
            'items' => $history,
            'hasMore' => count($history) === $limit,
        ];
    }

    public function getGrandExchangeHistory(int $limit = 500, ?int $offset = 0)
    {
        $history = json_decode($this->client->get('ge', [
            'query' => compact('limit', 'offset')
        ])->getBody());

        return array_map(function ($x) {
            return new GrandExchangeTrade(
                $x->buy,
                $x->itemId,
                $x->quantity,
                $x->price,
                $x->time->seconds
            );
        }, $history);
    }

    public function getLootTrackerHistory()
    {
        return json_decode($this->client->get('loottracker')->getBody());
    }
}
