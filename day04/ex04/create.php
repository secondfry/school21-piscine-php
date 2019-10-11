<?php

$user = $_POST['login'];
$pass = $_POST['passwd'];
$salt = $_POST['submit'];

if (!$user || !$pass || !$salt || $salt !== 'OK') {
  echo 'ERROR' . "\n";
  return;
}

$pass = hash('sha512', $pass);

$path = '../private';
$file = $path . '/passwd';

if (!file_exists($path)) {
  mkdir($path);
}

$userdata = [];

if (file_exists($file)) {
  $data = file_get_contents($file);
  $userdata = unserialize($data);
}

if ($userdata[$user]) {
  echo 'ERROR' . "\n";
  return;
}

$userdata[$user] = [
  'login' => $user,
  'passwd' => $pass
];

$data = serialize($userdata);
file_put_contents($file, $data);

header('Location: index.html');
echo 'OK' . "\n";
