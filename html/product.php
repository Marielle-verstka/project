<?php $product = getProduct($products,$id); ?>
<?php //setProductsHistoryCookie(); ?>

<?php //var_dump($products[$id]); ?>
<main class="product-page">
	<h1 class="product-page__title"><?php echo $product->name; ?></h1>
	<div class="product-page__b-img">
		<img class="product-page__img" alt="" src="files/img/hw.jpg">
		<?php if (is_array($product->images) && (count($product->images) > 1)): ?>
			<div class="product-page__images-small">
				<?php foreach ($product->images as $image) :?>
					<?php if ($image->id != $product->image->id): ?>
						<div class="product-page__b-img-small">
							<img class="product-page__img-small" alt="" src="files/img/hw.jpg">
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="product-page__more">
		<div class="product-page__more__price">
			<?php if (count($product->variants) > 1): ?>
				<select class="preview__select">
					<?php foreach ($product->variants as $item) :?>
						<option value=""><?php echo $item->sku, "&nbsp; &nbsp; &nbsp;", ceil($item->price); ?> грн.</option>
					<?php endforeach; ?>
				</select>
			<?php else: ?>
				<span class="preview__price"><?php echo ceil($product->variant->price); ?> грн.</span>
			<?php endif; ?>
		</div>
		<span class="product-page__more__description"><?php echo $product->description; ?></span>
	</div>
	<div class="product-page__buy">
		<form method="get" id="form_buy" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]);?>">
			<label for="amount">Количество:</label>
			<input type="number" name="amount" id="amount" value="1" step="1" min="1" max="<?= $product->variant->stock ?>">
			<input type="hidden" name="id" value="<?= $product->id; ?>">
		</form>
		<button type="submit" value="submit" form="form_buy" class="buy">
			<span>Купить</span>
		</button>
	</div>
</main>