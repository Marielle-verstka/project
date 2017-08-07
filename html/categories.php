<?php
function getCategoriesTree($categories,$parent_id = 0)
{
    if ($categories) {
        $results = array(); /*Строим объект, содержащий каталог надлежащей иеархии*/
        foreach ($categories as $key => $category) { /*Перебираем исходный массив*/
            if ($category->parent_id == $parent_id) {
                $result = new stdClass(); /*Создаём объект для текущей категории*/
                $result = $category; /*Копируем категорию из исходного массива в вновь созданный объект*/
                unset($categories[$key]); /*Удаляем текущий элемент из исходного массива, так как он не может быть потомком себя. Данная процедура существенно сократит количество итераций цикла*/
                if ($category->id != $parent_id) $result->subcategories = getCategoriesTree($categories,$category->id);
                if(empty($result->subcategories)) { /*если субкатегорий нет, удаляем поле */
                    unset($result->subcategories);
                }
                $results[$category->id] = $result; /*вместо очередного, присваиваем значение ид*/
                unset($result);
            }
        }
        return $results;
    }
}
$results = getCategoriesTree($categories_data);
function getCategories($categories) {
    if ($categories): /*проверка лишней не бывает*/
        static $count = 0; /*создаем индетификатор для списков разных уровней*/ ?>
        <ul class="categories list<?php echo $count ?>">
            <?php foreach ($categories as $category):
                $count = $category->level_depth;
                if($category->visible): /*важная проверка, которая позволит выводит только включенные категории на сайте*/ ?>
                    <li class="categories__item categories__item<?php echo $category->level_depth ?>"><a class="categories__link" href="<?php echo "?r=category&id=$category->id" ?>"><?php echo $category->name ?></a>
                        <?php if(!empty($category->subcategories)):/*проверяем есть ли подкатегории и вызываем заново функцию для вывода*/
                            getCategories($category->subcategories); /*передаем в функцию уже массив обьектов покатегорий*/
                        endif; ?>
                    </li>
                <?php endif;
            endforeach; ?>
        </ul>
    <?php endif;
}
getCategories($results);
?>