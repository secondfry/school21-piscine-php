<?php

require_once 'pieces/header.php';

?>
<h1 class="page_title">Регистрация</h1>
<form action="/index.php?action=register" method="post" class="form_responsive">
  <div class="form_title">Имя</div>
  <input class="form_input" type="text" name="name">
  <div class="form_title">Электронная&nbsp;почта</div>
  <input class="form_input" type="email" name="email">
  <div class="form_title">Пароль</div>
  <input class="form_input" type="password" name="passwd">
  <div></div>
  <input type="submit" name="submit" value="OK">
</form>
<?php

require_once 'pieces/footer.php';
