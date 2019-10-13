<?php

require_once 'engine/engine.php';

$action = url_get('action', '/^[a-z]+$/');

switch ($action) {
  case 'category':
    require_once 'pages/category.php';
    return;
  default:
    require_once 'pages/index.php';
    return;
}
