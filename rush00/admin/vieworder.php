<?php

require_once 'pieces/header.php';

$id = url_get('id', '/^[0-9]+$/');

$res = mysqli_query($DB, 'SELECT * FROM `orders` LEFT JOIN `users` ON `orders`.`user_id`=`users`.`id` WHERE `orders`.`id` = ' . $id);
$row = mysqli_fetch_assoc($res);

?>
<h1 class="page_title">Админ-панель</h1>
<p>Заказ #<?=$id?> от пользователя <?=$row['email']?> (<?=$row['name']?>)</p>
<table class="basket_table">
<thead>
  <tr>
    <th class="basket_table_th_name">Название</th>
    <th>Цена</th>
    <th>Количество</th>
    <th>Итого</th>
  </tr>
</thead>
<tbody>
<?php

$result = 0;

$res = mysqli_query($DB, 'SELECT * FROM `order_item` LEFT JOIN `items` ON `order_item`.`item_id`=`items`.`id` WHERE `order_id` = ' . $id);
while ($row = mysqli_fetch_assoc($res)) {
?>
  <tr>
    <td><?=$row['name']?></td>
    <td><?=format_price($row['price'])?></td>
    <td><?=$row['item_qty']?></td>
    <td><?=format_price($row['price'] * $row['item_qty'])?></td>
    <?php
      $result += $row['price'] * $row['item_qty'];
    ?>
  </tr>
<?php
}

?>
</tbody>
</table>
Итого: <?=format_price($result)?>.<br>
<?php

require_once 'pieces/footer.php';
