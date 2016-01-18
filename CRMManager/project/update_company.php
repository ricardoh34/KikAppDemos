<?php 
/**
 * Update company.
 * @author Gecko
 * @version 1.0
 */

$win = new SDPanel();

$id = new InputNumeric();

Data::getParm($id);

//Action bar and buttons bar
$acb = new ActionBar();
$btn_update = new ButtonBar();
$btn_update -> setCaption("Save");
$btn_update -> onTap(save());

$btn_cancel = new ButtonBar();
$btn_cancel -> setCaption("Cancel");
$btn_cancel -> onTap(cancel());

$acb -> addControl($btn_update);
$acb -> addControl($btn_cancel);
$win -> addControl($acb);

$mainTable = new Table();

$image 	= new InputImage();
$name 	= new InputText();
$address = new InputAddress();
$phone 	= new InputPhone();

$mainTable -> addControl($name,1,1);
$mainTable -> addControl($address,2,1);
$mainTable -> addControl($phone,3,1);
$mainTable -> addControl($image,4,1);

$win -> addControl($mainTable);

function start(){
	$url = "http://demo.kikapptools.com/CRMManager/crud/getCompanies.php?companyId=".$id;
	$httpClient = new httpClient();
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array(
				"CompanyName" => DataType::Character(100),
				"CompanyImage" => DataType::Character(200),
				"CompanyPhone" => DataType::Phone(),
				"CompanyAddress" => DataType::Address()
	);
	
	Data::FromJson($struct,$result);
		
	$image 	= $struct['CompanyImage'];
	$name 	= $struct['CompanyName'];
	$address = $struct['CompanyAddress'];
	$phone 	= $struct['CompanyPhone'];

}

function save(){
	
	ProgressIndicator::Show();
	$httpUpdate = new httpClient();
	$httpUpdate -> addVariable('id',$id);
	$httpUpdate -> addVariable('name',$name);
	$httpUpdate -> addVariable('address',$address);
	$httpUpdate -> addVariable('phone',$phone);
	$httpUpdate -> addVariable('image',$image);
	
	$httpUpdate -> Execute('POST',"http://demo.kikapptools.com/CRMManager/crud/updateCompany.php");
	ProgressIndicator::Hide();
	
	return;
}

function cancel(){
	return;
}

?>