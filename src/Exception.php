<?php

namespace Edge;

class Exception extends \Exception
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromResponse($response)
    {
        $message = isset($response['message']) ? $response['message'] : 'Unknown error';
        $code = isset($response['code']) ? $response['code'] : 0;

        return new self($message, $code);
    }
}
