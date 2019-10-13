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
    require_once 'admin/edit.php';
    return;
  case 'list':
    save_history();
    require_once 'admin/list.php';
    break;
  default:
    user_logout();
}
