#!/usr/bin/php
<?php

function ft_split($var) {
  $arr = explode(' ', $var);
  sort($arr);
  return $arr;
}
