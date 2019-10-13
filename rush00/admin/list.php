<?php

require_once 'pieces/header.php';

?>
<h1 class="page_title">Админ-панель</h1>
<?php

admin_list($DB, $entity);

require_once 'pieces/footer.php';
    