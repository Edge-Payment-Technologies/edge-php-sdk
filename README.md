# Edge PHP SDK

This is a lightweight PHP SDK for the Edge payment gateway. It uses Guzzle for making API requests and returns the responses as an array/object by default. The SDK communicates with the `https://api.tryedge.io/v2/` endpoint.

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

$delete = Edge\Client::delete('payment_demands', [] /*body can be placed here*/);
```

Endpoints are resolved against the base URI, so `payment_demands`, `/payment_demands` and
(for callers written against the old host-only base URI) `v2/payment_demands` all reach
`https://api.tryedge.io/v2/payment_demands`. Absolute `http(s)://` URLs are used as-is.

### Base URI

The base URI defaults to `https://api.tryedge.io/v2/` and can be overridden with the
`EDGE_API_BASE_URI` environment variable or at runtime. A host-only value gets `/v2` appended.

```php
Edge\Client::setBaseUri('https://api.tryedge.test:4001'); // => https://api.tryedge.test:4001/v2/
Edge\Client::setVerifySsl(false); // local dev with a self-signed certificate only
```

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
}
```

## Helpers

The SDK also includes a `Helpers` class with useful methods. For example, you can use the `convertAlpha2ToAlpha3` method to convert a country code from ISO 3166-1 alpha-2 to ISO 3166-1 alpha-3.

```php
$alpha3 = Edge\Helpers::convertAlpha2ToAlpha3('US');
```

## Contributing

Contributions are welcome. Please submit a pull request or create an issue if you have any improvements or find any bugs.
