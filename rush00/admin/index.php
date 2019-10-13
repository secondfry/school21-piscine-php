<?php

require_once 'pieces/header.php';

?>
<h1 class="page_title">Админ-панель</h1>
<a href="/admin.php?entity=category&action=list">Управление категориями</a><br>
<a href="/admin.php?entity=items&action=list">Управление книгами</a><br>
<a href="/admin.php?entity=users&action=list">Управление пользователями</a><br>
<a href="/admin.php?entity=orders&action=list">Управление заказами</a>
<?php

require_once 'pieces/footer.php';
