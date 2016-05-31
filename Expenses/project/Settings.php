<?php 


$win = new SDPanel();
$win -> setCaption("Settings");

$token = new InputText(80);
Data::getParm($token);

$table = new Table();
$table -> setRowsStyle("100%");
$table -> setWidth("100%");
$table -> setHeight("100%");
$table -> setClass("table.white");

$table_form = new Table();
$table_form -> setRowsStyle("76dip;76dip;76dip");
$table_form -> setClass("table.forms");

$username 	= new InputEmail();
$username -> setClass("input.form");
$username -> setInviteMessage("E-Mail");

$password 	= new InputText();
$password -> setClass("input.form");
$password -> setInviteMessage("Password");
$password -> setIsPassword(true);

$password2 	= new InputText();
$password2 -> setClass("input.form");
$password2 -> setInviteMessage("Password");
$password2 -> setIsPassword(true);

$btn_save= new Button();
$btn_save-> setCaption("Save changes");
$btn_save-> onTap(save());
$btn_save-> setClass("button.blue");

$table_form -> addControl($username,1,1);
$table_form -> addControl($password,2,1);
$table_form -> addControl($password2,3,1);

$table_button = new Table();

$table_button -> addControl($btn_save,1,1);

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);

function clientStart() {
	$url2 = "http://demo.kikapptools.com/Gastos/crud/getUser.php?id=".$token;
   $httpClient2 = new httpClient();
   $result2= $httpClient2 -> Execute('GET',$url2);
   
   $struct2= array(
            "username" => DataType::Character(100)
            );
   Data::FromJson($struct2,$result2); 
   $username = $struct['username'];  
}

function save(){
	
	if($username == null){
		echo "E-Mail is a required field.";
	}else{
		if($password == null){
			echo "Password is a required field.";
		}
		else{
			if($password != $password2){
				echo "Please make sure your passwords match.";
			}
			else{
				ProgressIndicator::Show();
				$url = "http://demo.kikapptools.com/Gastos/crud/updateUser.php";
				$httpClient_post = new httpClient();
					
				$inputMetodo = new InputText();
				$inputMetodo -> setValue("login");
				
				$httpClient_post -> addVariable('username',$username);
				$httpClient_post -> addVariable('password',$password);
				$httpClient_post -> addVariable('id',$token);

				$result = $httpClient_post -> Execute('POST',$url);
				
				$id_user = new InputText();	
				$struct = array("response"=>DataType::Character(50));
				
				Data::FromJson($struct,$result);
					
				$id_user = $struct['response'];
				ProgressIndicator::Hide();
				AndroidAction::GoHome();
			}
		}
	}	
}

function back(){
	AndroidAction::GoHome(); 
}
?>