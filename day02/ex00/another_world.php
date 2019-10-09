#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

$str = preg_replace('/[ \t]+/', ' ', $argv[1]);
$str = trim($str);
echo $str . "\n";
