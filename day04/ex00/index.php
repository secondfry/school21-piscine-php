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
<html>
<body>
<form action="index.php">
  <table>
    <tr>
      <td>Username</td>
      <td>
        <input type="text" name="login" value="<?=$_SESSION['login']?>">
      </td>
    </tr>
    <tr>
      <td>Password</td>
      <td>
        <input type="text" name="passwd" value="<?=$_SESSION['passwd']?>">
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <input type="submit" name="submit" value="OK">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
