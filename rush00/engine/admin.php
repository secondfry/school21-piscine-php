<?php

function check_admin() {
  if ($_SESSION['user']['type'] === 'admin') {
    return;
  }

  user_logout();
}

function admin_list($DB, $entity) {
  ?>
    <h2 class="page_title_small">Список <?=$entity?></h2>
    <a class="shop_item_basket add" href="/admin.php?entity=<?=$entity?>&action=add">Добавить</a><br><br>
  <?php
  $res = mysqli_query($DB, 'SELECT * FROM `' . $entity . '`');
  while ($row = mysqli_fetch_assoc($res)) {
    if ($entity === 'category' && $row['short'] === 'all') {
      continue;
    }
  ?>
    <a class="shop_item_basket remove" href="/admin.php?entity=<?=$entity?>&action=remove&id=<?=$row['id']?>" onclick="return confirm('Вы уверены? Гораздо лучше просто отключить его показ на сайте.') ? true : false;">Удалить</a>
    <a href="/admin.php?entity=<?=$entity?>&action=edit&id=<?=$row['id']?>"><?=$row['id']?> - <?=$row['name']?></a><br>
  <?php
  }
}

function admin_add($DB, $entity) {
  switch ($entity) {
    case 'users':
      $res = mysqli_query($DB, 'SELECT * FROM `' . $entity . '` WHERE `email` = "editme"');
      break;
    default:
      $res = mysqli_query($DB, 'SELECT * FROM `' . $entity . '` WHERE `short` = "editme"');
      break;
  }
  $data = mysqli_fetch_assoc($res);
  if (!empty($data)) {
    ft_reset_to('/admin.php?entity=' . $entity . '&action=edit&id=' . $data['id']);
    return;
  }

  switch ($entity) {
    case 'users':
      $res = mysqli_query($DB, 'INSERT INTO `' . $entity . '` (`email`) VALUES ("editme")');
      break;
    default:
      $res = mysqli_query($DB, 'INSERT INTO `' . $entity . '` (`short`) VALUES ("editme")');
      break;
  }
  $id = mysqli_insert_id($DB);
  ft_reset_to('/admin.php?entity=' . $entity . '&action=edit&id=' . $id);
}

function admin_remove($DB, $entity, $id) {
  mysqli_query($DB, 'DELETE FROM `' . $entity . '` WHERE `id` = ' . $id);
  ft_reset_to($_SESSION['page']);
}

function admin_edit($DB, $entity, $id) {
  $res = mysqli_query($DB, 'SELECT * FROM `' . $entity . '` WHERE `id` = ' . $id);
  $data = mysqli_fetch_assoc($res);
  ?>
    <h2 class="page_title_small">Редактирование <?=$data['short']?></h2>
    <form class="admin_form" action="/admin.php?entity=<?=$entity?>&action=submit" method="post">
      <input type="hidden" name="id" value="<?=$data['id']?>">
      <table>
        <thead>
          <tr>
            <th>Колонка</th>
            <th>Новое значение</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($data as $k => $v) {
            switch ($k) {
              case 'id':
                ?>
                <tr>
                  <td><?=$k?></td>
                  <td><input class="disabled" type="text" name="<?=$k?>" value="<?=$v?>" disabled></td>
                </tr>
                <?php
                break;
              case 'description':
                ?>
                <tr>
                  <td><?=$k?></td>
                  <td><textarea name="<?=$k?>"><?=$v?></textarea>
                </tr>
                <?php
                break;
              case 'password':
                ?>
                <tr>
                  <td><?=$k?></td>
                  <td><input type="password" name="<?=$k?>"></td>
                </tr>
                <?php
                break;
              case 'type':
                $flag_user = $v === 'user' ? ' selected' : '';
                $flag_admin = $v === 'admin' ? ' selected' : '';
                ?>
                <tr>
                  <td><?=$k?></td>
                  <td>
                    <select name="<?=$k?>">
                      <option value="user"<?=$flag_user?>>Пользователь</option>
                      <option value="admin"<?=$flag_admin?>>Администратор</option>
                    </select>
                  </td>
                </tr>
                <?php
                break;
              case 'created_at':
              case 'updated_at':
                continue;
              default:
                ?>
                <tr>
                  <td><?=$k?></td>
                  <td><input type="text" name="<?=$k?>" value="<?=$v?>"></td>
                </tr>
                <?php
                break;
            }
          }
          ?>
        </tbody>
      </table>
      <input type="submit" value="Отправить">
    </form>
  <?php

  if ($entity === 'items') {
    admin_show_links($DB, $id);
  }
}

function admin_show_links($DB, $id) {
  ?>
  <h2 class="page_title_small">Управление категориями</h2>
  <?php
  $res = mysqli_query($DB, 'SELECT * FROM `category_item` WHERE `item_id` = ' . $id);
  if (!$res) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL.',
      'type' => 'bad',
    ];
    ft_reset_to('/admin.php');
    return;
  }

  $cats = [0];
  while ($row = mysqli_fetch_assoc($res)) {
    $cats[] = $row['category_id'];
    $category = get_one_by_key_val($DB, 'category', 'id', $row['category_id']);
    ?>
      <a class="shop_item_basket remove" href="/admin.php?entity=items&action=unlink&book_id=<?=$id?>&category_id=<?=$category['id']?>">Удалить из категории</a> <?=$category['name']?><br>
    <?php
  }

  $res = mysqli_query($DB, 'SELECT * FROM `category` WHERE `id` NOT IN (' . implode(',', $cats) . ')');
  while ($row = mysqli_fetch_assoc($res)) {
    if ($row['short'] === 'all') {
      continue;
    }
    ?>
      <a class="shop_item_basket add" href="/admin.php?entity=items&action=link&book_id=<?=$id?>&category_id=<?=$row['id']?>">Добавить в категорию</a> <?=$row['name']?><br>
    <?php
  }
}

function admin_link($DB, $book_id, $category_id) {
  $res = mysqli_query($DB, 'INSERT INTO `category_item` (`category_id`, `item_id`) VALUES (' . $category_id . ', ' . $book_id . ')');
  if (!$res) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL. ' .'INSERT INTO `category_item` (`category_id`, `item_id`) VALUES (' . $category_id . ', ' . $book_id . ')',
      'type' => 'bad',
    ];
    ft_reset_to($_SESSION['page']);
  }

  $_SESSION['notification'][] = [
    'text' => 'Успешное добавление книги в категорию.',
    'type' => 'good',
  ];
  ft_reset_to($_SESSION['page']);
}

function admin_unlink($DB, $book_id, $category_id) {
  $res = mysqli_query($DB, 'DELETE FROM `category_item` WHERE `category_id` = ' . $category_id . ' AND `item_id` = ' . $book_id);
  if (!$res) {
    $_SESSION['notification'][] = [
      'text' => 'Ошибка MySQL.',
      'type' => 'bad',
    ];
    ft_reset_to($_SESSION['page']);
  }

  $_SESSION['notification'][] = [
    'text' => 'Успешное удаление книги из категории.',
    'type' => 'good',
  ];
  ft_reset_to($_SESSION['page']);
}

function admin_submit($DB, $entity) {
  if (empty($_POST['id'])) {
    $_SESSION['notification'][] = [
      'text' => 'Плохой пользователь!',
      'type' => 'bad',
    ];
    user_logout();
    ft_reset();
  }

  $status = preg_match('/^[0-9]+$/', $_POST['id'], $matches);
  if (!$status) {
    $_SESSION['notification'][] = [
      'text' => 'Плохой пользователь!',
      'type' => 'bad',
    ];
    user_logout();
    ft_reset();
  }

  $id = $matches[0];

  foreach ($_POST as $k => $v) {
    switch ($k) {
      case 'id':
        break;
      case 'password':
        if (empty($v)) {
          break;
        }
        $v = hash('sha512', $v);
      default:
        $query = 'UPDATE `' . $entity .'` SET `' . htmlentities($k) . '` = "' . htmlentities($v) . '" WHERE `id` = ' . $id;
        mysqli_query($DB, 'UPDATE `' . $entity .'` SET `' . htmlentities($k) . '` = "' . htmlentities($v) . '" WHERE `id` = ' . $id);
        $err = mysqli_error($DB);
        if (!empty($err)) {
          $_SESSION['notification'][] = [
            'text' => 'Ошибка MySQL.<br><pre>' . $query . '</pre>',
            'type' => 'bad',
          ];
          return;
        } else {
          $_SESSION['notification'][] = [
            'text' => '<pre>' . $query . '</pre>',
            'type' => 'good',
          ];
        }
    }
  }

  ft_reset_to('/admin.php');
}
