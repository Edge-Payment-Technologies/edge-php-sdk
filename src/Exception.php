<?php

namespace Edge;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Exception extends \Exception
{
    protected $request;
    protected $response;

    public function __construct(
        $message = "",
        $code = 0,
        ?\Exception $previous = null,
        $response = null,
        ?RequestInterface $request = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
    }

    public static function fromGuzzleException(\Exception $exception)
    {
        $request = method_exists($exception, 'getRequest')
            ? $exception->getRequest()
            : null;
        $response = method_exists($exception, 'getResponse')
            ? $exception->getResponse()
            : null;
        $message = $exception->getMessage();

        if ($response instanceof ResponseInterface) {
            $body = $response->getBody();

            if ($body->isSeekable()) {
                $body->rewind();
            }

            $responseMessage = $body->getContents();

            if ($body->isSeekable()) {
                $body->rewind();
            }

            if ($responseMessage !== '') {
                $message = $responseMessage;
            }
        }

        $code = $response instanceof ResponseInterface
            ? $response->getStatusCode()
            : $exception->getCode();

        return new self($message, $code, $exception, $response, $request);
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

    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    public function hasResponse(): bool
    {
        return $this->response !== null;
    }

    public function getStatusCode(): ?int
    {
        return $this->response instanceof ResponseInterface
            ? $this->response->getStatusCode()
            : null;
    }

    public static function getErrorMessage(Exception $e)
    {

        $decodedMessage = json_decode($e->getMessage());

        return $decodedMessage->errors['0']->detail;
    }
}
