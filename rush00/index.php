<?php

require_once 'engine/engine.php';

$action = url_get('action', '/^[a-z]+$/');

switch ($action) {
  case 'category':
    save_history();
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
  case 'basket':
    $baction = url_get('basket_action', '/^[a-z]+$/');
    switch ($baction) {
      case 'add':
        $item = url_get('item', '/^[0-9]+$/');
        basket_add($DB, $item);
        return;
      case 'remove':
        $item = url_get('item', '/^[0-9]+$/');
        basket_remove($DB, $item);
        return;
      default:
        ft_reset();
        return;
    }
  default:
    require_once 'pages/index.php';
    return;
}
