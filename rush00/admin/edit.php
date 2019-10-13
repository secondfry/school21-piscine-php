<?php

require_once 'pieces/header.php';

?>
<h1 class="page_title">Админ-панель</h1>
<?php

$id = url_get('id', '/^[0-9]+$/');
admin_edit($DB, $entity, $id);

require_once 'pieces/footer.php';
