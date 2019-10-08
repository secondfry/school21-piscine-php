#!/usr/bin/php
<?php

function ft_split($var) {
  $arr = explode(' ', $var);
  $arr = array_filter($arr, function ($x) { return $x !== ''; });
  sort($arr);
  return $arr;
}
