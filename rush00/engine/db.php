<?php

function connect($host) {
  $ret = mysqli_connect($host, 'root', 'root1', 'rush00');

  if (!$ret) {
    return false;
  }

  return $ret;
}

$DB = connect('192.168.99.104');

if (!$DB) {
  $host = system('docker-machine ip PHP');
  $DB = connect($host);
}

if (!$DB) {
  echo 'Ошибка: Невозможно установить соединение с MySQL.' . PHP_EOL;
  exit;
}

mysqli_set_charset($DB, 'utf8');
