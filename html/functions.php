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
		foreach($menu_array as $key => $value) {
			if(($value->visible) && ($value->menu_id == 1)) {
//                $nav_menu_item = $value->id;
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
	if(isset($_GET['r'])) {
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
		<ul class="categories list<?php echo $count; ?>">
			<?php foreach ($categories as $category): ?>
				<?php $count = $category->level_depth; ?>
				<?php if($category->visible): /*важная проверка, которая позволит выводит только включенные категории на сайте*/ ?>
					<li class="categories__item categories__item<?php echo $category->level_depth; ?>"><a class="categories__link" href="<?php echo "?r=category&id=$category->id"; ?>"><?php echo $category->name; ?></a>
						<?php if(!empty($category->subcategories)): /*проверяем есть ли подкатегории и вызываем заново функцию для вывода*/?>
							<?php getCategories($category->subcategories);  /*передаем в функцию уже массив обьектов покатегорий*/?>
						<?php endif; ?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
<?php }


function getPage($page,$page_id) {
	if($page_id) {
		return $page[$page_id];
	}
}
function getProduct($products,$id) {
	if($id) {
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

//if ($_COOKIE['time']) {
//   echo 'Ваше время посещения: ', $_COOKIE['time'];
//} else {
//    setcookie('time', date('d-m-Y:H-i-s'), time()+3600, '/');
//}



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
$timeLastVisited = 'getTimeLastVisited';
$timeLastVisited();

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
$pageLastVisited = 'getPageLastVisited';
$pageLastVisited();

function viewLastUserVisited($timeLastVisited, $pageLastVisited) /*Отрисовка информации о последних действиях пользователя в футере*/
{
	$lastUserMovies = "";
	if (!empty($timeLastVisited)) {
		$lastUserMovies .= "<span>Ваш последний визит на сайт: " . getTimeLastVisited() . "</span>";
	}
	if (!empty($pageLastVisited)) {
		$lastUserMovies .= "<span>Вернуться к <a href='" . getPageLastVisited() . "' title='последняя просмотренная страница'>последней просмотренной странице</a></span>";
	}
	return $lastUserMovies;
}

//Добавление/обновление товара в корзине
if(isset($_GET) && isset($_GET['amount'])) {
	$id = cleanInput($_GET['id']);
	$amount = cleanInput($_GET['amount']);
	$cart = array();
	if(isset($_COOKIE['cart'])) {
		$cart = unserialize($_COOKIE['cart']);
		$cart[$id] = $amount;
		setcookie('cart',serialize($cart),time()+3600,'/');
	} else {
		$cart[$id] = $amount;
		setcookie('cart',serialize($cart),time()+3600,'/');
	}
}
//print_r(unserialize($_COOKIE['cart']));
function getCart($products) {
	if (isset($_COOKIE['cart'])) { /*Если куки с именем карт существует, то...*/
		$cart_products = unserialize($_COOKIE['cart']); /*Превращаем строку в массив*/
		$products_cart = array();
		foreach ($cart_products as $id => $amount) { /*Перебор массива карт*/
			$products_cart[$id] = getProduct($products, $id); /*Каждому ид присваиваем соответсвующий продукт из массива продуктс*/
			$products_cart[$id]->amount = $amount; /*Добавляем поле количество*/
		}
		return $products_cart;
	} else {
		return null;
	}
	}

function countGoodsCart() {
	if(isset($_COOKIE['cart'])) {
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
