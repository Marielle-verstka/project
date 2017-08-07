<?php if (is_array($menu)):?>
    <?php foreach ($menu as $item): ?>
        <?php if (($item->visible) && ($item->menu_id == 1)): ?>
            <li class="menu__item">
                <a href="<?php echo "?r=page&id=$item->id" ?>" class="menu__link"><?php echo $item->name ?></a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>