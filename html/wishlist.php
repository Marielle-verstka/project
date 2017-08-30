<div class="container">
    <main class="b-main">
        <!-- Вывод товара -->
        <?php if (!empty($wishlist->items)): ?>
            <?php foreach ($wishlist->items as $product): ?>
                <?php if ($product->visible): ?>
                    <div class="b-preview">
                        <div class="preview">
                            <span class="preview__date"><?php echo date('m.d.y',strtotime($product->created)); ?></span>
                            <a href="<?php echo "?r=product&id=$product->id"; ?>" class="preview__img">
                                <img src="files/img/hw.jpg" alt="">
                            </a>
                            <div class="preview__info">
                                <a href="<?php echo "?r=product&id=$product->id"; ?>" class="preview__title"><?php echo $product->name; ?></a>
                                <span class="preview__article"></span>
                                <?php if (count($product->variants) > 1): ?>
                                    <select class="preview__select">
                                        <?php foreach ($product->variants as $item): ?>
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
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else:?>
            <p class="page_empty">Ваш список желаний пуст</p>
        <?php endif;?>
    </main>
</div>