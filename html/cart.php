<?php
    $cart = getCart($products);
    //var_dump($cart);
    if(!empty($cart->items)):
        $total_cart = 0;
        foreach ($cart->items as $item): ?>
            <div class="cart__b-item">
                <img class="cart__item__img" alt="" src="files/img/hw.jpg">
                <a href="<?php echo "?r=product&id=$item->id"; ?>" class="cart__item__link"><?php echo $item->name; ?></a>
                <span class="cart__item__price"><?php echo ceil($item->variant->price); ?> грн.</span>
                <input type="number" name="amountCart" id="amountCart" value="<?php echo $item->amount; ?>" step="1" min="1" max="<?php echo $item->variant->stock; ?>">
                <?php $priceThisGood = ceil($item->variant->price) * $item->amount; ?>
                <span class="cart__item__totalprice"><?php echo $priceThisGood; ?> грн.</span>
            </div>
        <?php endforeach;?>
        <span class="cart__totals">Общее количество товара: <span class="cart__totals-sum"><?php echo $cart->total_amount; ?> шт.</span></span>
        <span class="cart__totals">Общая сумма Вашей покупки: <span class="cart__totals-sum"><?php echo ceil($cart->total_price); ?> грн.</span></span>
    <?php else:?>
        <p>Ваша корзина пуста</p>
    <?php endif;?>
