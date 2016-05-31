<?php 
/***
 * Login
 */

$win = new SDPanel();
$win -> setCaption("Login");

$table = new Table();
$table -> setRowsStyle("100%");
$table -> setWidth("100%");
$table -> setHeight("100%");
$table -> setClass("table.white");

$table_form = new Table();
$table_form -> setRowsStyle("76dip;76dip");
$table_form -> setClass("table.forms");


$username 	= new InputEmail();
$username -> setClass("input.form");
$username -> setInviteMessage("E-Mail");

$password 	= new InputText();
$password -> setClass("input.form");
$password -> setInviteMessage("Password");
$password -> setIsPassword(true);



$label= new Label();
$label-> setCaption("OR");
$label-> setClass("label.big");

$btn_save= new Button();
$btn_save-> setCaption("Log in");
$btn_save-> onTap(login());
$btn_save-> setClass("button.blue");

$btn_register= new Button();
$btn_register-> setCaption("Sign up");
$btn_register-> onTap(register());
$btn_register-> setClass("button.blue");

$table_form -> addControl($username,1,1);
$table_form -> addControl($password,2,1);

$table_button = new Table();

$table_button -> addControl($btn_save,1,1);
$table_button -> addControl($label,2,1,1,1,"Center","Middle");
$table_button -> addControl($btn_register,3,1);

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);

function login(){
	
	if($username == null){
		echo "E-Mail is a required field.";
	}else{
		if($password == null){
			echo "Password is a required field.";
		}else{
			ProgressIndicator::Show();
			$url = "http://demo.kikapptools.com/Gastos/crud/Login.php";
			$httpClient_post = new httpClient();
				
			$inputMetodo = new InputText();
			$inputMetodo -> setValue("login");
			
			$httpClient_post -> addVariable('username',$username);
			$httpClient_post -> addVariable('password',$password);
			
			$result = $httpClient_post -> Execute('POST',$url);
			
			$id_user = new InputText();	
			$struct = array("response"=>DataType::Character(50));
			
			Data::FromJson($struct,$result);
				
			$id_user = $struct['response'];
			ProgressIndicator::Hide();
			
			if($id_user != "0"){
				StorageAPI::Set("token",$id_user);
				AndroidAction::GoHome();
			}else{
				echo "Wrong user or password";
			}
		}
	}		
}

function register(){
	$win -> Open("register");
}

function back(){
	AndroidAction::GoHome(); 
}
?>