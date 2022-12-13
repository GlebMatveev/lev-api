<?php

// use Psr\Http\Message\ResponseInterface;
// use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
// use Tuupola\Middleware\CorsMiddleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

return function (App $app) {

    // $app->get('/hello', function (ServerRequestInterface $request, ResponseInterface $response) {
    //     $response->getBody()->write('Hello World');
    //     return $response;
    // });
    // $app->add(CorsMiddleware::class);

    // CORS
    // $app->add(new Tuupola\Middleware\CorsMiddleware);

    $app->add(function (Request $request, RequestHandlerInterface $handler): Response {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
        $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

        // Optional: Allow Ajax CORS requests with Authorization header
        // $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    });


    // EXAMPLE
    $app->get('/example', '\App\Modules\Example\Example:getExample');
    $app->post('/example', '\App\Modules\Example\Example:postExample');
    $app->options('/example', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->put('/example/{id}', '\App\Modules\Example\Example:putExample');
    $app->delete('/example/{id}', '\App\Modules\Example\Example:deleteExample');
    $app->options('/example/{id}', function (Request $request, Response $response): Response {
        return $response;
    });
};
