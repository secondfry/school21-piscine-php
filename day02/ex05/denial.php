#!/usr/bin/php
<?php

if ($argc < 3) {
  return;
}

if (!is_file($argv[1])) {
  return;
}

$file = fopen($argv[1], 'rb');
if (!$file) {
  return;
}

$line = fgets($file);
if ($line === false) {
  return;
}

$line = trim($line);
$headers = explode(';', $line);
$key_name = $argv[2];
$key_pos = array_search($key_name, $headers);

if ($key_pos === false) {
  return;
}

foreach ($headers as $header) {
  $$header = [];
}

while (1) {
  $line = fgets($file);
  if ($line === false) {
    break;
  }

  $line = trim($line);
  $data = explode(';', $line);
  $key = $data[$key_pos];
  foreach ($headers as $pos => $header) {
    $$header = array_merge($$header, [
      $key => $data[$pos]
    ]);
  }
}

fclose($file);

while (1) {
  echo 'Enter your command: ';
  $line = fgets(STDIN);
  if ($line === false) {
    return;
  }

  $line = trim($line);
  eval($line);
}
