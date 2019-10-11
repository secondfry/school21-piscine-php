<?php

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

if (!$user || !$pass || $user != 'zaz' || ($pass != 'Ilovemylittleponey' && $pass != 'jaimelespetitsponeys')) {
  header('HTTP/1.0 401 Unauthorized');
  header('WWW-Authenticate: Basic realm=\'\'Member area\'\'');
  echo '<html><body>That area is accessible for members only</body></html>' . "\n";
  exit;
}

?>
<html><body>
Hello Zaz<br />
<img src='<?php echo base64_encode(file_get_contents('../img/42.png')); ?>'>
</body></html>
