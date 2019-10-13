<?php

session_start();

if (empty($_SESSION['notification'])) {
  $_SESSION['notification'] = [];
}

if (empty($_SESSION['user'])) {
  $_SESSION['user'] = [
    'type' => 'user',
    'name' => '',
    'email' => '',
  ];
}
