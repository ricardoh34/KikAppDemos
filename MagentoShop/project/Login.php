<?php 
/***
 * Login
 */

$win = new SDPanel();
$win -> setCaption("Login");

$table = new Table();
$table -> setRowsStyle("80dip;100%");
$table -> setClass("table.background");

$lbl_home = new Label();
$lbl_home -> setClass("labelTitlelogin");
$lbl_home -> setCaption("Log in to Magento-Gecko");

$table_form = new Table();
$table_form -> setRowsStyle("76dip;76dip");
$table_form -> setClass("tableforms");

$email 	= new InputEmail();
$email -> setClass("inputForm");
$email -> setInviteMessage("E-Mail");

$pass 	= new InputText();
$pass -> setClass("inputForm");
$pass -> setInviteMessage("Password");
$pass -> setIsPassword(true);

$btn_save = new Button();
$btn_save -> setCaption("LOG IN");
$btn_save -> setClass("buttonform");
$btn_save -> onTap(login());

$btn_register = new Button();
$btn_register -> setCaption("Register");
$btn_register -> setClass("buttonform");
$btn_register -> onTap(register());

$table_form -> addControl($email,1,1);
$table_form -> addControl($pass,2,1);

$table -> addControl($lbl_home,1,1,1,1,"Center","Middle");
$table -> addControl($table_form,2,1);

$table_button = new Table();
$table_button -> addControl($btn_save,1,1);
$table_button -> addControl($btn_register,2,1);

$table -> addControl($table_button,3,1);

$win -> addControl($table);
$win -> Render();

function login(){
	
	if($email == null){
		echo "E-Mail is a required field.";
	}else{
		if($pass == null){
			echo "Password is a required field.";
		}else{
			ProgressIndicator::Show();
			$url = "http://www.demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=login";
			$httpClient_post = new httpClient();
				
			$inputMetodo = new InputText();
			$inputMetodo -> setValue("login");
			
			//$httpClient_post -> addVariable('metodo',$inputMetodo);
			$httpClient_post -> addVariable('email',$email);
			$httpClient_post -> addVariable('password',$pass);
			
			$result = $httpClient_post -> Execute('POST',$url);
			
			$id_user = new InputText();	
			$SDTUser = array("customerToken"=>DataType::Character(50));
			
			Data::FromJson($SDTUser,$result);
				
			$id_user = $SDTUser['customerToken'];
			ProgressIndicator::Hide();
			
			if($id_user != ""){
				StorageAPI::Set("token",$id_user);
				AndroidAction::GoHome();
			}else{
				echo "User invalid.";
			}
		}
	}		
}

function register(){
	$win -> Open("register");
}

?>