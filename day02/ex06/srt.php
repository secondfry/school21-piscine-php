#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

if (!is_file($argv[1])) {
  return;
}

$file = fopen($argv[1], 'rb');
if (!$file) {
  return;
}

$data = [];
$obj = [
  'id' => '',
  'timestamp' => '',
  'data' => '',
];
$keys = [
  0 => 'id',
  1 => 'timestamp',
  2 => 'data',
];

$i = 0;

while (1) {
  $line = fgets($file);
  if ($line === false) {
    break;
  }

  $line = trim($line);
  $key = $keys[$i];

  $obj[$key] .= $line;
  if ($i === 0 || $i === 1) {
    $i++;
  }

  if ($line === '') {
    $i = 0;
    $data[] = $obj;
    $obj = [
      'id' => '',
      'timestamp' => '',
      'data' => '',
    ];
  }
}

$data[] = $obj;
$len = count($data) - 1;
usort($data, function ($a, $b) { return strcmp($a['timestamp'], $b['timestamp']); });

$i = 0;

foreach ($data as $piece) {
  echo sprintf(
    '%d' . "\n" . '%s' . "\n" . '%s' . "\n",
    $i,
    $piece['timestamp'],
    $piece['data']
  );

  if ($i !== $len) {
    echo "\n";
  }

  $i++;
}
