<?php 
/***
 * Navigation Style - Slide.
 * Main screen.
 */

$win = new SDPanel();
$win -> setCaption("Menu");

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

$table_account = new Table();
$table_account -> setClass("tableMenuOption");
$table_account -> setColumnsStyle("20%;80%");
$table_account -> setInvisibleMode("Collapse Space");

$img_account = new Image();
$img_account -> setImage("img/Android/hdpi/ic_product.png");
$img_account -> setClass("imagemenu");

$lbl_account = new Label();
$lbl_account -> setClass("labelMenuOption");
$lbl_account -> setCaption("Account");

$table_account -> onTap(account());
$table_account -> addControl($img_account,1,1,1,1,"Center","Middle");
$table_account -> addControl($lbl_account,1,2,1,1,"Left","Middle");

$table_transactions = new Table();
$table_transactions -> setClass("tableMenuOption");
$table_transactions -> setColumnsStyle("20%;80%");
$table_transactions -> setInvisibleMode("Collapse Space");

$img_transactions = new Image();
$img_transactions -> setImage("img/Android/hdpi/ic_category.png");
$img_transactions -> setClass("imagemenu");

$lbl_transactions = new Label();
$lbl_transactions -> setClass("labelMenuOption");
$lbl_transactions -> setCaption("Transactions");

$table_transactions -> onTap(transactions());
$table_transactions -> addControl($img_transactions,1,1,1,1,"Center","Middle");
$table_transactions -> addControl($lbl_transactions,1,2,1,1,"Left","Middle");

$table_settings = new Table();
$table_settings -> setClass("tableMenuOption");
$table_settings -> setColumnsStyle("20%;80%");

$img_settings = new Image();
$img_settings -> setImage("img/Android/hdpi/ic_settings.png");
$img_settings -> setClass("imagemenu");

$lbl_settings = new Label();
$lbl_settings -> setClass("labelMenuOption");
$lbl_settings -> setCaption("Settings");

$table_settings -> onTap(settings());
$table_settings -> addControl($img_settings,1,1,1,1,"Center","Middle");
$table_settings -> addControl($lbl_settings,1,2,1,1,"Left","Middle");

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
$table -> addControl($table_account,3,1);
$table -> addControl($table_transactions,4,1);
$table -> addControl($table_login,5,1);
$table -> addControl($table_settings,6,1);
$table -> addControl($table_logout,7,1);

$token = new InputText(80);
$win -> addControl($table);

function ClientStart(){
	$token = StorageAPI::Get("token");
	if($token == ""){
		$table_account -> setVisible(false);
		$table_transactions -> setVisible(false);
		$table_settings -> setVisible(false);
		$table_logout -> setVisible(false);
		$table_login -> setVisible(true);
	}else{
		$table_login -> setVisible(false);
		$table_account -> setVisible(true);
		$table_transactions -> setVisible(true);
		$table_settings -> setVisible(true);
		$table_logout -> setVisible(true);		
	}	
}

function Slide(){	
	$token = StorageAPI::Get("token");
	$win -> Open("Home",$token);		
}

function home(){
	$win -> Open("Home",$token);
}

function transactions(){
	$win -> Open("Transactions",$token);
}

function account(){
	$win -> Open("Account",$token);
}

function settings() {
	$win -> Open("Settings",$token);
}

function login(){
	$win -> Open("Login");
}

function logout(){	
	StorageAPI::Remove("token");
	AndroidAction::GoHome();
}
?>