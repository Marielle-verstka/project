<?php
	$cart = getCart($products);

if(isset($cart)):
	$total_cart = 0;
	foreach($cart as $goodId => $goodInfo): ?>
		<div class="cart__b-item">
			<img class="cart__item__img" alt="" src="files/img/hw.jpg">
			<a href="<?php echo "?r=product&id=$goodInfo->id"; ?>" class="cart__item__link"><?php echo $goodInfo->name; ?></a>
			<span class="cart__item__price"><?php echo ceil($goodInfo->variant->price); ?> грн.</span>
			<input type="number" name="amountCart" id="amountCart" value="<?= $goodInfo->amount; ?>" step="1" min="1" max="<?= $goodInfo->variant->stock; ?>">
			<?php $priceThisGood = ceil($goodInfo->variant->price) * $goodInfo->amount; ?>
			<?php $total_cart += $priceThisGood; ?>
			<span class="cart__item__totalprice"><?= $priceThisGood; ?> грн.</span>
		</div>
	<?php endforeach;?>
	<span class="cart__totals">Общая сумма Вашей покупки: <span class="cart__totals-sum"><?= $total_cart; ?> грн.</span></span>
<?php else:?>
<p>Ваша корзина пуста</p>
<?php endif;?>

