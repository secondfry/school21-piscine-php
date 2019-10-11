<?php

function auth($login, $passwd) {
  if (!$login || !$passwd) {
    return FALSE;
  }

  $path = '../private';
  $file = $path . '/passwd';

  if (!file_exists($file)) {
    return FALSE;
  }

  $data = file_get_contents($file);
  $userdata = unserialize($data);

  if (!$userdata[$login]) {
    return FALSE;
  }

  $hash = hash('sha512', $passwd);
  if ($userdata[$login]['passwd'] !== $hash) {
    return FALSE;
  }

  return TRUE;
}
