<?php if($products): ?>
    <?php foreach ($products as $product): ?>
    <?php if ($product->visible): ?>
        <div class="novelty">
            <span class="novelty__title"><?php echo $product->name; ?></span>
            <span class="novelty__price"><?php echo ceil($product->variant->price); ?> грн.</span>
            <div class="novelty__b-popup">
                <div class="novelty__popup">
                    <div class="novelty__popup-img"><img src="" alt=""></div>
                    <div class="novelty__popup-content">
                        <a href="<?php echo "?r=product&id=$product->id"; ?>" class="novelty__popup-title"><?php echo $product->name; ?></a>
                        <span class="novelty__popup__price">Стоимость: <?php echo ceil($product->variant->price); ?> грн.</span>
                        <span class="novelty__popup__stock">Количество товаров на складе: <?php echo ceil($product->variant->stock); ?> шт.</span>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>