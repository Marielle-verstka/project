<?php require_once 'html/functions.php'; ?>
<?php $cart = getCart($products); ?>
<?php $wishlist = getWishlist($products); ?>

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
        <!-- Cart && Wishlist-->
        <div class="b-header__items">
            <div class="header__cart">
                <?php if ($cart->total_amount > 0): ?>
                    <a class="header__cart-link" href="<?php echo "?r=cart"; ?>"><?php echo $cart->total_amount; ?></a>
                <?php else: ?>
                    <span class="header__cart-link">0</span>
                <?php endif; ?>
            </div>
            <div class="header__wl">
                <?php if (isset($wishlist->total_amount) && ($wishlist->total_amount > 0)): ?>
                    <a class="header__wl-link" href="<?php echo  "?r=wishlist"; ?>"><?php echo $wishlist->total_amount; ?></a>
                <?php else: ?>
                    <span class="header__wl-link">0</span>
                <?php endif; ?>
            </div>
            <div class="header__sign">
                <a class="header__sign-link" href="<?php echo "?r=sign"; ?>">SIGNUP</a>
            </div>
            <div class="header__sign">
                <a class="header__sign-link" href="<?php echo "?r=login"; ?>">LOGIN</a>
            </div>
            <?php if (isset($_SESSION['user']) && $_SESSION['user'] == 'true'): ?>
            <form class="" action="" method="post">
                <input type="submit" name="unset_login" class="header__sign header__sign-link" value="Выйти!">
            </form>
            <?php endif; ?>
        </div>
        <!-- Menu -->
        <nav class="menu">
            <ul class="b-menu">
                <?php echo buildMenu($nav_menu); ?>
            </ul>
        </nav>
    </div>
</header>
<!-- ***CONTENT*** -->
<div class="b-content">
    <?php require_once 'html/content.php'?>
</div>
<div class="b-footer">
    <footer class="footer">
        <div class="footer__item">
            <?php echo viewLastUserVisited($timeLastVisited, $pageLastVisited); ?>
        </div>
    </footer>
</div>
</body>
</html>