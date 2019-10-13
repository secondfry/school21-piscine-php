<?php

require_once 'pieces/header.php';

?>
<h1 class="page_title">Книжный магазин «Уютный раш»</h1>
<p>
  Добро пожаловать в наше уютненькое!<br>
  Только у нас Вы можете приобрети лучшие книги по программированию во всем Новом Эдене!
</p>
<h2 class="page_title_small">Избранные товары</h2>
<div class="shop_items">
<?php

$res = mysqli_query($DB, 'SELECT * FROM `items` LIMIT 2');
if ($res) {
  while ($item = mysqli_fetch_assoc($res)) {
    display_item($DB, $item);
  }
}
?>
</div>
<?php

require_once 'pieces/footer.php';
