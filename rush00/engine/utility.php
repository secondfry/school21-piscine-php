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
