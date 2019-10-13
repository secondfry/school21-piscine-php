<?php

function basket_add($DB, $item_id) {
  $_SESSION['basket'][] = [
    'id' => $item_id,
    'qty' => 1,
  ];
  $_SESSION['notification'][] = [
    'text' => 'Предмет успешно добавлен в корзину.',
    'type' => 'good',
  ];

  header('Location: ' . $_SESSION['page']);
}

function basket_remove($DB, $item_id) {
  $ret = array_filter($_SESSION['basket'], function ($item) use ($item_id) { return $item['id'] !== $item_id; });
  $_SESSION['basket'] = $ret;
  $_SESSION['notification'][] = [
    'text' => 'Предмет успешно удален из корзины.',
    'type' => 'good',
  ];

  header('Location: ' . $_SESSION['page']);
}
