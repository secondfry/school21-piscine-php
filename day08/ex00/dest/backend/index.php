<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\Session as SessionMiddleware;

require_once __DIR__ . '/engine/engine.php';

$app = AppFactory ::create();

$app -> add(
  new SessionMiddleware(
    [
      'name'        => 'dummy_session',
      'autorefresh' => true,
      'lifetime'    => '1 hour'
    ]
  )
);

$customErrorHandler = function (
  ServerRequestInterface $request,
  Throwable $exception,
  bool $displayErrorDetails,
  bool $logErrors,
  bool $logErrorDetails
) use ($app) {
  $response = $app -> getResponseFactory() -> createResponse();

  $filepath = __DIR__ . '/../' . $request -> getUri() -> getPath();
  if (file_exists($filepath) && is_file($filepath)) {
    $arr      = explode('.', $filepath);
    $filetype = $arr[count($arr) - 1];
    switch ($filetype) {
      case 'css':
        $response = $response -> withHeader('Content-Type', 'text/css');
        break;
    }
    $response -> getBody() -> write(file_get_contents($filepath));
    return $response;
  }

  $payload = ['error' => $exception -> getMessage()];

  $response -> getBody() -> write(
    json_encode($payload, JSON_UNESCAPED_UNICODE)
  );

  return $response;
};

// Add Error Middleware
$errorMiddleware = $app -> addErrorMiddleware(true, true, true);
$errorMiddleware -> setDefaultErrorHandler($customErrorHandler);

$app -> get(
  '/',
  function (Request $request, Response $response, array $args) {
    $html = file_get_contents(__DIR__ . '/../index-view.html');
    $response -> getBody() -> write($html);
    return $response;
  }
);

$app -> get(
  '/api/create',
  function (Request $request, Response $response, array $args) {
    $session = new \SlimSession\Helper();

    $game = new Game();
    $session -> set('game', $game);

    return $response -> withHeader('Location', '/play');
  }
);

$app -> get(
  '/play',
  function (Request $request, Response $response, array $args) {
    $session = new \SlimSession\Helper();

    /** @var Game $game */
    $game = $session -> get('game');

    $game -> play();

    $ret = $game -> render();
    $response -> getBody() -> write($ret);
    return $response;
  }
);

$app -> get(
  '/select/{shipID:[0-9]+}',
  function (Request $request, Response $response, array $args) {
    $session = new \SlimSession\Helper();

    /** @var Game $game */
    $game = $session -> get('game');

    $game -> selectShip(intval($args['shipID'], 10));

    return $response -> withHeader('Location', '/play');
  }
);

$app -> get(
  '/move/{x:[0-9]+}/{y:[0-9]+}',
  function (Request $request, Response $response, array $args) {
    $session = new \SlimSession\Helper();

    /** @var Game $game */
    $game = $session -> get('game');

    $game -> moveTo(
      intval($args['x'], 10),
      intval($args['y'], 10)
    );

    return $response -> withHeader('Location', '/play');
  }
);

$app -> get(
  '/attack/{x:[0-9]+}/{y:[0-9]+}',
  function (Request $request, Response $response, array $args) {
    $session = new \SlimSession\Helper();

    /** @var Game $game */
    $game = $session -> get('game');

    $game -> attackAt(
      intval($args['x'], 10),
      intval($args['y'], 10)
    );

    return $response -> withHeader('Location', '/play');
  }
);

$app -> run();
