<?php 
/***
 * Navigation Style - Slide.
 * Main screen.
 */

$win = new SDPanel();
$win -> setCaption(" ");

$table = new Table();
$table -> setClass("tableMenu");


$table_home = new Table();
$table_home -> setClass("tableMenuOption");
$table_home -> setColumnsStyle("20%;80%");

$img_home = new Image();
$img_home -> setImage("img/Android/hdpi/ic_home.png");
$img_home -> setClass("imagemenu");

$lbl_home = new Label();
$lbl_home -> setClass("labelMenuOption");
$lbl_home -> setCaption("Home");

$table_home -> onTap(home());
$table_home -> addControl($img_home,1,1,1,1,"Center","Middle");
$table_home -> addControl($lbl_home,1,2,1,1,"Left","Middle");

$table_products = new Table();
$table_products -> setClass("tableMenuOption");
$table_products -> setColumnsStyle("20%;80%");

$img_products = new Image();
$img_products -> setImage("img/Android/hdpi/ic_product.png");
$img_products -> setClass("imagemenu");

$lbl_list = new Label();
$lbl_list -> setClass("labelMenuOption");
$lbl_list -> setCaption("Products");

$table_products -> onTap(list_prod());
$table_products -> addControl($img_products,1,1,1,1,"Center","Middle");
$table_products -> addControl($lbl_list,1,2,1,1,"Left","Middle");

$table_cat = new Table();
$table_cat -> setClass("tableMenuOption");
$table_cat -> setColumnsStyle("20%;80%");

$img_cat = new Image();
$img_cat -> setImage("img/Android/hdpi/ic_category.png");
$img_cat -> setClass("imagemenu");

$lbl_categorias = new Label();
$lbl_categorias -> setClass("labelMenuOption");
$lbl_categorias -> setCaption("Categories");

$table_cat -> onTap(categorias());
$table_cat -> addControl($img_cat,1,1,1,1,"Center","Middle");
$table_cat -> addControl($lbl_categorias,1,2,1,1,"Left","Middle");

$table_login = new Table();
$table_login -> setClass("tableMenuOption");
$table_login -> setColumnsStyle("20%;80%");
$table_login -> setInvisibleMode("Collapse Space");

$img_login = new Image();
$img_login -> setImage("img/Android/hdpi/ic_login.png");
$img_login -> setClass("imagemenu");

$lbl_login = new Label();
$lbl_login -> setCaption("Login");
$lbl_login -> setClass("labelMenuOption");

$table_login -> onTap(login());
$table_login -> addControl($img_login,1,1,1,1,"Center","Middle");
$table_login -> addControl($lbl_login,1,2,1,1,"Left","Middle");

$table_mydata = new Table();
$table_mydata -> setClass("tableMenuOption");
$table_mydata -> setColumnsStyle("20%;80%");

$img_mydata = new Image();
$img_mydata -> setImage("img/Android/hdpi/ic_mydata.png");
$img_mydata -> setClass("imagemenu");

$lbl_datos = new Label();
$lbl_datos -> setCaption("My Data");
$lbl_datos -> setClass("labelMenuOption");

$table_mydata -> onTap(misdatos());
$table_mydata -> addControl($img_mydata,1,1,1,1,"Center","Middle");
$table_mydata -> addControl($lbl_datos,1,2,1,1,"Left","Middle");

$table_mycart = new Table();
$table_mycart -> setClass("tableMenuOption");
$table_mycart -> setColumnsStyle("20%;80%");

$img_mycart = new Image();
$img_mycart -> setImage("img/Android/hdpi/ic_cart.png");
$img_mycart -> setClass("imagemenu");

$lbl_cart = new Label();
$lbl_cart -> setCaption("My Cart");
$lbl_cart -> setClass("labelMenuOption");

$table_mycart -> onTap(mycart());
$table_mycart -> addControl($img_mycart,1,1,1,1,"Center","Middle");
$table_mycart -> addControl($lbl_cart,1,2,1,1,"Left","Middle");

$table_logout = new Table();
$table_logout -> setClass("tableMenuOption");
$table_logout -> setColumnsStyle("20%;80%");

$img_logout = new Image();
$img_logout -> setImage("img/Android/hdpi/ic_logout.png");
$img_logout -> setClass("imagemenu");

$lbl_logout = new Label();
$lbl_logout -> setCaption("Logout");
$lbl_logout -> setClass("labelMenuOption");

$table_logout -> onTap(logout());
$table_logout -> addControl($img_logout,1,1,1,1,"Center","Middle");
$table_logout -> addControl($lbl_logout,1,2,1,1,"Left","Middle");

$table_space = new Table();

$table -> addControl($table_space,1,1);
$table -> addControl($table_home,2,1);
$table -> addControl($table_products,3,1);
$table -> addControl($table_cat,4,1);
$table -> addControl($table_login,5,1);
$table -> addControl($table_mydata,6,1);
$table -> addControl($table_mycart,7,1);
$table -> addControl($table_logout,8,1);

$cat = new InputNumeric(); //..

$win -> addControl($table);
$win -> Render();

function Slide(){	
	$token = new InputText(80);
	$win -> Open("Home");		
}

function home(){
	$win -> Open("Home");
}

function list_prod(){
	$win -> Open("products_list",$cat);
}

function categorias(){
	$win -> Open("categories_list");
}

function login(){
	$win -> Open("Login");
}

function misdatos(){
	$win -> Open("mydata",$token);
}

function mycart(){		
	$win -> Open("shopping_cart",$token);
}

function logout(){	
	ProgressIndicator::Show();
	$url = "http://www.demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=logout&customerToken=".$token;
	$hc = new httpClient();
	$rs = $hc -> Execute("GET",$url);
	ProgressIndicator::Hide();
	
	StorageAPI::Remove("token");
	AndroidAction::GoHome();		
}

/**
 * Function ClientStart.
 * This function is execute in event user and is the first event in execute
 */ 
function ClientStart(){
	$token = StorageAPI::Get("token");
	if($token == null){
		$table_mydata -> setVisible(false);
		$table_mycart -> setVisible(false);
		$table_logout -> setVisible(false);
		$table_login -> setVisible(true);
	}else{
		$table_login -> setVisible(false);
		$table_mydata -> setVisible(true);
		$table_mycart -> setVisible(true);
		$table_logout -> setVisible(true);		
	}	
}

?>