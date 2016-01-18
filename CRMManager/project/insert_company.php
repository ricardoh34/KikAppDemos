<?php 
/**
 * Detail company.
 * @author Gecko
 * @version 1.0
 */

$win = new SDPanel();
$win -> setCaption("Company");

$id = new InputNumeric();

Data::getParm($id);

//Action bar and buttons bar
$acb = new ActionBar();
$btn_save = new ButtonBar();
$btn_save -> setCaption("Save");
$btn_save -> onTap(save());

$btn_cancel = new ButtonBar();
$btn_cancel -> setCaption("Cancel");
$btn_cancel -> onTap(cancel());

$acb -> addControl($btn_save);
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

function save(){
	
	ProgressIndicator::Show();
		$httpUpdate = new httpClient();
		$httpUpdate -> addVariable('CompanyName',$name);
		$httpUpdate -> addVariable('CompanyAddress',$address);
		$httpUpdate -> addVariable('CompanyPhone',$phone);
		$httpUpdate -> addVariable('CompanyImage',$image);		
		
		$httpUpdate -> Execute('POST',"http://demo.kikapptools.com/CRMManager/crud/addCompany.php");
	ProgressIndicator::Hide();
	return;
}

function cancel(){
	return;
}

?>