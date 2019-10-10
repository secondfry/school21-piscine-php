#!/usr/bin/php
<?php

if ($argc < 2) {
  return;
}

function download_image($domain, $url) {
  $arr = explode('/', $url);
  $filename = array_pop($arr);
  $filepath = $domain . '/' . $filename;

  $out = fopen($filepath, 'wb');
  if (!$out) {
    return;
  } 

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_FILE, $out);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_URL, $url);

  curl_exec($curl);
  curl_close($curl);
}

function check_scheme($domain, $image) {
  $arr = explode('://', $image);
  if (count($arr) !== 2) {
    return false;
  }

  download_image($domain, $image);
  return true;
}

function download_images($scheme, $domain, $prefix, $matches) {
  foreach ($matches[1] as $image) {
    if (check_scheme($domain, $image)) {
      continue;
    }

    $pos = strpos($image, '//');
    if ($pos === 0) {
      $url = $scheme . ':' . $image;
      download_image($domain, $url);
      continue;
    }

    $pos = strpos($image, '/');
    if ($pos === 0) {
      $url = $scheme . '://' . $domain . $image;
      download_image($domain, $url);
      continue;
    }

    $url = $prefix . '/' . $image;
    download_image($domain, $url);
  }
}

$arr = explode('://', $argv[1]);
if (count($arr) !== 2) {
  return;
}
$scheme = $arr[0];
$arr = explode('/', $arr[1]);
$domain = $arr[0];
array_pop($arr);
$prefix = $scheme . '://' . implode('/', $arr);

if (!file_exists($domain)) {
  mkdir($domain);
}

if (file_exists($domain) && !is_dir($domain)) {
  return;
}

$res = file_get_contents($argv[1]);
preg_match_all('/<img .*?src="([^"]+)"/', $res, $matches);
download_images($scheme, $domain, $prefix, $matches);
preg_match_all('/<img .*?src=\'([^\']+)\'/', $res, $matches);
download_images($scheme, $domain, $prefix, $matches);
preg_match_all('/<img .*?src=([^"\' ]+)/', $res, $matches);
download_images($scheme, $domain, $prefix, $matches);
