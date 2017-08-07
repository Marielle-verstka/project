<?php require_once 'html/functions.php' ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<!-- TopBar. Место для контактной информации и кнопок соцсетей-->
<div class="b-topbar">
    <div class="topbar">
        <span class="topbar__location">Днепр</span>
        <a href="tel:+380988888888" class="topbar__telephone">+380988888888</a>
    </div>
</div>
<!-- ***HEADER*** -->
<header class="header">
    <!-- Здесь еще должна быть корзина и кнопка авторизации -->
    <div class="header__line">
        <!-- Logo -->
        <a href="#" class="header__logo">LOGO
            <img src="" alt="" class="logo">
        </a>
        <!-- Menu -->
        <nav class="menu" style="font-size: 16px; color: #000;">
            <ul class="b-menu">
                <?php require_once "html/menu.php"; ?>
            </ul>
        </nav>
    </div>
    <div class="header__line header__line-search">
        <!-- Строка и иконка каталог-->
        <div class="category">
            <span class="category__icon">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </span>
            <span class="category__caption">Каталог товаров</span>
        </div>
        <!-- Поиск -->
        <form action="" method="get" id="search" class="search">
            <input type="text" name="q" autocomplete="off" class="search__input" placeholder="Что вы хотите купить?">
            <button type="submit" class="search__button">Найти</button>
        </form>
    </div>
</header>
<!-- ***CONTENT*** -->
<div class="b-content">
    <!-- ***ASIDE (left)*** -->
    <aside>
        <!-- Категории -->
       <div class="b-categories"> <!-- Исправить ховеры -->
            <?php require_once "html/categories.php"; ?>
        </div>
    </aside>
    <!-- ***MAIN*** -->
    <div class="container">
        <main class="b-main">
            <!-- Вывод товара -->
            <?php require_once "html/products.php"; ?>
        </main>
        <!-- Новинки-->
        <section class="b-novelties">
            <div class="novelties">
                <h3 class="novelties__title">Новинки</h3>
                <?php require_once "html/novelties.php"; ?>
            </div>
        </section>
    </div>
</div>
</body>
</html>