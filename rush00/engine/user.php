<?php

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

  $stmt = mysqli_prepare($DB, 'INSERT INTO `users` (`name`, `email`, `password`, `active`) VALUES (?, ?, ?, 1)');
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
    'text' => 'Регистрация прошла успешно! Теперь Вы можете войти.',
    'type' => 'good',
  ];
  ft_reset();
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

  $stmt = mysqli_prepare($DB, 'SELECT `id`, `type`, `name`, `email`, `password` FROM `users` WHERE `email` = ?');
  if (!$stmt) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL.',
      'type' => 'bad',
    ];
    ft_reset();
  }

  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $id_db, $type_db, $name_db, $email_db, $pass_db);

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
  $_SESSION['user']['type'] = $type_db;
  $_SESSION['user']['id'] = $id_db;
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
    'id' => 0,
    'name' => '',
    'email' => '',
  ];
  ft_reset();
}
