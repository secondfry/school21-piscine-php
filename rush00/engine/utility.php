<?php

function url_get_category() {
  return url_get('category', '/^[a-z]+$/');
}

function url_get($name, $regex) {
  $status = preg_match($regex, $_GET[$name], $matches);
  if (!$status) {
    ft_reset();
  }

  return $matches[0];
}

function ft_reset() {
  if ($_SERVER['REQUEST_URI'] === '/') {
    return;
  }

  header('Location: /');
  exit;
}

function get_all($DB, $table) {
  $res = mysqli_query($DB, 'SELECT * FROM `' . $table . '` WHERE `active` = 1');
  if (!$res) {
    return false;
  }

  return $res;
}
