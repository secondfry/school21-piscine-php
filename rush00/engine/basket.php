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
  $_SESSION['basket'] = array_filter($_SESSION['basket'], function ($item) use ($item_id) { return $item['id'] !== $item_id; });
  $_SESSION['notification'][] = [
    'text' => 'Предмет успешно удален из корзины.',
    'type' => 'good',
  ];

  header('Location: ' . $_SESSION['page']);
}

function basket_qty($DB) {
  if (empty($_SESSION['basket'])) {
    $_SESSION['notification'][] = [
      'text' => 'Корзина пуста.',
      'type' => 'bad',
    ];

    ft_reset_to($_SESSION['page']);
  }

  $qty = $_POST['qty'];
  $flag = false;

  foreach ($qty as $key => $value) {
    array_walk($_SESSION['basket'], function (&$item, $key, $data) use (&$flag) {
      if ($item['id'] == $data['id']) {
        if ($item['qty'] != intval($data['qty'])) {
          $flag = true;
        }
        $item['qty'] = intval($data['qty']);
      }
    }, [
      'id' => $key,
      'qty' => $value,
    ]);
  }

  if ($flag) {
    $_SESSION['notification'][] = [
      'text' => 'Количество предметов успешно изменено.',
      'type' => 'good',
    ];
  }

  if ($_POST['action'] === 'Оформить заказ') {
    if (empty($_SESSION['user']['email'])) {
      $_SESSION['notification'][] = [
        'text' => 'Оформление заказ доступно только зарегистрированным пользователям.',
        'type' => 'bad',
      ];

      ft_reset_to('Location: /index.php?action=view&page=login');
    }

    place_order($DB);
  }

  header('Location: ' . $_SESSION['page']);
}

function place_order($DB) {
  if (empty($_SESSION['basket'])) {
    $_SESSION['notification'][] = [
      'text' => 'Заказ пустой.',
      'type' => 'bad',
    ];

    ft_reset_to($_SESSION['page']);
  }

  $res = mysqli_query($DB, 'INSERT INTO `orders` (`user_id`) VALUES (' . $_SESSION['user']['id'] . ')');
  $order_id = mysqli_insert_id($DB);
  if (!$order_id) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL.',
      'type' => 'bad',
    ];
    ft_reset();
  }

  while ($item = array_pop($_SESSION['basket'])) {
    $status = mysqli_query($DB, 'INSERT INTO `order_item` (`order_id`, `item_id`, `item_qty`) VALUES (' . $order_id . ', ' . $item['id'] . ', ' . $item['qty'] . ')');
    if (!$status) {
      $_SESSION['notification'][] = [
        'text' => 'Ошибка MySQL.',
        'type' => 'bad',
      ];
      ft_reset();
    }
  }

  $_SESSION['notification'][] = [
    'text' => 'Заказ оформлен!',
    'type' => 'good',
  ];
  ft_reset();
}
