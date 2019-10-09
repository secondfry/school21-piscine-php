#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

if (!file_exists($argv[1])) {
  return;
}

$ret = '';
$data = file_get_contents($argv[1]);
$start = strpos($data, '<a ');
$ret .= strsub($data, 0, $start);
$data = strsub($data, $start);
$end = strpos($data, '>');

$editable = strsub($data);

$regexp_title_esclosed = '/<a.*?(?:title="([^"]+)").*?<\/a>/';
$regexp_title_plain = '/<a.*?(?:title=([^" ]+)).*?<\/a>/';
$regexp_inside_link