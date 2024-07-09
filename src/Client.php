<?php

namespace Edge;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class Client
{
    private static $client;

    private static function getClient()
    {
        if (!self::$client) {
            self::$client = new GuzzleClient([
                'base_uri' => 'https://api.tryedge.com',

                'headers' => [
                    'User-Agent' => 'Edge PHP 1.0.0',
                    'Authorization' => 'Bearer ' . Auth::getApiKey(),
                    'Accept' => 'application/vnd.api+json',

                ],
            ]);
        }

        return self::$client;
    }

    public static function create($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->post($endpoint, [
                'json' => $body,

                'headers' => [
                    'Content-Type' => 'application/vnd.api+json'
                ]

            ]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }

    public static function get($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->get($endpoint, ['query' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }

    public static function update($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->put($endpoint, [
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/vnd.api+json'
                ]
            ]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }

    public static function delete($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->delete($endpoint, ['json' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }
}
