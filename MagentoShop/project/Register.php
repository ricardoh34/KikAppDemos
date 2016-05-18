<?php 
/***
 * Register.
 */

$win = new SDPanel();
$win -> setCaption("Create an account");

$table = new Table();
$table -> setClass("tablebackground");

$table_form = new Table();
$table_form -> setClass("tableforms");
$table_form -> setRowsStyle("76dip;76dip;76dip;76dip;76dip");

$name 		= new InputText();
$name -> setInviteMessage("Name");
$name -> setClass("inputForm");

$lastname 	= new InputText();
$lastname -> setInviteMessage("Last Name");
$lastname -> setClass("inputForm");

$email 		= new InputEmail();
$email -> setInviteMessage("E-Mail");
$email -> setClass("inputForm");

$password	= new InputText();
$password -> setClass("inputForm");
$password -> setInviteMessage("Password");
$password -> setIsPassword(true);

$password_2	= new InputText();
$password_2 -> setClass("inputForm");
$password_2 -> setInviteMessage("Repite Password");
$password_2 -> setIsPassword(true);


$btn_save = new Button();
$btn_save -> setCaption("SIGN UP");
$btn_save -> setClass("buttonform");
$btn_save -> onTap(save());

$table_form -> addControl($name,1,1);
$table_form -> addControl($lastname,2,1);
$table_form -> addControl($email,3,1);
$table_form -> addControl($password,4,1);
$table_form -> addControl($password_2,5,1);

$table_button = new Table();
$table_button -> addControl($btn_save,1,1);

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);
$win -> Render();

function save(){
	if($name == null){
		echo "Name is a required field.";
	}else{
		if($lastname == null){
			echo "Last name is a required field.";
		}else{
			if($email == null){
				echo "E-Mail is a required field.";
			}else{
				if($password == null){
					echo "Password is a required field.";
				}else{
					if($password != $password_2){
						echo "Please make sure your passwords match.";
					}else{	

						ProgressIndicator::Show();
						
						$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=createUser";
						$http = new httpClient();
						$http -> addVariable('name',$name);
						$http -> addVariable('lastname',$lastname);
						$http -> addVariable('email',$email);
						$http -> addVariable('password',$password);						
						$rs = $http -> Execute("POST",$url);
	
						$sdt_rs = array("error"=>DataType::Character(300));
						Data::FromJson($sdtError,$rs);
						
						$rs = new InputText(300);
						$rs = $sdt_rs['error'];
					
						ProgressIndicator::Hide();
						
						if($rs == "0"){
							echo "User created successfully!";
							$win -> Open("Login");
						}else{
							echo $rs;
						}
					}
				}				
			}				
		}
	}	
}

?>