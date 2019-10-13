<?php

require_once 'pieces/header.php';

?>
<h1 class="page_title">Форма входа</h1>
<form action="/index.php?action=login" method="post" class="form_responsive">
  <div class="form_title nowrap">Электронная почта</div>
  <input class="form_input" type="email" name="email">
  <div class="form_title">Пароль</div>
  <input class="form_input" type="password" name="passwd">
  <div></div>
  <input type="submit" name="submit" value="OK">
</form>
<?php

require_once 'pieces/footer.php';
