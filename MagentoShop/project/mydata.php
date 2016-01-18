<?php 
/***
 * Pantalla para editar los datos del usuario.
 */

$win = new SDPanel();
$win -> setCaption("My Account");

$token = new InputText(80);
Data::getParm($token);

$table = new Table();
$table -> setClass("tablebackground");

$table_form = new Table();
$table_form -> setClass("tableforms");
$table_form -> setRowsStyle("76dip;76dip;76dip");

$name 	= new InputText();
$name -> setClass("inputForm");

$ape 	= new InputText();
$ape -> setClass("inputForm");

$email 	= new InputEmail();
$email -> setClass("inputForm");

$btn_save = new Button();
$btn_save -> setCaption("Save");
$btn_save -> setClass("buttonform");
$btn_save -> onTap(save());

$table_form -> addControl($name,1,1);
$table_form -> addControl($ape,2,1);
$table_form -> addControl($email,3,1);

$table_button = new Table();
$table_button -> addControl($btn_save,1,1);

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);
$win -> Render();

function refresh(){

	$ur = "http://www.demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=userData&customerToken=".$token;
	$http = new httpClient();
	$result = $http -> Execute('GET',$ur);
	
	$sdtUser = array("firstname"=>DataType::Character(35), "lastname" => DataType::Character(50), "email"=> DataType::Email());
	Data::FromJson($sdtUser,$result);
	
	//Asignamos valores a los inputs.
	$name = $sdtUser['firstname'];
	$ape = $sdtUser['lastname'];
	$email = $sdtUser['email'];

}

function save(){
	ProgressIndicator::Show();
	$url = "http://www.demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=updateUser&name=".$name."&lastname=".$ape."&email=".$email."&customerToken=".$token;
	$hc = new httpClient();
	$rs = $hc -> Execute("GET",$url);
	
	$sdtError = array("id"=>DataType::Character(100));
	Data::FromJson($sdtError,$rs);
	
	$rsValue = new InputText(100);
	$rsValue = $sdtError['id'];
	ProgressIndicator::Hide();
	
	$win -> Refresh();
	 
}


function back(){
	echo "Back...";
}

?>