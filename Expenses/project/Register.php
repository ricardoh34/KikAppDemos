<?php 
/***
 * Register.
 */

$win = new SDPanel();
$win -> setCaption("Create an account");

$table = new Table();
$table -> setWidth("100%");
$table -> setHeight("100%");
$table -> setClass("table.white");

$table_form = new Table();
$table_form -> setRowsStyle("76dip;76dip;76dip");
$table_form -> setClass("table.forms");

$email 		= new InputEmail();
$email -> setInviteMessage("E-Mail");
$email -> setClass("input.form");

$password	= new InputText();
$password -> setClass("input.form");
$password -> setInviteMessage("Password");
$password -> setIsPassword(true);

$password_2	= new InputText();
$password_2 -> setClass("input.form");
$password_2 -> setInviteMessage("Confirm your Password");
$password_2 -> setIsPassword(true);

$table_form -> addControl($email,1,1);
$table_form -> addControl($password,2,1);
$table_form -> addControl($password_2,3,1);

$btn_save = new Button();
$btn_save -> setCaption("SIGN UP");
$btn_save -> setClass("button.blue");
$btn_save -> onTap(save());

$table_button = new Table();

$table_button -> addControl($btn_save,1,1);

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);

function save(){
	if($email == null){
		echo "EMail is a required field.";
	}else{
		if($password == null){
			echo "Password is a required field.";
		}else{
			if($password != $password_2){
				echo "Please make sure your passwords match.";
			}else{	
				ProgressIndicator::Show();
   				
				$url = "http://demo.kikapptools.com/Gastos/crud/Register.php";
				$http = new httpClient();
				$http -> addVariable('username',$email);
				$http -> addVariable('passcode',$password); //lo de pasar la clave a md5 lo hace el Register.php
				
				$result = $http -> Execute('POST',$url);
				$struct= array(
					"id_usuario" => DataType::Character(101)
				);
				Data::FromJson($struct,$result);		

				$id_user= new InputText(100);
				$id_user= $struct['id_usuario'];

				if($id_user=="0") {
					echo "User already signed up";
				}
				else {
					echo "Signed up succesfully.";
					StorageAPI::Set("token",$id_user);
					AndroidAction::GoHome();
				}
			}
		}				
	}				
}

?>