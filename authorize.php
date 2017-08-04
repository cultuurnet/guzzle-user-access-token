<?php
session_start();

require __DIR__ . '/vendor/autoload.php';
require 'settings.php';

if (isset($_GET['token'])) {

    $auth_url = $base_url . 'auth/authorize?oauth_token=' . $_GET['token'] . '&oauth_token_secret=' . $_GET['token_secret'];

    print_r('<pre><p>Authorize URL:</p><a href="' . $auth_url . '">' . $auth_url . '</a></pre><hr>');
}