<pre>
::::::'##::'######:::'#######::'##::: ##:'########::'########:::'######::
:::::: ##:'##... ##:'##.... ##: ###:: ##: ##.... ##: ##.... ##:'##... ##:
:::::: ##: ##:::..:: ##:::: ##: ####: ##: ##:::: ##: ##:::: ##: ##:::..::
:::::: ##:. ######:: ##:::: ##: ## ## ##: ########:: ########:: ##:::::::
'##::: ##::..... ##: ##:::: ##: ##. ####: ##.. ##::: ##.....::: ##:::::::
 ##::: ##:'##::: ##: ##:::: ##: ##:. ###: ##::. ##:: ##:::::::: ##::: ##:
. ######::. ######::. #######:: ##::. ##: ##:::. ##: ##::::::::. ######::
:......::::......::::.......:::..::::..::..:::::..::..::::::::::......:::
:'######::'##::::'##:'########::'##:::::::
'##... ##: ##:::: ##: ##.... ##: ##:::::::
 ##:::..:: ##:::: ##: ##:::: ##: ##:::::::
 ##::::::: ##:::: ##: ########:: ##:::::::
 ##::::::: ##:::: ##: ##.. ##::: ##:::::::
 ##::: ##: ##:::: ##: ##::. ##:: ##:::::::
. ######::. #######:: ##:::. ##: ########:
:......::::.......:::..:::::..::........::
</pre>

# JSON-RPC CURL

A tiny JSON-RPC client which works perfectly with [Simplon/Jr](https://github.com/fightbulc/simplon_jr) - a JSON-RPC server.

## 1. Installation
You can install JSONRPC CURL either via package download from github or via [Composer](http://getcomposer.org) install. I encourage you to do the latter:

```json
{
  "require": {
    "fightbulc/jsonrpc_curl": "0.5.2"
  }
}
```

## 2. How to use?
If you are new to the topic of JSON-RPC I would suggest you to jump over to [Simplon/Jr's documentation](https://github.com/fightbulc/simplon_jr/README.md) which explains the whole topic. Go ahead I will wait here...

Got it? Cool!

The following code examples should help you understand how to use the client. First off, we need ```load composer's autoloader```. Secondly, since we require a JSON-RPC server lets assume that our server resides under the following URL:

```php
// load autoloader
require __DIR__ . '/vendor/autoload.php';       // set correct composer vendor path

// set url for server
$urlServiceGateway = 'http://localhost/jsonrpc/';
```

### 2.1. Request without data
Sending an request without data:

```php
// send request without parameters
$response = (new JsonRpcCurl())
  ->setUrl($urlServiceGateway . '/api/web/')    // server url with gateway path
  ->setId(1)                                    // request ID (important for batch/async)
  ->setMethod('Web.Base.helloWorld')            // requested service
  ->send();                                     // send request

// dump response
var_dump($response);
```

### 2.2. Request with data
Data are passed via an assoc. array:

```php
// set data
$data = [
  'address'  => 'Mr.',
  'lastname' => 'Putterschmidt',
];

// send request without parameters
$response = (new JsonRpcCurl())
  ->setUrl($urlServiceGateway . '/api/web/')    // server url with gateway path
  ->setId(1)                                    // request ID (important for batch/async)
  ->setMethod('Web.Family.guy')                 // requested service
  ->setData($data)                              // holds data
  ->send();                                     // send request

// dump response
var_dump($response);
```

### 2.3. Proxy a request
In development I am using [Charles](http://www.charlesproxy.com/) to see all communication between server and client. The following example shows how to enable a proxy:

```php
// proxy
$proxyIp = '127.0.0.1';
$proxyPort = 88;

// set data
$data = [
  'message'  => 'Can I get a what whaaaat?',
];

// send request without parameters
$response = (new JsonRpcCurl())
  ->setUrl($urlServiceGateway . '/api/web/')    // server url with gateway path
  ->setId(1)                                    // request ID (important for batch/async)
  ->setMethod('Web.Cheerleader.cheer')          // requested service
  ->setData($data)                              // holds data
  ->setProxy($proxyIp, $proxyPort)              // enable proxy
  ->send();                                     // send request

// dump response
var_dump($response);
```

### 3. Conclusion
That's pretty much all there is. Cheers!