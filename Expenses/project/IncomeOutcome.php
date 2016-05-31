<?php 


$win = new SDPanel();
$win -> setCaption("Movement");

$token = new InputText(80);

$moveType= new InputText();
Data:: getParm($moveType);

$table = new Table();
$table -> setRowsStyle("100%");
$table -> setWidth("100%");
$table -> setHeight("100%");
$table-> setClass("table.white");

$table_form = new Table();
$table_form -> setRowsStyle("76dip;76dip;76dip;76dip");
$table_form -> setClass("table.forms");

$input_value= new InputNumeric(15); 
$input_value-> setClass("input.form");

$input_date= new InputDate();
$input_date-> setClass("input.form");

$input_desc= new InputText();
$input_desc-> setLabelCaption("Description");
$input_desc-> setClass("input.form");


$dc_cat = new DynamicComboBox();
$dc_cat -> setValues(load_cat());
$dc_cat-> setAddEmptyItem(false);
$dc_cat-> setClass("input.form");


$button= new Button();
$button-> setCaption("Confirm");
$button-> onTap(confirm());
$button-> setClass("button.blue");

$table_form-> addControl($input_value,1,1,1,1,"Center","Middle");
$table_form-> addControl($input_date,2,1,1,1,"Center","Middle");
$table_form-> addControl($input_desc,3,1,1,1,"Center","Middle");
$table_form-> addControl($dc_cat,4,1,1,1,"Center","Middle");

$table_button = new Table();

$table_button-> addControl($button,1,1,1,1,"Center","Middle");

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);


function load_cat(){
	$url = "http://demo.kikapptools.com/Gastos/crud/getCategories.php";
	$hc = new httpClient();
	$rs = $hc -> Execute("GET",$url);

	$struct = array(
			array(
					"idCat" => DataType::Numeric(11),
					"name" => DataType::Character(80),
					"desc" => DataType::Character(200)
			)
	);

	Data::FromJson($struct,$rs);
}


function confirm() {
	$token = StorageAPI::Get("token");
	ProgressIndicator::ShowWithTitle("Inserting movement...");
	$httpUpdate = new httpClient();
	$httpUpdate -> addVariable('value',$input_value);
	$httpUpdate -> addVariable('moveType',$moveType);
	$httpUpdate -> addVariable('description',$input_desc);
	$httpUpdate-> addVariable("id",$token);
	$httpUpdate -> addVariable('idCat',$dc_cat);
	$httpUpdate -> addVariable('dates',$input_date);
	$httpUpdate -> Execute('POST',"http://demo.kikapptools.com/Gastos/crud/insertMovements.php");
	ProgressIndicator::Hide();
	AndroidAction::GoHome();
}
?>