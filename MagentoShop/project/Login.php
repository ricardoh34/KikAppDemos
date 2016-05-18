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
$lbl_home -> setClass("label.Titlelogin");
$lbl_home -> setCaption("Login to KikApp Store");

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

$lbl_signup = new Label();
$lbl_signup -> setClass("label.SubTitlelogin");
$lbl_signup -> setCaption("Not a store member?");

$btn_register = new Button();
$btn_register -> setCaption("SIGN UP NOW");
$btn_register -> setClass("button.Small");
$btn_register -> onTap(register());

$table_form -> addControl($email,1,1);
$table_form -> addControl($pass,2,1);

$table -> addControl($lbl_home,1,1,1,1,"Center","Middle");
$table -> addControl($table_form,2,1);

$table_button = new Table();
$table_button -> addControl($btn_save,1,1);
$table_button -> addControl($lbl_signup,2,1,1,1,"Center","Middle");
$table_button -> addControl($btn_register,3,1);

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
			$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=login";
			$httpClient_post = new httpClient();
			
			$httpClient_post -> addVariable('email',$email);
			$httpClient_post -> addVariable('password',$pass);
			
			$result = $httpClient_post -> Execute('POST',$url);
			
			$customerToken = new InputText();	
			$response = array("customerToken"=>DataType::Character(150));
			
			Data::FromJson($response,$result);
				
			$customerToken = $response['customerToken'];
			ProgressIndicator::Hide();
						
			if($customerToken != ""){
				StorageAPI::Set("token",$customerToken);
				AndroidAction::GoHome();
			}else{
				echo "Invalid user";
			}
		}
	}		
}

function register(){
	$win -> Open("Register");
}

?>