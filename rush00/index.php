<?php

session_start();

$action = $_GET['action'];

switch ($action) {
  default:
    require_once 'pages/index.php';
}
