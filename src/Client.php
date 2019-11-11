<?php

namespace Kylestev\RuneLite\API;

use Exception;
use GuzzleHttp\Client;
use Kylestev\RuneLite\API\Model\ConfigEntry;
use Kylestev\RuneLite\API\Model\GameItem;
use Kylestev\RuneLite\API\Model\GrandExchangeTrade;
use Kylestev\RuneLite\API\Model\LootRecord;

class RuneLiteAPI
{
    /** @var string */
    private $token;

    /** @var Client */
    private $client;

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

    public function paginate(string $method, int $page = 1, int $limit = 500)
    {
        if (!method_exists($this, $method)) {
            throw new Exception('Bad method on RuneLiteAPI: '.$method);
        }

        $items = $this->{$method}($limit, ($page - 1) * $limit);

        return (object) [
            'items' => $items,
            'hasMore' => count($items) === $limit,
        ];
    }

    public function getConfiguration()
    {
        $response = json_decode($this->client->get('config')->getBody());

        return array_map(function ($entry) {
            return new ConfigEntry($entry->key, $entry->value);
        }, $response->config);
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

    public function getLootTrackerHistory(int $count = 500, ?int $start = 0)
    {
        $loots = json_decode($this->client->get('loottracker', [
            'query' => compact('count', 'start')
        ])->getBody());

        return array_map(function ($x) {
            $drops = array_map(function ($drop) {
                return new GameItem($drop->id, $drop->qty);
            }, $x->drops);

            return new LootRecord(
                $x->eventId,
                $x->type,
                $drops,
                $x->time->seconds
            );
        }, $loots);
    }
}
