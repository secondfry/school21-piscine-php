<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\Session as SessionMiddleware;

require_once __DIR__ . '/engine/engine.php';

$app -> get(
  '/',
  function (Request $request, Response $response, array $args) {
    $html = file_get_contents(__DIR__ . '/../index.html');
    $response -> getBody() -> write($html);
    return $response;
  }
);

$app -> get(
  '/test',
  function (Request $request, Response $response, array $args) {
    $frigate = new AmarrFrigate();
    echo '<pre>';
    var_dump($this->get('session'));
    var_dump($frigate);
    return $response -> withHeader('Content-Type', 'text/plain');
  }
);

$app -> run();
