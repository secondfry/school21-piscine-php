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
  $res = mysqli_query($DB, '
    SELECT *
    FROM `items`
    WHERE `active` = 1
          AND `id` IN (SELECT `item_id` FROM `category_item` WHERE `category_id` = "' . $category['id'] . '")
  ');
  if (!$res) {
    return false;
  }

  return $res;
}

function display_item($DB, $item) {
  ?>
    <div class="shop_item">
      <img src="<?=$item['image']?>" alt="<?=$item['name']?>" class="shop_item_image img_responsive">
      <h2 class="shop_item_title"><?=$item['name']?></h2>
      <div class="shop_item_categories">
        <?php
          $res = mysqli_query($DB, '
            SELECT *
            FROM `category`
            WHERE `active` = 1
                  AND `id` IN (SELECT `category_id` FROM `category_item` WHERE `item_id` = "' . $item['id'] . '")
          ');
          while ($category = mysqli_fetch_assoc($res)) { ?>
            <a class="shop_item_category" href="/index.php?action=category&category=<?=$category['short']?>"><?=$category['name']?></a>
          <?php }
        ?>
      </div>
      <p class="shop_item_description"><?=$item['description']?></p>
      <p class="shop_item_price"><?=number_format($item['price'], 2, '.', ' ')?> ₽</p>
      <div class="shop_item_actions">
        <?php
          $res = array_reduce($_SESSION['basket'], function ($acc, $cur) {
            if ($acc === $cur['id']) {
              $acc = true;
            }
            return $acc;
          }, $item['id']);
          if ($res === true) { ?>
            <a class="shop_item_basket remove" href="/index.php?action=basket&basket_action=remove&item_id=<?=$item['id']?>">Из корзины</a>
          <?php } else { ?>
            <a class="shop_item_basket add" href="/index.php?action=basket&basket_action=add&item_id=<?=$item['id']?>">В корзину</a>
          <?php }
        ?>
      </div>
    </div>
  <?php
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

function save_history() {
  $_SESSION['page'] = $_SERVER['REQUEST_URI'];
}
