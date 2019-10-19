<?php

if (!is_file('list.csv')) {
  echo '{}';
  return;  
}

try {
  $filedata = file_get_contents('list.csv');
} catch (Exception $e) {
  echo '{}';
  return;
}

if ($filedata === false) {
  echo '{}';
  return;
}

$data = [];
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
  $data[$id] = $text;
}

if (empty($data)) {
  echo '{}';
  return;
}

echo json_encode($data);
