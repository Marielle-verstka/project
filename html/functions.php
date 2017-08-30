<?php
    header('Content-Type: text/html; charset=utf-8');
    ini_set('display_errors',true);
    error_reporting(E_ALL);
    session_name(md5($_SERVER['HTTP_USER_AGENT']));
    session_start();
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


    if(isset($_POST['amount']) && isset($_POST['id'])) {
        $cart = array();
        $product_id = cleanInput($_POST['id']);
        $amount = cleanInput($_POST['amount']);
        if(isset($_COOKIE['cart'])) {
            $cart = unserialize($_COOKIE['cart']);
        }
        $cart[$product_id] = $amount;
        setcookie('cart',serialize($cart),time()+(60*60*24*30),'/');
        /* $path = '?r=product&id='.$_POST['id'];
        header("Location: $path"); */
        header("Location: ".$_SERVER['REQUEST_URI']);
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


    if (isset($_POST['wl_product'])) {
        $wl_id = cleanInput($_POST['wl_product']);
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
        header("Location: ".$_SERVER['REQUEST_URI']);
    }


    function getWishlist($products) {
        $wishlist  = new stdClass();
        $wishlist_products = array();
        $total_amount = 0;
        if (isset($_COOKIE['wishlist'])) {
            $ids = unserialize($_COOKIE['wishlist']);
            foreach ($ids as $product) {
                $wishlist_products[] = getProduct($products, $product);
                $total_amount++;
            }
            $wishlist->items = $wishlist_products;
            $wishlist->total_amount = $total_amount;
            return $wishlist;
        } else {
            return null;
        }
    }


    if (isset($_GET['r']) && isset($_GET['id']) && $_GET['r'] == 'product') {
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


    function getBrowsedProducts($products) {
        $view_products = array();
        if (isset($_COOKIE['browsed_products'])) {
            $ids = explode(',', $_COOKIE['browsed_products']);
            foreach ($ids as $product) {
                $view_products[] = getProduct($products, $product);
            }
            //var_dump($view_products);
            return $view_products;
        } else {
            return null;
        }
    }



    /*** ORDER ***/

    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['make_order'])) {
        $user_info = clearInputs($_POST);
        //Если папки files/order нет, создадим её
        if (!file_exists('files/order')) {
            mkdir('files/order',0777);
        }

        $cart = getCart($products);
        $put_txt = '';
        $put_txt .= "========= Заказ № ".rand(1,1000)."========="."\n";
        $put_txt .= 'Имя:'.$user_info['firstname']."\n";
        $put_txt .= 'Фамилия:'.$user_info['lastname']."\n";
        $put_txt .= 'Email:'.$user_info['email']."\n";
        $put_txt .= 'Телефон:'.$user_info['phone']."\n";
        $put_txt .= 'Город:'.$user_info['city']."\n";
        $put_txt .= 'Область'.$user_info['region']."\n";
        $put_txt .= 'Адрес доставки:'.$user_info['address']."\n";
        $put_txt .= 'Комментарий:'.$user_info['message']."\n";
        $put_txt .= 'Список товаров:'."\n";

        $i = 1;
        foreach ($cart->items as $item) {
            $put_txt .= $i++ ." ".$item->name. "- ". $item->amount . " шт ". $item->amount*$item->variant->price . " грн"."\n";
        }

        file_put_contents('files/order/orders.txt', $put_txt, FILE_APPEND | LOCK_EX);

        setcookie('cart','',time()-(60*60*24*30),'/');
        header("Location:".$_SERVER['REQUEST_URI']);
    }


    function clearInputs($data) /* Функция для очистки массива входящих данных */
    {
        if (is_array($data)) {
            $new_data = array();
            foreach ($data as $key => $value) {
                $new_data[$key] = cleanInput($value);
            }
            return $new_data;
        }
        return false;
    }


    if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['make_signup'])) {

        $error_singup = array();

        $pattern_name = '/^[а-яё][-_\.,а-яё0-9]{1,}\s*[а-яё][-_\.,а-яё0-9]*$/iu';
        $user_name = mb_strtolower(trim($_POST['user_name']));

        if (!preg_match($pattern_name, $user_name, $matches_name)) {
            $error_singup['name'] = 'Имя может состоять только из букв русского алфавита и цифр, а также знаков - "-_,.". И должно быть длиннее 2-х символов.';
        }


        $pattern_login = '/^[a-z][-a-z0-9]{3,}$/i';
        $user_login = mb_strtolower(trim($_POST['user_login']));

        if (!preg_match($pattern_login, $user_login, $matches_login)) {
            $error_singup['login'] = 'Логин может состоять только из букв латинского алфавита, цифр и дефиса. Логин должен быть не короче 4 знаков';
        } else {
            $handler = fopen('adm.txt', 'r');
            while($line=fgets($handler)) {
                $arr[] = explode('|||', $line);
            }
            foreach ($arr as $key => $user) {
                if ($user[1] == $user_login) {
                    $error_singup['login'] = 'Пользователь с таким логином уже существует';
                }
            }
            fclose($handler);
        }


        $pattern_email = '/^(?!\.) #исключаем точку в начале имени пользователя
                            ((?!(\.\.)|(\-\-))[-_\'.a-z0-9]){2,} #исключаем двойные точки и дефисы в имени пользователя
                            (?<!\.)@ #исключаем точку в конце имени пользователя
                            [-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,}$/ix';
        $user_email = mb_strtolower(trim($_POST['user_email']));

        if (!preg_match($pattern_email, $user_email, $matches_email)) {
            $error_singup['email'] = 'Введите корректный email';
        } else {
            $handler = fopen('adm.txt', 'r');
            while($line=fgets($handler)) {
                $arr[] = explode('|||', $line);
            }
            foreach ($arr as $key => $user) {
                if ($user[2] == $user_email) {
                    $error_singup['email'] = 'Пользователь с таким email уже существует';
                }
            }
            fclose($handler);
        }


        $pattern_password = '/^[a-z0-9\/-\?\*\!]{8,}$/i';
        $user_password = trim($_POST['user_password']);

        if (!preg_match($pattern_password, $user_password, $matches_password)) {
            $error_singup['password'] = 'Пароль может состоять только из букв латинского алфавита и цифр, а также знаков - "-/?*!". И должно быть не короче 8-ми символов.';
        }
        if (count($error_singup) == 0) {//or ! in if
            addUser($user_name, $user_login, $user_email, $user_password); /*При успешной валидации формы, добавляем пользователя в базу*/
            if (isset($_POST['user_agree']) && $_POST['user_agree'] === 'on') { /*Если пользователь подтвердил заполнить меня*/
                $sign_date = array();
                $sign_date['name'] = $user_name;
                $sign_date['login'] = $user_login;
                $sign_date['email'] = $user_email;

                setcookie('about', serialize($sign_date), time() + (86400 * 30), '/');
                //header("Location: " . $_SERVER['REQUEST_URI']);
                header("Location: " . "index.php?r=login");

            }
        }
    }


    function addUser($name, $login, $email, $password)
    {
        $user_info = "";
        $passw = password_hash($password, PASSWORD_DEFAULT);
        $user_info .= $name."|||".$login."|||".$email."|||".$passw."|||"."\n";
        file_put_contents('adm.txt', $user_info, FILE_APPEND | LOCK_EX);

    }


    if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['make_login'])) {
        $pattern_login = '/^[a-z][-a-z0-9]{3,}$/i';
        $user_login = mb_strtolower(trim($_POST['user_login']));
        $pattern_password =  '/^[a-z0-9\/-\?\*\!]{8,}$/i';
        $user_password = trim($_POST['user_password']);
        if (preg_match($pattern_login, $user_login, $matches) && preg_match($pattern_password, $user_password, $matches_password)) {//or ! in if
            getUser($user_login, $user_password);
        }
    }


    function getUser($user_login, $user_password) {
        $handler = fopen('adm.txt', 'r');
        while($line=fgets($handler)) {
            $arr[] = explode('|||', $line);
        }
        foreach ($arr as $key => $user) {
            if ($user[1] == $user_login) {
                if (password_verify($user_password, $user[3])) {
                    $_SESSION['user'] = true;
                } else {
                    $_SESSION['user'] = false;
                }
            } else {
                $_SESSION['user'] = false;
            }
        }
        fclose($handler);
    }


    if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['unset_login'])) { /*Прервать сессию при клике на кнопку ВЫЙТИ*/
        //session_start();
        unset($_SESSION['user']);
        //setcookie('about', '', time() - (86400 * 30), '/');
    }
