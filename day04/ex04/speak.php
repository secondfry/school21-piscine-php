<?php

include_once 'auth.php';

session_start();

$user = $_SESSION['loggued_on_user'];

if (!$user) {
  echo 'ERROR' . "\n";
  return;
}

$msg = $_POST['msg'] ?? '';

if ($msg !== '') {
  save_message($msg);
}

function save_message($msg) {
  $user = $_SESSION['loggued_on_user'];

  $path = '../private';
  $file = $path . '/chat';

  if (!file_exists($path)) {
    mkdir($path);
  }

  $userdata = [];

  if (file_exists($file)) {
    $handle = fopen($file, 'rb');
    flock($handle, LOCK_SH);

    $data = file_get_contents($file);

    flock($handle, LOCK_UN);
    fclose($handle);

    $userdata = unserialize($data);
  }

  date_default_timezone_set('Europe/Moscow');

  $userdata[] = [
    'login' => $user,
    'time' => time(),
    'msg' => $msg,
  ];

  $data = serialize($userdata);

  $handle = fopen($file, 'wb');
  flock($handle, LOCK_EX);

  file_put_contents($file, $data);

  flock($handle, LOCK_UN);
  fclose($handle);
}

?>
<style lang="css">
#speaker {
  width: 100%;
}
</style>
<script langage="javascript">top.frames['chat'].location = 'chat.php';</script>
<form action="speak.php" method="post">
  <input type="text" name="msg" id="speaker">
</form>
