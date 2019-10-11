<?php

include_once 'auth.php';

session_start();

$user = $_SESSION['loggued_on_user'];

if (!$user) {
  echo '';
  return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Уютненькое Школы 21</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style lang="css">
body {
  background: #333;
  color: #eee;

  padding: 1rem 1rem 0 1rem;
}
</style>
</head>
<body>
<?php

$path = '../private';
$file = $path . '/chat';

if (!file_exists($file)) {
  echo '';
  return;
}

$handle = fopen($file, 'rb');
flock($handle, LOCK_SH);

$data = file_get_contents($file);

flock($handle, LOCK_UN);
fclose($handle);

$userdata = unserialize($data);

date_default_timezone_set('Europe/Moscow');

foreach ($userdata as $part) {
  $time = $part['time'];
  $user = $part['login'];
  $msg = $part['msg'];

  echo date('[H:i]', $time) . ' <b>' . $user . '</b>: ' . $msg . '<br />' . "\n";
}

?>
<script langage="javascript">
  setTimeout(() => {
    window.location.href = window.location.href;
  }, 1000);
</script>
</body>
</html>
