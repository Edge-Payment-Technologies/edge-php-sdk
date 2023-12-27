# Edge PHP API sdk

This is a lightweight PHP API sdk for the Edge payment gateway. It uses Guzzle for making API requests and returns the responses as an array/object by default. The sdk communicates with the `https://api.tryedge.com` endpoint.

## Installation

This project is not yet available via Composer. You will need to clone or download the repository and include it in your project manually.

## Usage

First, you need to set your API key. This can be done using the `setApiKey` method in the `Auth` class.

```php
Edge\Auth::setApiKey('YOUR_API_KEY');
```

After setting the API key, you can use the `Client` class to make requests to the Edge API. The `Client` class has four methods: `create`, `get`, `update`, and `delete`. Each of these methods takes two parameters: the endpoint and an optional body.

Here are some examples:

```php
$create = Edge\Client::create('charges', [] /*body can be placed here*/);

$get = Edge\Client::get('charges', [] /*body can be placed here*/);

$update = Edge\Client::update('charges', [] /*body can be placed here*/);

$delete = Edge\Client::delete('charges', [] /*body can be placed here*/);
```

By default, the response from these methods will be an object. If you want to get the response as an array, you can use the `toArray` method.

```php
$response = Edge\Client::get('charges');
$arrayResponse = $response->toArray();
```

## Error Handling

This sdk uses exceptions for error handling. If an error occurs during a request, an `Exception` will be thrown. You can catch these exceptions to handle errors in your application.

```php
try {
    $response = Edge\Client::get('charges');
} catch (Edge\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

## Contributing

Contributions are welcome. Please submit a pull request or create an issue if you have any improvements or find any bugs.
