#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

$data = [];

while (1) {
  $line = fgets(STDIN);
  if ($line === false) {
    break;
  }

  $line_data = explode(';', $line);
  if (count($line_data) != 4) {
    continue;
  }

  if (!is_numeric($line_data[1])) {
    continue;
  }

  $login = $line_data[0];
  $grade = intval($line_data[1]);
  $peer = $line_data[2];

  if (!array_key_exists($login, $data)) {
    $data[$login] = [
      'grades_by_peers' => [],
      'grades_by_moulinette' => [],
    ];
  }

  if ($peer === 'moulinette') {
    $data[$login]['grades_by_moulinette'][] = $grade;
  } else {
    $data[$login]['grades_by_peers'][] = $grade;
  }
}

function ft_average($data) {
  $data = array_reduce($data, function ($acc, $cur) {
    return array_merge($acc, $cur['grades_by_peers']);
  }, []);
  $len = count($data);
  $sum = array_sum($data);
  $res = $sum / $len;
  echo $res . "\n";
}

function ft_average_user($data, $output) {
  $ret = [];
  foreach ($data as $k => $v) {
    $data = array_merge($v['grades_by_peers']);
    $len = count($data);
    $sum = array_sum($data);
    $ret[$k] = $sum / $len;
  }
  uksort($ret, function ($a, $b) { return strcmp($a, $b); });

  if ($output === true) {
    foreach ($ret as $k => $v) {
      echo $k . ':' . $v . "\n";
    }
  }

  return $ret;
}

function ft_moulinette_variance($data) {
  $flat = ft_average_user($data, false);
  array_walk($flat, function (&$v, $k, $data) {
    $v = $v - array_sum($data[$k]['grades_by_moulinette']) / count($data[$k]['grades_by_moulinette']);
  }, $data);

  foreach ($flat as $k => $v) {
    echo $k . ':' . $v . "\n";
  }
}

switch ($argv[1]) {
  case 'average':
  case 'moyenne':
    ft_average($data);
    break;
  case 'moyenne_user':
  case 'average_user':
    ft_average_user($data, true);
    break;
  case 'moulinette_variance':
  case 'ecart_moulinette':
    ft_moulinette_variance($data);
    break;
}
