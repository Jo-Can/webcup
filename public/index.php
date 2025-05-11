<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Exception\HttpNotFoundException;


require dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();
$twig = Twig::create(__DIR__ . '/../views', ['cache' => false]);

$app->addRoutingMiddleware();
$app->add(TwigMiddleware::create($app, $twig));

require __DIR__ . '/../src/routes.php';

$customErrorHandler = function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app, $twig) {
    $response = $app->getResponseFactory()->createResponse();

    // Handle 404 separately
    if ($exception instanceof HttpNotFoundException) {
        return $twig->render($response->withStatus(404), 'errors/404.twig', [
            'message' => 'Page not found'
        ]);
    }

    // Fallback for other exceptions
    $response->getBody()->write('An unexpected error occurred.');
    return $response->withStatus(500);
};


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();