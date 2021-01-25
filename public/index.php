<?php
$a = microtime(true);
session_start();
require_once __DIR__.'/../vendor/autoload.php';

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



try {
    $request->attributes->add($matcher->match($request->getPathInfo()));

    $controller = $controllerResolver->getController($request);

    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);

} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
    exit("shit");
}
// var_dump($_route);



$response->send();