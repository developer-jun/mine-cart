<?php
require('config.php');
require('includes/helpers.php');

use \Libraries\DB;
use \Libraries\ToastNotifications;
//use \Libraries\Messages;

// DB related services
use \Services\CustomerServices;
use \Services\ProductServices;
use \Services\CartServices;

//use \Models\Product;
//use \Models\Customer;

use \Views\PageView;
use \Views\Components\CartComponent;
use \Views\Components\ProductComponent;


$page_view = new PageView();
$page_view->addScriptFile('public/js/custom-script.js');
$page_view->addScriptFile('https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js');

// Step 1:
$db = new DB(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

// Step 2:
$product_services = new ProductServices($db);

// Step 3:
$customer_services = new CustomerServices($db);
$customer_data = $customer_services->checkCustomer();

// Step 4:
$cart_services = new CartServices($db, $customer_data->number ?? 0);

// Step 5
$product_view = new ProductComponent();
$cart_view = new CartComponent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {	
	if(isset($_POST['prod_id']) 
		&& $product = $product_services->getProduct($_POST['prod_id'])) {
		$cart_services->addToCart($product, intval($_POST['quantity']));
	} else {
		if(isset($_POST['item_id']) 
			&& $cart_item = $cart_services->getCartItem($_POST['item_id'])) {
			$cart_services->updateCart($cart_item, intval($_POST['quantity']));
		}
	}
}

if(isset($_GET['cart_action']) && $_GET['cart_action'] == "remove") {
	$cart_services->deleteCartItem($_GET['id']);
}

if (isset($_GET['action']) && $_GET['action'] == "checkout") {
	if ($cart_services->getNumberOfRecords() > 0) {
		header("Location: ". CHECKOUT); // change the file name if you need
	} 
	
	$cart_services->error = "Your cart is currently empty!";	
}

$toast_notification = new ToastNotifications();

