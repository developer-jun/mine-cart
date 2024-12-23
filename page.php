<?php
require('config.php');
require('includes/helpers.php');


use \Libraries\Page;
use \Libraries\Renderer;
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


require('./includes/config.php');


$page = new Page(
  template_dir: SITE_TEMPLATES,
  
);

// header
$page->renderPartial(SITE_INCLUDES.'/header.php');

//content


// footer
$page->renderPartial(SITE_INCLUDES.'/footer.php');

  
  //require(SITE_INCLUDES.'/helpers/form.helper.php');
  require(SITE_INCLUDES.'/_login.php');
  require(SITE_INCLUDES.'/header5.php');
  ?>

  <div class="container pt-3">
    <div class="row">
      <div class="col-md-8">
        <div class="main-col">
          <div class="block">
            <div class="title-block">
              <h1>User Login</h1>
              <span>Secure Form</span>
            </div>  
            <?= $signinform->showNotifications() ?>
            <form class="login-form" role="form" method="post" action="">
              <?= $signinform->renderCsrfField() ?>              
              <div class="form-group">
                <?= $signinform->renderLabel('Email Address') ?>
                <?= $signinform->renderField(['name' => "email", 'placeholder' => "Email Address", 'value' => $signinform->email, 'type' => "email"]) ?>                
              </div>              
              <div class="form-group">
                <?= $signinform->renderLabel('Password') ?>
                <?= $signinform->renderField(['name' => "password", 'placeholder' => "", 'value' => $signinform->password, 'type' => 'password']) ?>
              </div>              
              <?= $signinform->renderButton(['label' => "Login", 'class'=>'btn btn-primary']) ?>                
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <?php require('./includes/sidebar.php') ?>
      </div>
    </div>
  </div><!-- /.container -->


  <?php
  require('./includes/footer5.php');
