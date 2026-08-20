# Edge PHP SDK

This is a lightweight PHP SDK for the Edge payment gateway. It uses Guzzle for making API requests and returns the responses as an array/object by default. The sdk communicates with the `https://api.tryedge.io` endpoint.

## Installation

To install the SDK, add the following to your composer.json file:

```json
"require": {
    "edge-payment-technologies/edge-php-sdk": "^2.0"
}
```

Then run `composer install` to add the SDK to your project.

## Usage

First, you need to set your API key. This can be done using the `setApiKey` method in the `Auth` class.

```php
Edge\Auth::setApiKey('YOUR_API_KEY');
```

After setting the API key, you can use the `Client` class to make requests to the Edge API. The `Client` class has four methods: `create`, `get`, `update`, and `delete`. Each of these methods takes two parameters: the endpoint and an optional body.

Here are some examples:

```php
$create = Edge\Client::create('payment_demands', [] /*body can be placed here*/);

$get = Edge\Client::get('payment_demands', [] /*body can be placed here*/);

$update = Edge\Client::update('payment_demands', [] /*body can be placed here*/);

// Omit the body for action endpoints that accept a bodyless PATCH request.
$confirm = Edge\Client::update('v2/payment_demands/PAYMENT_DEMAND_ID/confirm');

$delete = Edge\Client::delete('payment_demands', [] /*body can be placed here*/);
```

Passing an update body sends it as JSON with the JSON:API content type. Omitting
the body, or passing `null`, sends the PATCH request without a request body.

By default, the response from these methods will be an object. If you want to get the response as an array, you can use the `toArray` method.

```php
$response = Edge\Client::get('payment_demands');
$arrayResponse = $response->toArray();
```

## Error Handling

This sdk uses exceptions for error handling. If an error occurs during a request, an `Exception` will be thrown. You can catch these exceptions to handle errors in your application.

```php
try {
    $response = Edge\Client::get('payment_demands');
} catch (Edge\Exception $e) {
    echo 'Error: ' . $e->getMessage();

    if ($e->getStatusCode() !== null) {
        echo 'Status: ' . $e->getStatusCode();
    }

    if ($e->getRequest()) {
        echo 'Request: ' . $e->getRequest()->getMethod()
            . ' ' . $e->getRequest()->getUri()->getPath();
    }
}
```

HTTP failures retain the PSR-7 request and response objects. Network failures retain
the request, but `getResponse()` and `getStatusCode()` return `null` because no HTTP
response was received. The original Guzzle exception is available through
`getPrevious()`.

Do not log or expose request headers or bodies. They can contain authorization
credentials or sensitive payment data. Prefer the request method and URI path when
recording diagnostic context.

## Helpers

The SDK also includes a `Helpers` class with useful methods. For example, you can use the `convertAlpha2ToAlpha3` method to convert a country code from ISO 3166-1 alpha-2 to ISO 3166-1 alpha-3.

```php
$alpha3 = Edge\Helpers::convertAlpha2ToAlpha3('US');
```

## Contributing

Contributions are welcome. Please submit a pull request or create an issue if you have any improvements or find any bugs.
