<?php

namespace Edge;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class Client
{
    const DEFAULT_BASE_URI = 'https://api.tryedge.io/v2/';

    private static $client;
    private static $baseUri;
    private static $verifySsl = true;

    /**
     * Override the API base URI. Falls back to the EDGE_API_BASE_URI
     * environment variable, then to DEFAULT_BASE_URI.
     *
     * A host-only value (e.g. "https://api.tryedge.test:4001") gets "/v2"
     * appended; the result is always normalised to a trailing slash.
     */
    public static function setBaseUri($uri)
    {
        self::$baseUri = self::normalizeBaseUri($uri);
    }

    public static function getBaseUri()
    {
        if (self::$baseUri === null) {
            $env = getenv('EDGE_API_BASE_URI');
            $uri = ($env !== false && trim($env) !== '') ? $env : self::DEFAULT_BASE_URI;
            self::$baseUri = self::normalizeBaseUri($uri);
        }

        return self::$baseUri;
    }

    /**
     * Disable TLS verification for local development against a
     * self-signed certificate (e.g. api.tryedge.test:4001). Leave enabled
     * in production.
     */
    public static function setVerifySsl($verify)
    {
        self::$verifySsl = (bool) $verify;
        self::$client = null;
    }

    private static function normalizeBaseUri($uri)
    {
        $uri = rtrim(trim((string) $uri), '/');

        if ($uri === '') {
            throw new \InvalidArgumentException('Base URI must not be empty.');
        }

        $path = parse_url($uri, PHP_URL_PATH);
        if ($path === null || $path === false || $path === '' || $path === '/') {
            $uri .= '/v2';
        }

        return $uri . '/';
    }

    /**
     * Build the absolute URL for an endpoint.
     *
     * Absolute http(s) URLs pass through untouched. Leading slashes are
     * dropped so "/customers" resolves under the versioned base URI rather
     * than replacing its path.
     *
     * Transitional: a leading "v2/" is stripped when the base URI already
     * ends in "/v2/", so callers written against the old host-only base
     * URI (which prefixed every endpoint with "v2/") keep working.
     */
    private static function url($endpoint)
    {
        $endpoint = (string) $endpoint;

        if (preg_match('#^https?://#i', $endpoint)) {
            return $endpoint;
        }

        $endpoint = ltrim($endpoint, '/');
        $baseUri = self::getBaseUri();

        if (substr($baseUri, -4) === '/v2/') {
            if ($endpoint === 'v2') {
                $endpoint = '';
            } elseif (strpos($endpoint, 'v2/') === 0) {
                $endpoint = substr($endpoint, 3);
            }
        }

        return $baseUri . $endpoint;
    }

    private static function getClient()
    {
        if (!self::$client) {
            self::$client = new GuzzleClient([
                'verify' => self::$verifySsl,

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
            $response = self::getClient()->post(self::url($endpoint), [
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
            $response = self::getClient()->get(self::url($endpoint), ['query' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }

    public static function update($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->patch(self::url($endpoint), [
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
            $response = self::getClient()->delete(self::url($endpoint), ['json' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }
}
