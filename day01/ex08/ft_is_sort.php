#!/usr/bin/php
<?php

function ft_is_sort($arr) {
  $save = $arr;
  sort($arr);
  $res = array_diff_assoc($arr, $save);
  if (count($res) > 0) {
    return 0;
  }

  return 1;
}
