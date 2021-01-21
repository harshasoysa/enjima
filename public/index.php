<?php

require_once __DIR__.'/../vendor/autoload.php';

$a = microtime();

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$b = microtime();
$request = Request::createFromGlobals();
$c = microtime();

$name = $request->get('name', 'World');
$d = microtime();

$name = htmlspecialchars($_GET['name']);

$e = microtime();

$response = new Response($a . '<br>' . $b . '<br>' . $c . '<br>' . $d . '<br>' . $e . '<br>' . $name);

$response->send();