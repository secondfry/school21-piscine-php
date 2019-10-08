#!/usr/bin/php
<?php

function display_error($data) {
  echo '\'' . $data . '\' is not a number' . "\n";
}

function display($data) {
  echo 'The number ' . $data . ' is ';
  if (intval($data, 0) % 2 === 0) {
    echo 'even' . "\n";
    return;
  }

  echo 'odd' . "\n";
  return;
}

while (1) {
  echo 'Enter a number: ';
  $data = fgets(STDIN);
  if ($data === false) {
    return;
  }

  $input = trim($data);
  if (!is_numeric($input)) {
    display_error($input);
    continue;
  }

  if (floor($input) != $input) {
    display_error($input);
    continue;
  }

  display($input);
}
