<?php
session_start();

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

require __DIR__ . '/vendor/autoload.php';
require 'settings.php';

// Settings UiTID
$token = $_SESSION['oauth_token'];
$token_secret = $_SESSION['oauth_token_secret'];
$verifier = $_GET['oauth_verifier'];

$stack = HandlerStack::create();
$middleware = new Oauth1([
    'consumer_key'    => $key,
    'consumer_secret' => $secret,
    'token'           => $token,
    'token_secret'    => $token_secret,
    'verifier'        => $verifier
]);

$stack->push($middleware);

$client = new Client([
    'base_uri' => $base_url,
    'handler' => $stack,
    'auth' => 'oauth'
]);

try {
    $request = $client->post('accessToken');
    $request = (string)$request->getBody();
    parse_str($request, $tokens);

    print_r('<pre>OAuth Token: ' . $tokens['oauth_token'] . '</pre>');
    print_r('<pre>OAuth Token Secret: ' . $tokens['oauth_token_secret'] . '</pre>');
    print_r('<pre>UserId: ' . $tokens['userId'] . '</pre>');

} catch (RequestException $e) {
    echo Psr7\str($e->getRequest());
    if ($e->hasResponse()) {
        echo Psr7\str($e->getResponse());
    }
}