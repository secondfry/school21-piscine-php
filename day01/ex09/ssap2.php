#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

function ft_split($var) {
  $arr = explode(' ', $var);
  $arr = array_filter($arr, function ($x) { return $x !== ''; });
  sort($arr);
  return $arr;
}

function categorize($c) {
  if ($c > 96 && $c < 123)
    return 0;
  if ($c > 47 && $c < 58)
    return 1;
  return 2;
}

function ft_kek_compare($a, $b) {
  $len_a = strlen($a);
  $len_b = strlen($b);
  $a = strtolower($a);
  $b = strtolower($b);
  $i = 0;
  while ($i < $len_a && $i < $len_b) {
    if ($a[$i] === $b[$i]) {
      $i++;
      continue;
    }
      
    $qa = ord($a[$i]);
    $qb = ord($b[$i]);

    $cqa = categorize($qa);
    $cqb = categorize($qb);

    if ($cqa !== $cqb) {
      return $cqa - $cqb;
    }

    return $qa - $qb;
  }

  return $len_a - $len_b;
}

$i = 1;
$res = [];
while ($i < $argc) {
  $res = array_merge(ft_split($argv[$i]), $res);
  $i++;
}
usort($res, ft_kek_compare);

$len = count($res);
$i = 0;
while ($i < $len) {
  echo $res[$i] . "\n";
  $i++;
}
