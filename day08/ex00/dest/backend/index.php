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

  $payload =
    [
      'error' => $exception -> getMessage(),
      'file'  => $exception -> getFile(),
      'line'  => $exception -> getLine(),
      'trace' => $exception -> getTrace(),
    ];

  $response -> getBody() -> write(
    json_encode($payload, JSON_UNESCAPED_UNICODE)
  );

  return $response;
};

// Add Error Middleware
$errorMiddleware = $app -> addErrorMiddleware(true, true, true);
$errorMiddleware -> setDefaultErrorHandler($customErrorHandler);

$app -> get(
  '/list',
  function (Request $request, Response $response, array $args) {
    $ret = '';

    $ret .= GameField::HEADER;
    $ret .= '<div class="px-1">';
    $ret .= GameField::NAVIGATION;

    $allGamesData = MDB ::get() -> games -> find(['status' => 'active']);
    foreach ($allGamesData as $gameData) {
      $ret .= sprintf(
        '<a href="/play/%s">%s</a><br>',
        $gameData['_id'],
        $gameData['_id']
      );
    }

    $ret .= '</div>';

    $response -> getBody() -> write($ret);
    return $response;
  }
);

$app -> get(
  '/create',
  function (Request $request, Response $response, array $args) {
    $game = Game ::constructPreset();
    $game -> store();
    $url = sprintf(
      '/play/%s',
      $game -> getID()
    );
    return $response -> withHeader('Location', $url);
  }
);

$app -> get(
  '/play/{gameID:[0-9a-f]{24}}',
  function (Request $request, Response $response, array $args) {
    $game = Game ::recreate($args['gameID']);

    while ($game -> play()) {
    }
    $game -> store();

    $ret = $game -> getField() -> render($game, $game -> getState());
    $response -> getBody() -> write($ret);
    return $response;
  }
);

$app -> get(
  '/play/{gameID:[0-9a-f]{24}}/select/{shipID:[0-9]+}',
  function (Request $request, Response $response, array $args) {
    $game = Game ::recreate($args['gameID']);

    $game -> selectShip(intval($args['shipID'], 10));
    $game -> store();

    $url = sprintf(
      '/play/%s',
      $game -> getID()
    );
    return $response -> withHeader('Location', $url);
  }
);

$app -> get(
  '/play/{gameID:[0-9a-f]{24}}/move/x/{x:[0-9]+}/y/{y:[0-9]+}',
  function (Request $request, Response $response, array $args) {
    $game = Game ::recreate($args['gameID']);

    $game -> moveTo(
      intval($args['x'], 10),
      intval($args['y'], 10)
    );
    $game -> store();

    $url = sprintf(
      '/play/%s',
      $game -> getID()
    );
    return $response -> withHeader('Location', $url);
  }
);

$app -> get(
  '/play/{gameID:[0-9a-f]{24}}/attack/x/{x:[0-9]+}/y/{y:[0-9]+}',
  function (Request $request, Response $response, array $args) {
    $game = Game ::recreate($args['gameID']);

    $game -> attackAt(
      intval($args['x'], 10),
      intval($args['y'], 10)
    );
    $game -> store();

    $url = sprintf(
      '/play/%s',
      $game -> getID()
    );
    return $response -> withHeader('Location', $url);
  }
);

$app -> get(
  '/',
  function (Request $request, Response $response, array $args) {
    $ret = '';

    $ret .= GameField::HEADER;
    $ret .= '<div class="px-1">';
    $ret .= GameField::NAVIGATION;
    $ret .= '<p>Это пацанский лэндинг пейдж, бро!</p>';
    $ret .= '</div>';

    $response -> getBody() -> write($ret);
    return $response;
  }
);

$app -> run();
