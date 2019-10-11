<?php

$action = $_GET['action'];
$name = $_GET['name'];
$value = $_GET['value'];
$expires = time() + 3600;

switch ($action) {
  case 'set':
    setcookie($name, $value, $expires);
    return;
  case 'get':
    if (!$_COOKIE[$name]) {
      return;
    }
    echo $_COOKIE[$name] . "\n";
    return;
  case 'del':
    setcookie($name, $value, 1);
    return;
}
