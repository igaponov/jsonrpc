# JSON-RPC 2.0 Specification

[JSON-RPC](http://www.jsonrpc.org/specification) is a stateless, light-weight remote procedure call (RPC) protocol.

## Request object

A rpc call is represented by sending a [Request object](http://www.jsonrpc.org/specification#request_object) to a Server.

```php
// client
$request = new \JsonRpc\Spec\Request('subtract', [42, 23], 1);

// server
$result = call_user_func_array($request->getMethod(), $request->getParams());
```

### Notification

A [Notification](http://www.jsonrpc.org/specification#notification) is a Request object without an "id" member.

```php
$request1 = new \JsonRpc\Spec\Request('update', [1,2,3,4,5]);
$request2 = new \JsonRpc\Spec\Request('foobar');
```

## Response object

When a rpc call is made, the Server MUST reply with a [Response](http://www.jsonrpc.org/specification#response_object), except for in the case of Notifications.

```php
$response = new \JsonRpc\Spec\Response($result, null, $request->getId());
```

### Error object

When a rpc call encounters an error, the Response Object MUST contain the [Error](http://www.jsonrpc.org/specification#error_object) member with a value that is a \JsonRpc\Spec\Error

```php
$error = new \JsonRpc\Spec\Error(500, 'Internal error', $exception->getTraceAsString());
$response = new \JsonRpc\Spec\Response(null, $error, $request->getId());
```

The error codes from and including -32768 to -32000 are reserved for pre-defined errors.

```php
use \JsonRpc\Spec\Exception\ParseErrorException;

try {
    // parse request
    throw new ParseErrorException();
} catch(ParseErrorException $e) {
    $error = new \JsonRpc\Spec\Error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
    $response = new \JsonRpc\Spec\Response(null, $error, $request->getId());    
}
```

## Batch

To send several Request objects at the same time, the Client MAY send an [Array](http://www.jsonrpc.org/specification#batch) filled with Request objects.

```php
foreach($batch as $response) {
    $result = $response->getResult();
}
```

### BatchRequest

```php
$requests = [
    new \JsonRpc\Spec\Request('update', [1,2,3,4,5]),
    new \JsonRpc\Spec\Request('foobar'),
    // ...
];
$batch = new \JsonRpc\Spec\BatchRequest($requests);
```

### BatchResponse

```php
$responses = [
    new \JsonRpc\Spec\Response(7, 1),
    new \JsonRpc\Spec\Response(null, $error, 2),
    // ...
];
$batch = new \JsonRpc\Spec\BatchResponse($responses);
```

## ObjectManager

Object manager is a wrapper for dealing with requests/responses

```php
$manager = new \JsonRpc\ObjectManager($transport);
$id = $manager->addRequest('subtract', [42, 23]);
$manager->addNotification('foobar');
$manager->commit();
if (!$manager->hasError($id)) {
    $result = $manager->getResult($id); // 19
} else {
    throw new Exception($manager->getError($id), $manager->getErrorCode($id));
}
```

### Transport

The object manager uses a transport object to communicate with a transport layer (http, rabbitmq, etc). 
The transport object must implements the [\JsonRpc\TransportInterface](src/JsonRpc/TransportInterface.php).

```php
class CurlTransport implements \JsonRpc\TransportInterface 
{
    public function send(UnitInterface $data) 
    {
        $ch = curl_init();        
        
        $data = json_encode($data);        
        
        curl_setopt($ch, CURLOPT_URL, 'http://localhost/rpc.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        curl_exec($ch);  
    }
}
``` 

## Testing

``` bash
$ phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.