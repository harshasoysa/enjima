<?php
$a = microtime(true);
session_start();
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();

$routes = new Routing\RouteCollection();

$routes->add('is_leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => '\App\Http\Controllers\LeapYearController::index',
]));

$a = microtime(true);

$context = new Routing\RequestContext();
$context->fromRequest($request);

// $compiledRoutes = (new CompiledUrlMatcherDumper($routes))->getCompiledRoutes();

// $_SESSION["compiledRoutes"] = $compiledRoutes;

// $compiledRoutes = $_SESSION["compiledRoutes"];
// dd($compiledRoutes);

// $matcher = new CompiledUrlMatcher($compiledRoutes, $context);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();
$dispatcher = new EventDispatcher();

$framework = new Enjima\Core\Application($dispatcher, $matcher, $controllerResolver, $argumentResolver);

$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__.'/../cache')
);

$response = $framework->handle($request);

$response->send();