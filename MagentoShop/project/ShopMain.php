<?php 
/***
 * Navigation Style - Slide.
 * Main screen.
 */

$win = new SDPanel();
$win -> setCaption(" ");

$table = new Table();
$table -> setClass("table.Menu");
$table-> setRowsStyle("150dip;50dip");


//Menu Header
$table_header = new Table();
$table_header -> setClass("table.Header");
$table_header -> setColumnsStyle("15%;85%");
$table_header -> setRowsStyle("50dip;90dip");

$headerImage = new Image();
$headerImage->setClass("image.MenuHeader");
$headerImage->setImage("img/userIcon.png");

$headerLabel = new InputText();
$headerLabel->setClass("input.titleWhite");
$headerLabel->setReadOnly(true);
 
$table_header->addControl($headerImage,2,1,1,1,"Left","Middle");
$table_header->addControl($headerLabel,2,2,1,1,"Left","Middle");


$table_home = new Table();
$table_home -> setClass("tableMenuOption");
$table_home -> setColumnsStyle("20%;80%");

$img_home = new Image();
$img_home -> setImage("img/ic_home.png");
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
$img_products -> setImage("img/ic_product.png");
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
$img_cat -> setImage("img/ic_category.png");
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
$img_login -> setImage("img/ic_login.png");
$img_login -> setClass("imagemenu");

$lbl_login = new Label();
$lbl_login -> setCaption("Login");
$lbl_login -> setClass("labelMenuOption");

$table_login -> onTap(login());
$table_login -> addControl($img_login,1,1,1,1,"Center","Middle");
$table_login -> addControl($lbl_login,1,2,1,1,"Left","Middle");

$table_UserData = new Table();
$table_UserData -> setClass("tableMenuOption");
$table_UserData -> setColumnsStyle("20%;80%");

$img_UserData = new Image();
$img_UserData -> setImage("img/ic_mydata.png");
$img_UserData -> setClass("imagemenu");

$lbl_datos = new Label();
$lbl_datos -> setCaption("My Data");
$lbl_datos -> setClass("labelMenuOption");

$table_UserData -> onTap(misdatos());
$table_UserData -> addControl($img_UserData,1,1,1,1,"Center","Middle");
$table_UserData -> addControl($lbl_datos,1,2,1,1,"Left","Middle");

$table_mycart = new Table();
$table_mycart -> setClass("tableMenuOption");
$table_mycart -> setColumnsStyle("20%;80%");

$img_mycart = new Image();
$img_mycart -> setImage("img/ic_cart.png");
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
$img_logout -> setImage("img/ic_logout.png");
$img_logout -> setClass("imagemenu");

$lbl_logout = new Label();
$lbl_logout -> setCaption("Logout");
$lbl_logout -> setClass("labelMenuOption");

$table_logout -> onTap(logout());
$table_logout -> addControl($img_logout,1,1,1,1,"Center","Middle");
$table_logout -> addControl($lbl_logout,1,2,1,1,"Left","Middle");

//Add controls to menu
$table -> addControl($table_header,1,1);
$table -> addControl($table_home,2,1);
$table -> addControl($table_products,3,1);
$table -> addControl($table_cat,4,1);
$table -> addControl($table_login,5,1);
$table -> addControl($table_UserData,6,1);
$table -> addControl($table_mycart,7,1);
$table -> addControl($table_logout,8,1);

$cat = new InputNumeric(); //..

$win -> addControl($table);
$win -> Render();

function Slide(){	
	$token = new InputText(80);
	//$userName = new InputText(80);
	$win -> Open("Home");		
}

function home(){
	$win -> Open("Home");
}

function list_prod(){
	$win -> Open("ProductList",$cat);
}

function categorias(){
	$win -> Open("Categories");
}

function login(){
	$win -> Open("Login");
}

function misdatos(){
	$win -> Open("UserData",$token);
}

function mycart(){		
	$win -> Open("ShoppingCart",$token);
}

function logout(){	
	ProgressIndicator::Show();
	$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=logout&customerToken=".$token;
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
		
	$token   = StorageAPI::Get("token");
	//$userName = StorageAPI::Get("userName");
	
	if($token == null){
		$table_UserData -> setVisible(false);
		$table_mycart -> setVisible(false);
		$table_logout -> setVisible(false);
		$table_login -> setVisible(true);
		$headerLabel->setValue("Magento KikApp Store");
	}else{
		$table_login -> setVisible(false);
		$table_UserData->setVisible(true);
		$table_mycart -> setVisible(true);
		$table_logout -> setVisible(true);
		$headerLabel = StorageAPI::Get("userName");
			
	}	
}

?>