#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

// Is this part of SPL?
// $arr = preg_split('/ /', $argv[1], -1, PREG_SPLIT_NO_EMPTY);
// IDK. So we could use
$arr = explode(' ', $argv[1]);
$arr = array_filter($arr, function ($x) { return $x !== ''; });
$str = implode($arr, ' ') . "\n";
echo $str;
