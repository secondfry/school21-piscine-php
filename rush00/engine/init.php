<?php

session_start();

if (empty($_SESSION['notification'])) {
  $_SESSION['notification'] = [];
}

if (empty($_SESSION['user'])) {
  $_SESSION['user'] = [
    'type' => 'user',
    'id' => 0,
    'name' => '',
    'email' => '',
  ];
}

if (empty($_SESSION['basket'])) {
  $_SESSION['basket'] = [];
}

if (empty($_SESSION['page'])) {
  $_SESSION['page'] = '/';
}
