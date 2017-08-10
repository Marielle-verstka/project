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
<!-- ***ASIDE (left)*** -->
<aside>
	<!-- Категории -->
	<div class="b-categories"> <!-- Исправить ховеры -->
		<?php getCategories($results); ?>
	</div>
</aside>
<!-- ***MAIN*** -->
<div class="container">
	<main class="b-main">
		<!-- Вывод товара -->
		<?php if($products): ?>
			<?php foreach ($products as $product): ?>
			<?php if ($product->visible): ?>
				<div class="b-preview">
					<div class="preview">
						<span class="preview__date"><?php echo date('m.d.y',strtotime($product->created)); ?></span>
						<a href="<?php echo "?r=product&id=$product->id" ?>" class="preview__img">
							<img src="files/img/hw.jpg" alt="">
						</a>
						<div class="preview__info">
							<a href="<?php echo "?r=product&id=$product->id" ?>" class="preview__title"><?php echo $product->name; ?></a>
							<span class="preview__article"></span>
							<?php if (count($product->variants) > 1): ?>
								<select class="preview__select">
									<?php foreach ($product->variants as $item) :?>
										<option value=""><?php echo $item->sku, "&nbsp; &nbsp; &nbsp;", ceil($item->price); ?> грн.</option>
									<?php endforeach; ?>
								</select>
							<?php else: ?>
								<span class="preview__price"><?php echo ceil($product->variant->price); ?> грн.</span>
							<?php endif; ?>
							<div class="preview__control">
								<div class="preview__b-more">
									<span class="preview__more">Подробнее</span>
								</div>
								<button type="button" class="buy">
									<span>Купить</span>
								</button>
								<!-- Рopup для подробнее-->
								<div class="preview__b-popup">
									<div class="preview__popup">
										<!--                                                <div class="popup__img">-->
										<!--                                                    <img src="">-->
										<!--                                                </div>-->
										<div class="popup__info">
											<span class="popup__content">В комплекте 1 машинка</span>
											<span class="popup__content">Масштаб машинки 1:64</span>
											<span class="popup__content">Возраст: для детей от 3 лет</span>
											<span class="popup__content">Спешите! Осталось <span class="<?php echo $product->variant->stock == 0 ? "red" : "green"?>"> <?php echo ceil($product->variant->stock); ?></span> шт.</span> <!--Если товара 0, то делаем цвет вывода красным, иначе зеленым -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</main>
	<!-- Новинки-->
	<section class="b-novelties">
		<div class="novelties">
			<h3 class="novelties__title">Новинки</h3>
			<?php require_once "html/novelties.php"; ?>
		</div>
	</section>