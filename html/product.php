<?php $product = getProduct($products,$id); ?>

<main class="product-page">
    <h1 class="product-page__title"><?php echo $product->name; ?></h1>
    <div class="product-page__b-img">
        <img class="product-page__img" alt="" src="files/img/hw.jpg">
        <?php if (is_array($product->images) && (count($product->images) > 1)): ?>
            <div class="product-page__images-small">
                <?php foreach ($product->images as $image): ?>
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
                    <?php foreach ($product->variants as $item): ?>
                        <option value=""><?php echo $item->sku, "&nbsp; &nbsp; &nbsp;", ceil($item->price); ?> грн.</option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <span class="preview__price"><?php echo ceil($product->variant->price); ?> грн.</span>
            <?php endif; ?>
        </div>
        <?php if($product->description):?>
            <div class="product-page__more__description">
                <?php echo $product->description; ?>
            </div>
        <?php endif;?>
    </div>
    <div class="product-page__buy">
        <form method="post" id="form_buy" action="">
            <input type="hidden" name="id" value="<?php echo $product->id; ?>">
            <label for="amount">Количество:</label>
            <input type="number" name="amount" id="amount" value="1" step="1" min="1" max="<?php echo $product->variant->stock; ?>">
        </form>
        <button type="submit" value="submit" form="form_buy" class="buy buy__product">
            <span>Купить</span>
        </button>
        <form method="post" id="form_wishlist" action="">
            <input type="hidden" name="r" value="<?php echo $_GET['r'] ?>">
            <input type="hidden" name="wl_product" value="<?php echo $product->id; ?>">
        </form>
        <button type="submit" value="submit" form="form_wishlist" class="buy buy__product submit_wl">
            <span>Добавить в список желаний</span>
        </button>
    </div>
</main>
<div class="b-last-product">
    <h3 class="last-product__title">Вы также просматривали:</h3>
    <?php $view_products = getBrowsedProducts($products); ?>
        <?php if (!empty($view_products)): ?>
            <?php foreach ($view_products as $product): ?>
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
        <?php endif;?>
</div>