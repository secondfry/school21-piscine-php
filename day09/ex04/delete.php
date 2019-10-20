<?php

$id = $_POST['id'] ?? '';

if ($id === '') {
  return;
}

function read_data() {
  if (!is_file('list.csv')) {
    return [];
  }

  try {
    $filedata = file_get_contents('list.csv');
  } catch (Exception $e) {
    return [];
  }

  if ($filedata === false) {
    return [];
  }

  $ret = [];
  $lines = explode("\n", $filedata);
  foreach ($lines as $line) {
    $piece = explode(';', $line);
    if (count($piece) < 2) {
      continue;
    }

    $id = array_shift($piece);
    if (empty($id)) {
      continue;
    }

    $text = implode(';', $piece);
    $ret[$id] = $text;
  }

  return $ret;
}

$data = read_data();

$ret = '';
foreach ($data as $k => $v) {
  if ($k === '') {
    continue;
  }
  
  if ($k == $id) {
    continue;
  }

  $ret .= $k . ';' . $v . "\n";
}

file_put_contents('list.csv', $ret);
