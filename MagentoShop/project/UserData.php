<?php 
/***
 * Edit my data.
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

$last_name 	= new InputText();
$last_name -> setClass("inputForm");

$email 	= new InputEmail();
$email -> setClass("inputForm");

$btn_save = new Button();
$btn_save -> setCaption("Save");
$btn_save -> setClass("buttonform");
$btn_save -> onTap(save());

$table_form -> addControl($name,1,1);
$table_form -> addControl($last_name,2,1);
$table_form -> addControl($email,3,1);

$table_button = new Table();
$table_button -> addControl($btn_save,1,1);

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);
$win -> Render();

function refresh(){

	$ur = "http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userData&customerToken=".$token;
	$http = new httpClient();
	$result = $http -> Execute('GET',$ur);
	
	$sdtUser = array("firstname"=>DataType::Character(35), "lastname" => DataType::Character(50), "email"=> DataType::Email());
	Data::FromJson($sdtUser,$result);
	
	//Asignamos valores a los inputs.
	$name = $sdtUser['firstname'];
	$last_name = $sdtUser['lastname'];
	$email = $sdtUser['email'];

}

function save(){
	ProgressIndicator::Show();
	
	$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=updateUser";
	$hc = new httpClient();
	$hc->addVariable("name",$name);
	$hc->addVariable("lastname",$last_name);
	$hc->addVariable("email",$email);
	$hc->addVariable("customerToken",$token);
	$rs = $hc -> Execute("POST",$url);
	
	$sdt_rs = array("error"=>DataType::Character(300));
	Data::FromJson($sdtError,$rs);
	
	$rs = new InputText(300);
	$rs = $sdt_rs['error'];

	ProgressIndicator::Hide();
	
	if($rs == "0"){
		echo "User updated successfully!";
		$win -> Refresh();
	}else{
		echo $rs;
	}
	 
}

?>