<?php

include_once 'auth.php';

session_start();

$user = $_GET['login'];
$pass = $_GET['passwd'];

if (!auth($user, $pass)) {
  $_SESSION['loggued_on_user'] = '';
  echo 'ERROR' . "\n";
  return;
}

$_SESSION['loggued_on_user'] = $user;
echo 'OK' . "\n";
