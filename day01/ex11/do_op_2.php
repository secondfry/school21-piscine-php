#!/usr/bin/php
<?php

if ($argc != 2) {
  echo 'Incorrect Parameters' . "\n";
  return;
}

function display_error() {
  echo 'Syntax Error' . "\n";
}

function do_op($op, $oppos, $str) {
  $n1 = substr($str, 0, $oppos);
  $n2 = substr($str, $oppos + 1);

  if (!is_numeric($n1) || !is_numeric($n2))
    return false;

  switch ($op) {
    case '+':
      echo $n1 + $n2;
      break;
    case '-':
      echo $n1 - $n2;
        break;
    case '*':
      echo $n1 * $n2;
        break;
    case '/':
      echo $n1 / $n2;
        break;
    case '%':
      echo $n1 % $n2;
        break;
  }

  echo "\n";
  return true;
}

function find_op($op, $str) {
  $offset = 0;
  while (1) {
    $oppos = strpos($str, $op, $offset);
    if ($oppos === false) {
      return false;
    }
    if (do_op($op, $oppos, $str)) {
      return true;
    }
    $offset = $oppos + 1;
  }
}

$arr = explode(' ', $argv[1]);
$arr = array_filter($arr, function ($x) { return $x !== ''; });
$str = implode('', $arr);

if (
  find_op('+', $str)
  || find_op('-', $str)
  || find_op('*', $str)
  || find_op('/', $str)
  || find_op('%', $str)
) {
  return;
}

display_error();
