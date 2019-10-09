#!/usr/bin/php
<?php

if ($argc < 3) {
  return;
}

$data = [];
$i = 2;
while ($i < $argc) {
  $parts = explode(':', $argv[$i]);

  if (count($parts) != 2) {
    $i++;
    continue;
  }

  $data[$parts[0]] = $parts[1];
  $i++;
}

if (array_key_exists($argv[1], $data)) {
  echo $data[$argv[1]] . "\n";
}
