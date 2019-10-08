#!/usr/bin/php
<?php

function ft_split($var) {
  $arr = explode(' ', $var);
  $arr = array_filter($arr);
  sort($arr);
  return $arr;
}
