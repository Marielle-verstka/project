<?php require_once 'html/functions.php'; ?>
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
		<nav class="menu">
			<ul class="b-menu">
				<?= buildMenu($nav_menu); ?>
			</ul>
		</nav>
		<!-- Cart -->
		<a class="header__cart" href="<?= "?r=cart"; ?>">
			<span class="header__cart-link"><?= countGoodsCart(); ?></span>
		</a>
	</div>
</header>
<!-- ***CONTENT*** -->
<div class="b-content">
	<?php require_once 'html/content.php'?>
</div>
</div>
<div class="b-footer">
	<footer class="footer">
		<div class="footer__item">
			<?= viewLastUserVisited($timeLastVisited, $pageLastVisited); ?>
		</div>
	</footer>
</div>
</body>
</html>