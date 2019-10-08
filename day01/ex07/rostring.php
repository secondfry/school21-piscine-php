#!/usr/bin/php
<?php

if ($argc < 2)
{
  return;
}

$arr = explode(' ', $argv[1]);
$arr = array_filter($arr, function ($x) { return $x !== ''; });
array_push($arr, array_shift($arr));
$str = implode(' ', $arr);
echo $str . "\n";
