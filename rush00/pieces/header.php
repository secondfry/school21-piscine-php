<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/static/style.css">
<link rel="stylesheet" href="/static/desktop.css" media="(min-width: 576px)">
</head>
<body>
<div id="container">
<header id="header">
  <div class="some_block" id="header_top">
    <div id="title">Книжный магазин «Уютный раш»</div>
    <a href="/index.php?action=basket">Корзина</a>
  </div>
  <navbar class="navigation">
    <a class="nav_link" href="/">Главная</a>
    <a class="nav_link" href="/index.php?action=view&page=about">О нас</a>
    <a class="nav_link" href="/index.php?action=category&page=all">Все книги</a>
    <a class="nav_link" href="/index.php?action=view&page=contacts">Контакты</a>
  </navbar>
  <navbar class="navigation">
    <a class="nav_link" href="/index.php?action=category&category=python">Python</a>
    <a class="nav_link" href="/index.php?action=category&category=cpp">C/C++</a>
    <a class="nav_link" href="/index.php?action=category&category=java">Java</a>
  </navbar>
</header>
<div class="some_block" id="main">