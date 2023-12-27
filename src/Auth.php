<?php

namespace Edge;

class Auth
{
    private static $apiKey;

    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function getApiKey()
    {
        if (!self::$apiKey) {
            throw new Exception('API key not set. Use Edge\Auth::setApiKey() to set it.');
        }

        return self::$apiKey;
    }
}
