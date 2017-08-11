<?php
    header('Content-Type: text/html; charset=utf-8');
    ini_set('display_errors',true);
    error_reporting(E_ALL);
    require_once 'data/menu.php';
    require_once 'data/categories.php';
    require_once 'data/products.php';



    /*** MENU ***/

    function createMenu($menu_array) /*Создание меню*/
    {
        if (is_array($menu_array)) {
            $nav_menu = array();
            foreach ($menu_array as $key => $value) {
                if (($value->visible) && ($value->menu_id == 1)) {
                    //$nav_menu_item = $value->id;
                    $nav_menu[$value->id] = $value;
                }
            }
        }
        uasort($nav_menu, "cmp"); /*Сортируем массив в зависимости от position*/
        return $nav_menu;
    }
    $nav_menu=createMenu($menu);


    function cmp($a, $b) /*Функция сортировки массива*/
    {
        $a = $a->position;
        $b = $b->position;
        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? +1 : -1;
    }


    function buildMenu($menu_arr) /*Отрисовка меню*/
    {
        if (is_array($menu_arr)) {
            $tree = "";
            foreach ($menu_arr as $key => $value) {
                if ($value->url == '') {
                    $tree .= '<li class="menu__item"><a href='.$_SERVER['SCRIPT_NAME'].' class="menu__link '.higlightMenu($value).'">'.$value->name.'</a></li>';
                } else {
                    $tree .='<li class="menu__item"><a href='."?r=page&id=$value->id".' class="menu__link '.higlightMenu($value).'">'.$value->name.'</a></li>';
                }
            }
        } else {
            return null;
        }
        return $tree;
    }


    function higlightMenu($menu_arr) /*Подсветка активной страницы*/
    {
        if (isset($_GET['r'])) {
            if (($_GET['r'] == 'page') && ($_GET['id'] == $menu_arr->id)) {
                return 'menu__link-active';
            }
        }
        return null;
    }



    /*** CATEGORIES***/

    function getCategoriesTree($categories,$parent_id = 0)
    {
        if ($categories) {
            $results = array(); /*Строим объект, содержащий каталог надлежащей иеархии*/
            foreach ($categories as $key => $category) { /*Перебираем исходный массив*/
                if ($category->parent_id == $parent_id) {
                    $result = new stdClass(); /*Создаём объект для текущей категории*/
                    $result = $category; /*Копируем категорию из исходного массива в вновь созданный объект*/
                    unset($categories[$key]); /*Удаляем текущий элемент из исходного массива, так как он не может быть потомком себя. Данная процедура существенно сократит количество итераций цикла*/
                    if ($category->id != $parent_id) {
                        $result->subcategories = getCategoriesTree($categories,$category->id);
                    }
                    if (empty($result->subcategories)) { /*если субкатегорий нет, удаляем поле */
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
            <ul class="categories list<?php echo $count; ?>">
                <?php foreach ($categories as $category): ?>
                    <?php $count = $category->level_depth; ?>
                    <?php if ($category->visible): /*важная проверка, которая позволит выводит только включенные категории на сайте*/ ?>
                        <li class="categories__item categories__item<?php echo $category->level_depth; ?>"><a class="categories__link" href="<?php echo "?r=category&id=$category->id"; ?>"><?php echo $category->name; ?></a>
                            <?php if (!empty($category->subcategories)): /*проверяем есть ли подкатегории и вызываем заново функцию для вывода*/?>
                                <?php getCategories($category->subcategories);  /*передаем в функцию уже массив обьектов покатегорий*/?>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php }


    function getPage($page,$page_id) {
        if ($page_id) {
            return $page[$page_id];
        }
    }


    function getProduct($products,$id) {
        if ($id) {
            return $products[$id];
        }
    }


    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        // $data = (int)$data; //явное преобразование в число
        return $data;
    }


    /*** COOKIE ***/

    function getTimeLastVisited()
    {
        if (isset($_COOKIE['timeLastVisited'])) {
            setcookie('timeLastVisited', date('d-m-Y:H-i-s'), time() + 3600 * 24 * 7, '/');
            $timeLastVisited = $_COOKIE['timeLastVisited'];
        } else {
            setcookie('timeLastVisited', date('d-m-Y:H-i-s'), time() + 3600 * 24 * 7, '/');
            $timeLastVisited = '';
        }
        return $timeLastVisited;
    }
    $timeLastVisited = getTimeLastVisited();


    function getPageLastVisited()
    {
        $ref = 'http://localhost'.$_SERVER['REQUEST_URI'];
        if (isset($_COOKIE['pageLastVisited'])) {
            setcookie('pageLastVisited', $ref, time() + 3600 * 24 * 7, '/');
            $pageLastVisited = $_COOKIE['pageLastVisited'];
        } else {
            setcookie('pageLastVisited', $ref, time() + 3600 * 24 * 7, '/');
            $pageLastVisited = '';
        }
        return $pageLastVisited;

    }
    $pageLastVisited = getPageLastVisited();


    function viewLastUserVisited($timeLastVisited, $pageLastVisited) /*Отрисовка информации о последних действиях пользователя в футере*/
    {
        $lastUserMovies = "";
        if (!empty($timeLastVisited)) {
            $lastUserMovies .= "<span>Ваш последний визит на сайт: " . $timeLastVisited . "</span>";
        }
        if (!empty($pageLastVisited)) {
            $lastUserMovies .= "<span>Вернуться к <a href='" . $pageLastVisited . "' title='последняя просмотренная страница'>последней просмотренной странице</a></span>";
        }
        return $lastUserMovies;
    }


    if(isset($_GET['amount']) && isset($_GET['id'])) {
        $cart = array();
        $product_id = cleanInput($_GET['id']);
        $amount = cleanInput($_GET['amount']);
        if(isset($_COOKIE['cart'])) {
            $cart = unserialize($_COOKIE['cart']);
        }
        $cart[$product_id] = $amount;
        setcookie('cart',serialize($cart),time()+(60*60*24*30),'/');
        $path = '?r=product&id='.$_GET['id'];
        header("Location: $path");
    }


    function getCart($products) {
        $cart_products = array();
        $total_amount = 0;
        $total_price = 0;
        $cart = new stdClass();
        if (isset($_COOKIE['cart'])) {
            $ids = unserialize($_COOKIE['cart']);
            foreach ($ids as $id=>$amount) {
                $cart_products[$id] = getProduct($products,$id);
                $cart_products[$id]->amount = $amount;
                $total_price += $cart_products[$id]->variant->price*$amount;
                $total_amount += $amount;
            }
            $cart->items = $cart_products;
        }
        $cart->total_price = $total_price;
        $cart->total_amount = $total_amount;
        return $cart;
    }


    function countGoodsCart() {
        if (isset($_COOKIE['cart'])) {
            $countGoodsCart = 0;
            $array = unserialize($_COOKIE['cart']);
            foreach ($array  as $id => $amount) {
                $countGoodsCart += $amount;
            }
        } else {
            $countGoodsCart = '0';
        }
        return $countGoodsCart;
    }


    if (isset($_GET['wl_product'])) {
        $wl_id = cleanInput($_GET['wl_product']);
        $wishlist = array();
        if (isset($_COOKIE['wishlist'])) {
            $wishlist = unserialize($_COOKIE['wishlist']);
            if (!in_array($wl_id, $wishlist)) {
                $wishlist[] = $wl_id;
            }
        } else {
            $wishlist[] = $wl_id;
        }
        setcookie('wishlist',serialize($wishlist),time()+86400*30,'/');
        $path = '?r=product&id='.$_GET['wl_product'];
        header("Location: $path");
    }


    function getWishlist($products) {
        if (isset($_COOKIE['wishlist'])) {
            $wishlist_products = unserialize($_COOKIE['wishlist']);
            $wishlist = array();
            foreach ($wishlist_products as $product) {
                $wishlist[] = getProduct($products, $product);
            }
            return $wishlist;
        } else {
            return null;
        }
    }


    function setProductsHistoryCookie()
    {
        $max_visited_products = 5;// Максимальное число хранимых товаров в истории
        $expire = time() + 86400 * 30; // Время жизни - 30 дней
        if (!empty($_COOKIE['browsed_products'])) {
            $browsed_products = explode(',', $_COOKIE['browsed_products']);
    // Удалим текущий товар, если он был
            if (($exists = array_search($_GET['id'], $browsed_products)) !== false) {
                unset($browsed_products[$exists]);
            }
        }
    // Добавим текущий товар
        $browsed_products[] = $_GET['id'];
        $cookie_val = implode(',', array_slice($browsed_products, -$max_visited_products, $max_visited_products));
        setcookie("browsed_products", $cookie_val, $expire, "/");
    }
    //var_dump($_COOKIE['browsed_products']);