<?php

session_start();

function check_session() {
  $user = $_GET['login'];
  $pass = $_GET['passwd'];
  $salt = $_GET['submit'];

  if ($salt !== 'OK') {
    return;
  }

  $_SESSION['login'] = $user;
  $_SESSION['passwd'] = $pass;
}

check_session();

?>
<html><body>
<form action="index.php">
   Username: <input type="text" name="login" value="<?=$_SESSION['login']?>">
   <br>
   Password: <input type="text" name="passwd" value="<?=$_SESSION['passwd']?>">
  <input type="submit" name="submit" value="OK">
</form>
</body></html>
