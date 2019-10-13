<?php

function url_get_category() {
  return url_get('category', '/^[a-z]+$/');
}

function url_get($name, $regex) {
  if (empty($_GET[$name])) {
    return false;
  }

  $status = preg_match($regex, $_GET[$name], $matches);
  if (!$status) {
    ft_reset();
  }

  return $matches[0];
}

function ft_reset() {
  ft_reset_to('/');
}

function ft_reset_to($page) {
  if ($_SERVER['REQUEST_URI'] === $page) {
    return;
  }

  header('Location: ' . $page);
  exit;
}

function get_all($DB, $table) {
  $res = mysqli_query($DB, 'SELECT * FROM `' . $table . '` WHERE `active` = 1');
  if (!$res) {
    return false;
  }

  return $res;
}

function get_by_key_val($DB, $table, $key, $value) {
  $res = mysqli_query($DB, 'SELECT * FROM `' . $table . '` WHERE `' . $key . '` = "' . $value . '"');
  if (!$res) {
    return false;
  }

  return $res;
}

function get_one_by_key_val($DB, $table, $key, $value) {
  $res = mysqli_query($DB, 'SELECT * FROM `' . $table . '` WHERE `' . $key . '` = "' . $value . '"');
  if (!$res) {
    return false;
  }

  return mysqli_fetch_assoc($res);
}

function get_items_by_category($DB, $category) {
  $res = mysqli_query($DB, 'SELECT * FROM `items` WHERE `active` = 1 AND `category` = "' . $category['id'] . '"');
  if (!$res) {
    return false;
  }

  return $res;
}

function display_item($item) {
  ?>
<div class="shop_item">
  <img src="<?=$item['image']?>" alt="<?=$item['name']?>" class="shop_item_image img_responsive">
  <h2 class="shop_item_title"><?=$item['name']?></h2>
  <p class="shop_item_description"><?=$item['description']?></p>
  <div class="shop_item_actions">В корзину!</div>
</div><?php
}

function user_register($DB) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $pass = $_POST['passwd'];
  $salt = $_POST['submit'];

  if (!$name) {
    $_SESSION['notification'][] = [
      'text' => 'Вы не указали имя при регистрации!',
      'type' => 'bad',
    ];
  }

  if (!$email) {
    $_SESSION['notification'][] = [
      'text' => 'Вы не указали электронную почту при регистрации!',
      'type' => 'bad',
    ];
  }

  if (!$pass) {
    $_SESSION['notification'][] = [
      'text' => 'Вы не указали пароль при регистрации!',
      'type' => 'bad',
    ];
  }

  if (!$salt || $salt !== 'OK') {
    $_SESSION['notification'][] = [
      'text' => 'Nice try! ;)',
      'type' => 'bad',
    ];
  }

  if (!$name || !$email || !$pass) {
    ft_reset_to('/index.php?action=view&page=register');
  }

  if (!$salt || $salt !== 'OK') {
    ft_reset();
  }

  $stmt = mysqli_prepare($DB, 'SELECT `id` FROM `users` WHERE `email` = ?');
  if (!$stmt) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL.',
      'type' => 'bad',
    ];
    ft_reset();
  }

  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  if (mysqli_stmt_fetch($stmt)) {
    $_SESSION['notification'][] = [
      'text' => 'Пользователь с таким адресом электронной почты уже зарегистрирован!',
      'type' => 'bad',
    ];
    mysqli_stmt_close($stmt);
    ft_reset();
  }
  mysqli_stmt_close($stmt);

  $stmt = mysqli_prepare($DB, 'INSERT INTO `users` (`name`, `email`, `password`) VALUES (?, ?, ?)');
  if (!$stmt) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL.',
      'type' => 'bad',
    ];
    ft_reset();
  }

  $pass = hash('sha512', $pass);
  mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $pass);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  $_SESSION['notification'][] = [
    'text' => 'Регистрация прошла успешно!',
    'type' => 'good',
  ];
  $_SESSION['user']['name'] = $name;
  $_SESSION['user']['email'] = $email;
  ft_reset();
}

function display_notification() {
  if (empty($_SESSION['notification'])) {
    return;
  }

  foreach($_SESSION['notification'] as $v) {
    ?>
<div class="notification <?=$v['type']?>">
  <?=$v['text']?>
</div>
    <?php
  }

  $_SESSION['notification'] = [];
}

function display_login_logout() {
  if (empty($_SESSION['user']['name'])) {
    ?>
<a href="/index.php?action=view&page=login">Войти</a>
    <?php
  } else {
    ?>
<div class="nowrap">
  Привет, <?=htmlentities($_SESSION['user']['name'])?>!
  <a class="ml1" href="/index.php?action=logout">Выйти</a>
</div>
    <?php
  }
}

function user_login($DB) {
  $email = $_POST['email'];
  $pass = $_POST['passwd'];
  $salt = $_POST['submit'];

  if (!$email) {
    $_SESSION['notification'][] = [
      'text' => 'Вы не указали электронную почту для входа!',
      'type' => 'bad',
    ];
  }

  if (!$pass) {
    $_SESSION['notification'][] = [
      'text' => 'Вы не указали пароль для входа!',
      'type' => 'bad',
    ];
  }

  if (!$salt || $salt !== 'OK') {
    $_SESSION['notification'][] = [
      'text' => 'Nice try! ;)',
      'type' => 'bad',
    ];
  }

  if (!$email || !$pass) {
    ft_reset_to('/index.php?action=view&page=login');
  }

  if (!$salt || $salt !== 'OK') {
    ft_reset();
  }

  $stmt = mysqli_prepare($DB, 'SELECT `name`, `email`, `password` FROM `users` WHERE `email` = ?');
  if (!$stmt) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL.',
      'type' => 'bad',
    ];
    ft_reset();
  }

  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $name_db, $email_db, $pass_db);

  if (!mysqli_stmt_fetch($stmt)) {
    $_SESSION['notification'][] = [
      'text' => 'Проверьте свой логин и пароль.',
      'type' => 'bad',
    ];
    mysqli_stmt_close($stmt);
    ft_reset_to('/index.php?action=view&page=login');
  }
  mysqli_stmt_close($stmt);

  $pass = hash('sha512', $pass);
  if ($pass != $pass_db) {
    $_SESSION['notification'][] = [
      'text' => 'Проверьте свой логин и пароль.',
      'type' => 'bad',
    ];
    ft_reset_to('/index.php?action=view&page=login');
  }

  $_SESSION['notification'][] = [
    'text' => 'Успешный вход ( ͡° ͜ʖ ͡°)',
    'type' => 'good',
  ];
  $_SESSION['user']['name'] = $name_db;
  $_SESSION['user']['email'] = $email_db;
  ft_reset();
}

function user_logout() {
  $_SESSION['notification'][] = [
    'text' => 'Успешный выход ( ͡° ͜ʖ ͡°)',
    'type' => 'good',
  ];
  $_SESSION['user'] = [
    'type' => 'user',
    'name' => '',
    'email' => '',
  ];
  ft_reset();
}
