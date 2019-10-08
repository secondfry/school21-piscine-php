#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

function ft_split($var) {
  $arr = explode(' ', $var);
  $arr = array_filter($arr);
  sort($arr);
  return $arr;
}

$i = 1;
$res = [];
while ($i < $argc) {
  $res = array_merge(ft_split($argv[$i]), $res);
  $i++;
}
sort($res);

$len = count($res);
$i = 0;
while ($i < $len) {
  echo $res[$i] . "\n";
  $i++;
}
