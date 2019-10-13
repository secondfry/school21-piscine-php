<?php

require_once 'engine/engine.php';
require_once 'engine/admin.php';

check_admin();

$entity = url_get('entity', '/^[a-z]+$/');
$action = url_get('action', '/^[a-z]+$/');

switch ($entity) {
  case '':
  case 'category':
  case 'items':
  case 'users':
  case 'orders':
    break;
  default:
    user_logout();
}

switch ($action) {
  case '':
  case 'add':
  case 'edit':
  case 'remove':
  case 'list':
  case 'submit':
  case 'link':
  case 'unlink':
  case 'see':
    break;
  default:
    user_logout();
}

if (empty($entity)) {
  require_once 'admin/index.php';
  return;
}

switch ($action) {
  case 'submit':
    admin_submit($DB, $entity);
    return;
  case 'remove':
    $id = url_get('id', '/^[0-9]+$/');
    admin_remove($DB, $entity, $id);
    return;
  case 'add':
    admin_add($DB, $entity);
    return;
  case 'edit':
    save_history();
    require_once 'admin/edit.php';
    return;
  case 'list':
    save_history();

    if ($entity === 'orders') {
      require_once 'admin/vieworders.php';
      return;
    }

    require_once 'admin/list.php';
    break;
  case 'link':
    $book_id = url_get('book_id', '/^[0-9]+$/');
    $category_id = url_get('category_id', '/^[0-9]+$/');
    admin_link($DB, $book_id, $category_id);
    break;
  case 'unlink':
    $book_id = url_get('book_id', '/^[0-9]+$/');
    $category_id = url_get('category_id', '/^[0-9]+$/');
    admin_unlink($DB, $book_id, $category_id);
    break;
  case 'see':
    if ($entity !== 'orders') {
      user_logout();
      return;
    }
    require_once 'admin/vieworder.php';
    break;
  default:
    user_logout();
}
