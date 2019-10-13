<?php

require_once 'engine/engine.php';

$action = url_get('action', '/^[a-z]+$/');

switch ($action) {
  case 'category':
    require_once 'pages/category.php';
    return;
  case 'view':
    $page = url_get('page', '/^[a-z]+$/');
    switch ($page) {
      case 'about':
        require_once 'pages/about.php';
        return;
      case 'contacts':
        require_once 'pages/contacts.php';
        return;
      case 'register':
        require_once 'pages/register.php';
        return;
      case 'login':
        require_once 'pages/login.php';
        return;
      default:
        ft_reset();
        return;
    }
  case 'register':
    user_register($DB);
    return;
  case 'login':
    user_login($DB);
    return;
  case 'logout':
    user_logout($DB);
    return;
  default:
    require_once 'pages/index.php';
    return;
}
