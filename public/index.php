<?php
$a = microtime(true);
session_start();
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\Routing\RouteCollection;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();

$routes = new RouteCollection();

$routes->add('is_leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => '\App\Http\Controllers\LeapYearController::index',
]));

$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();
$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));

$framework = new Enjima\Core\Application($dispatcher, $controllerResolver, $requestStack, $argumentResolver);


$response = $framework->handle($request);

$response->send();