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

$lastName 	= new InputText();
$lastName -> setInviteMessage("Last Name");
$lastName -> setClass("inputForm");

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
$table_form -> addControl($lastName,2,1);
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
		if($lastName == null){
			echo "Last name is a required field.";
		}else{
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
						
						$url = "http://www.demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=createUser";
						$http = new httpClient();
						$http -> addVariable('name',$name);
						$http -> addVariable('lastname',$lastname);
						$http -> addVariable('email',$email);
						$http -> addVariable('password',$password);
						
						$result = $http -> Execute('POST',$url);
							
						$id_user = new InputNumeric();
						
						$SDTUser = array("id"=>DataType::Numeric(8));
						
						Data::FromJson($SDTUser,$result);
						
						$id_user = $SDTUser['id'];
						
						ProgressIndicator::Hide();
						
						if($id_user != 0){
							StorageAPI::Set("token",$id_user);
							AndroidAction::GoHome();
						}else{
							echo "Error..." .$id_user;
						}
					}
				}				
			}				
		}
	}	
}

?>