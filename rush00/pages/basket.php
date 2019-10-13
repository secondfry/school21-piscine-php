<?php

require_once 'pieces/header.php';

if (empty($_SESSION['user']['email'])) {

}

?>
<h1 class="page_title">Корзина</h1>
<form action="/index.php?action=basket&basket_action=qty" method="post">
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

$items = array_reduce($_SESSION['basket'], function ($acc, $cur) use ($DB) {
  $item = get_one_by_key_val($DB, 'items', 'id', $cur['id']);
  $item['qty'] = $cur['qty'];
  $acc[] = $item;
  return $acc;
}, []);

foreach ($items as $item) {
?>
  <tr>
    <td><?=$item['name']?></td>
    <td><?=format_price($item['price'])?></td>
    <td>
      <input type="number" value="<?=$item['qty']?>" name="qty[<?=$item['id']?>]">
    </td>
    <td><?=format_price($item['price'] * $item['qty'])?></td>
    <?php
      $result += $item['price'] * $item['qty']);
    ?>
  </tr>
<?php
}

?>
</tbody>
</table>
Итого: <?=format_price($result)?>.<br>
<input type="submit" name="action" value="Пересчитать">
<?php
  if (empty($_SESSION['user']['email'])) {
    ?>
      <p>Для оформления заказ пройдите регистрацию!</p>
    <?php
  } else {
    ?>
      <input type="submit" name="action" value="Оформить заказ">
    <?php
  }
?>
</form>
<?php

require_once 'pieces/footer.php';
