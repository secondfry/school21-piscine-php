<?php

include_once 'auth.php';

session_start();

$user = $_POST['login'] ?? '';
$pass = $_POST['passwd'] ?? '';

if (!auth($user, $pass)) {
  $_SESSION['loggued_on_user'] = '';
  echo 'ERROR' . "\n";
  return;
}

$_SESSION['loggued_on_user'] = $user;

?>
<!DOCTYPE html>
<html>
<head>
<title>Уютненькое Школы 21</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style lang="css">
body {
  display: grid;
  grid-template-rows: 550px 50px;

  background: #333;
  color: #eee;
}
#chat {
  height: 550px;
}
#speak {
  height: 50px;
}
</style>
</head>
<body>
<div class="embed-responsive">
  <iframe class="embed-responsive-item" name="chat" id="chat" src="chat.php"></iframe>
</div>
<div class="embed-responsive">
  <iframe class="embed-responsive-item" name="speak" id="speak" src="speak.php"></iframe>
</div>
</body>
</html>
