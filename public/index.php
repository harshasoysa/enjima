<?php
$a = microtime(true);
session_start();
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();

$routes = new Routing\RouteCollection();

$routes->add('hello', new Routing\Route('/hello/{name}', ['name' => 'World']));
$routes->add('bye', new Routing\Route('/bye'));
$routes->add('/', new Routing\Route('/'));

$a = microtime(true);

$context = new Routing\RequestContext();
$context->fromRequest($request);

// $compiledRoutes = (new CompiledUrlMatcherDumper($routes))->getCompiledRoutes();

// $_SESSION["compiledRoutes"] = $compiledRoutes;

// $compiledRoutes = $_SESSION["compiledRoutes"];
// dd($compiledRoutes);

// $matcher = new CompiledUrlMatcher($compiledRoutes, $context);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try {

    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
    $name = $request->get('name');
    $b = microtime(true);
    $response = new Response($name . ($b - $a) );
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
    exit("shit");
}
// var_dump($_route);



$response->send();