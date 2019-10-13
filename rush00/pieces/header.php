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
    <?php
      $res = get_all($DB, 'category');
      $category = url_get_category();
      if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
          $flag = $category == $row['short'] ? ' active' : '';
          ?><a class="nav_link<?=$flag?>" href="/index.php?action=category&category=<?=$row['short']?>"><?=$row['name']?></a><?php
        }
      }
    ?>
  </navbar>
</header>
<div class="some_block" id="main">
