<?php

namespace Edge;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private static $client;

    private static function getClient()
    {
        if (!self::$client) {
            self::$client = new GuzzleClient([
                'base_uri' => 'https://api.tryedge.io',

                'headers' => [
                    'User-Agent' => 'Edge PHP 2.0.0',
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
        } catch (GuzzleException $e) {
            throw Exception::fromGuzzleException($e);
        }
    }

    public static function get($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->get($endpoint, ['query' => $body]);
            return (new Response($response))->toObject();
        } catch (GuzzleException $e) {
            throw Exception::fromGuzzleException($e);
        }
    }

    public static function update($endpoint, $body = null)
    {
        try {
            // Some action endpoints, such as payment demand confirmation, require
            // a bodyless PATCH. Only set Guzzle's json option when a body is given
            // so an omitted body is not serialized as an empty JSON array.
            $options = [];

            if ($body !== null) {
                $options = [
                    'json' => $body,
                    'headers' => [
                        'Content-Type' => 'application/vnd.api+json'
                    ]
                ];
            }

            $response = self::getClient()->patch($endpoint, $options);
            return (new Response($response))->toObject();
        } catch (GuzzleException $e) {
            throw Exception::fromGuzzleException($e);
        }
    }

    public static function delete($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->delete($endpoint, ['json' => $body]);
            return (new Response($response))->toObject();
        } catch (GuzzleException $e) {
            throw Exception::fromGuzzleException($e);
        }
    }
}
