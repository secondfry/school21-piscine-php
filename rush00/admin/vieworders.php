<?php

require_once 'pieces/header.php';

?>
<h1 class="page_title">Админ-панель</h1>
<?php

$res = mysqli_query($DB, 'SELECT *, `orders`.`id` as `order_id` FROM `orders` LEFT JOIN `users` ON `orders`.`user_id`=`users`.`id`');
while ($row = mysqli_fetch_assoc($res)) {
?>
<a href="/admin.php?entity=orders&action=see&id=<?=$row['order_id']?>">Заказ #<?=$row['order_id']?> от пользователя <?=$row['email']?></a><br>
<?php
}


require_once 'pieces/footer.php';
