<?php

namespace Edge;

class Exception extends \Exception
{
    protected $response;

    public function __construct($message = "", $code = 0, \Exception $previous = null, $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public static function fromResponse($response)
    {
        $message = isset($response['message']) ? $response['message'] : 'Unknown error';
        $code = isset($response['code']) ? $response['code'] : 0;

        return new self($message, $code, null, $response);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public static function getErrorMessage(Exception $e)
    {

        $decodedMessage = json_decode($e->getMessage());

        return $decodedMessage->errors['0']->detail;
    }
}
