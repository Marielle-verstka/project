<?php
    if(!empty($cart->items)):
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
        <div class="b-order">
            <p class="order__title">Для совершения покупки заполните форму.</p>
            <p class="order__info">Наша компания делает всё возможное для защиты вашей конфиденциальной информации.</p>
            <form action="" method="post" class="order">
                <input type="text" class="order__input" name="firstname" placeholder="Имя" pattern="^[А-Яа-яЁё]+$" required>
                <input type="text" class="order__input" name="lastname" placeholder="Фамилия" pattern="^[А-Яа-яЁё]+$" required>
                <input type="email" class="order__input" name="email" placeholder="Email" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
                <input type="tel" class="order__input" name="phone" placeholder="Номер телефона" required>
                <input type="text" class="order__input" name="city" placeholder="Город" required>
                <input type="text" class="order__input" name="region" placeholder="Область">
                <input type="text" class="order__input" name="address" placeholder="Адрес" required>
                <textarea name="message" class="order__input order__input-textarea" placeholder="Комментарий к заказу"></textarea>
                <input type="submit" name="make_order" class="order__submit" value="Оформить заказ">
            </form>
        </div>
    <?php else:?>
        <p class="page_empty">Ваша корзина пуста</p>
    <?php endif;?>
