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

function get_by_key_val($DB, $table, $key, $value) {
  $res = mysqli_query('SELECT * FROM `' . $table . '` WHERE `' . $key . '` = "' . $value . '"');
  if (!$res) {
    return false;
  }

  return $res;
}

function get_one_by_key_val($DB, $table, $key, $value) {
  $res = mysqli_query('SELECT * FROM `' . $table . '` WHERE `' . $key . '` = "' . $value . '"');
  if (!$res) {
    return false;
  }

  return mysqli_fetch_assoc($res);
}

function get_items_by_category($DB, $category) {
  $category = get_one_by_key_val($DB, 'category', 'short', $category);
  if (!$category) {
    return false;
  }

  $res = mysqli_query($DB, 'SELECT * FROM `items` WHERE `active` = 1 AND `category` = "' . $category['id'] . '"');
  if (!$res) {
    return false;
  }

  return $res;
}
