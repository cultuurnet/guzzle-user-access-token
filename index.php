<?php
session_start();

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

require __DIR__ . '/vendor/autoload.php';
require 'settings.php';

$stack = HandlerStack::create();
$middleware = new Oauth1([
    'consumer_key'    => $key,
    'consumer_secret' => $secret,
    'token'           => '',
    'token_secret'    => '',
    'callback'        => 'http://oauth.dev/access-token.php'
]);

$stack->push($middleware);

$client = new Client([
    'base_uri' => $base_url,
    'handler' => $stack,
    'auth' => 'oauth'
]);

try {
    $request = $client->post('requestToken');
    $request = (string)$request->getBody();
    parse_str($request, $req_tokens);

    $req_token = $req_tokens['oauth_token'];
    $req_token_secret = $req_tokens['oauth_token_secret'];

    $_SESSION['oauth_token'] =  $req_token;
    $_SESSION['oauth_token_secret'] =  $req_token_secret;

    $callback_url = 'http://oauth.dev/authorize.php?token=' . $req_token . '&token_secret=' . $req_token_secret;

    print_r('<pre>' . $req_token . '</pre>');
    print_r('<pre>' . $req_token_secret . '</pre>');
    print_r('<pre><a href="' . $callback_url . '">' . $callback_url . '</a></pre><hr>');


} catch (RequestException $e) {
    echo Psr7\str($e->getRequest());
    if ($e->hasResponse()) {
        echo Psr7\str($e->getResponse());
    }
}
