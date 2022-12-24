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

    $app->add(function (Request $request, RequestHandlerInterface $handler): Response {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
        $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

        return $response;
    });

    // CATEGORIES
    $app->get('/categories', '\App\Modules\Categories\Categories:getCategories');
    $app->options('/categories', function (Request $request, Response $response): Response {
        return $response;
    });

    // SUBCATEGORIES
    $app->get('/subcategories', '\App\Modules\Subcategories\Subcategories:getSubcategories');
    $app->options('/subcategories', function (Request $request, Response $response): Response {
        return $response;
    });

    // PRODUCTS
    $app->get('/products', '\App\Modules\Products\Products:getProducts');
    $app->options('/products', function (Request $request, Response $response): Response {
        return $response;
    });

    // PRODUCTS
    $app->get('/modals/by-code/{code}', '\App\Modules\Modals\Modals:getModalByCode');
    $app->options('/modals/by-code/{code}', function (Request $request, Response $response): Response {
        return $response;
    });
};
