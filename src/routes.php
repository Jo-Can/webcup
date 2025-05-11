<?php 
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function ($request, $response, $args) use ($twig) {
    return $twig->render($response, 'home.twig', [
        'Twig' => 'Twig rendering OK'
    ]);
});

$app->get('/test', function ($request, $response, $args) use ($twig) {
    return $twig->render($response, 'test.twig');
});

$app->get('/message', function ($request, $response, $args) use ($twig) {
    $html = '<p>HTMX Loaded!</p>';
    $response->getBody()->write($html);
    return $response
        ->withHeader('Content-Type', 'text/html');
});

$app->get('/about', function (Request $request, Response $response) {
    $response->getBody()->write("wakanda");
    return $response;
});

$app->get('/load/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $html = "<p>Dynamic content loaded for column {$id}!</p>";
    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});