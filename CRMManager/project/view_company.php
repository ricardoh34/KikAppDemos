<?php 
/**
 * Detail company.
 * @author KikApp
 * @version 1.0
 */

$win = new SDPanel();

$id = new InputNumeric();

Data::getParm($id);

//Action bar and buttons bar
$acb = new ActionBar();

$btn_update = new ButtonBar();
$btn_update -> setCaption("Update");
$btn_update -> onTap(update());

$btn_delete = new ButtonBar();
$btn_delete -> setCaption("Delete");
$btn_delete -> onTap(delete());

$acb -> addControl($btn_update);
$acb -> addControl($btn_delete);
$win -> addControl($acb);

$mainTable = new Table();
$mainTable -> setEnableHeaderRowPattern(true);
$mainTable -> setHeaderRowApplicationBarsClass("applicationBars.transparent");
$mainTable -> setRowsStyle("150dip;pd;pd");

$tableImg = new Table();
$tableImg -> setClass("table.image");
$tableImg -> setRowsStyle("pd;150dip;pd"); 

$image 	= new InputImage();
$image -> setReadOnly(true);
$image -> setClass("image.roundBorder");

$name 	= new InputText();
$name -> setReadOnly(true);
$name -> setClass("input.title");

$address = new InputAddress();
$address -> setReadOnly(true);

$phone 	= new InputPhone();
$phone -> setReadOnly(true);

$tableImg -> addControl($image,2,1,1,1,"Center","Middle");
$tableImg -> addControl($name,3,1,1,1,"Center","Middle");

$tableDesc = new Table();
$tableDesc -> setClass("table.margin");
$tableDesc -> addControl($address,1,1);
$tableDesc -> addControl($phone,2,1);

$mainTable -> addControl($tableImg,1,1);
$mainTable -> addControl($tableDesc,2,1);

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

function update(){
	$win -> Open("update_company",$id);
}

function delete(){
	$isOk = new InputBoolean();
	$isOk = Interop::Confirm("Do you want to delete company ?");
	
	if($isOk == true){
		ProgressIndicator::Show();
		$httpC = new httpClient();		
		$httpC -> addVariable("CompanyId",$id);
		$httpC -> Execute('POST',"http://demo.kikapptools.com/CRMManager/crud/deleteCompany.php");
		ProgressIndicator::Hide();
		return;
	}
}

?>