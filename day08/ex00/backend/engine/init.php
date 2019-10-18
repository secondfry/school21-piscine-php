<?php

$container = new Container();

$container->set('session', function () {
  return new \SlimSession\Helper;
});

AppFactory::setContainer($container);

$app = AppFactory::create();

$app -> add(
  new SessionMiddleware(
    [
      'name'        => 'dummy_session',
      'autorefresh' => true,
      'lifetime'    => '1 hour'
    ]
  )
);
