<?php

$user = $_POST['login'];
$pass_old = $_POST['oldpw'];
$pass_new = $_POST['newpw'];
$salt = $_POST['submit'];

if (!$user || !$pass_old || !$pass_new || !$salt || $salt !== 'OK') {
  echo 'ERROR' . "\n";
  return;
}

$pass_old = hash('sha512', $pass_old);
$pass_new = hash('sha512', $pass_new);

$path = '../private';
$file = $path . '/passwd';

if (!file_exists($file)) {
  echo 'ERROR' . "\n";
  return;
}

$data = file_get_contents($file);
$userdata = unserialize($data);

if (!$userdata[$user]) {
  echo 'ERROR' . "\n";
  return;
}

if ($userdata[$user]['passwd'] !== $pass_old) {
  echo 'ERROR' . "\n";
  return;
}

$userdata[$user] = [
  'login' => $user,
  'passwd' => $pass_new
];

$data = serialize($userdata);
file_put_contents($file, $data);

header('Location: index.html');
echo 'OK' . "\n";
