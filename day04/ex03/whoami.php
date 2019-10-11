<?php

session_start();

$user = $_SESSION['loggued_on_user'];

if (!$user) {
  echo 'ERROR' . "\n";
  return;
}

echo $user . "\n";
