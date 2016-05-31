<?php 
/**
 * Update company.
 * @author KikApp
 * @version 1.0
 */

$win = new SDPanel();
$win -> setCaption("Transaction");

$token = new InputText(80);
$idMov_trans = new InputNumeric();
Data::getParm($token,$idMov_trans);


//Action bar and buttons bar
$acb = new ActionBar();
$btn_update = new ButtonBar();
$btn_update -> setCaption("Save");
$btn_update -> onTap(save());

$btn_cancel = new ButtonBar();
$btn_cancel -> setCaption("Delete");
$btn_cancel -> onTap(delete());

$acb -> addControl($btn_update);
$acb -> addControl($btn_cancel);
$win -> addControl($acb);

$mainTable= new Table();
$mainTable -> setRowsStyle("100%");
$mainTable -> setWidth("100%");
$mainTable -> setHeight("100%");
$mainTable-> setClass("table.white");

$table = new Table();
$table -> setRowsStyle("76dip;76dip;76dip;76dip;76dip");
$table -> setClass("table.forms");

$move_type 	= new InputText();
$move_type-> setClass("input.form");
$move_type -> setReadOnly(true);

$value 	= new InputNumeric(10);
$value-> setClass("input.form");

$date = new InputDate();
$date-> setClass("input.form");

$description = new InputText();
$description-> setClass("input.form");

$dc_cat = new DynamicComboBox();
$dc_cat -> setValues(load_cat());
$dc_cat-> setAddEmptyItem(false);
$dc_cat-> setClass("input.form");

$label_type= new Label();
$label_type-> setCaption("Move type:");
$label_type-> setClass("label.black");

$label_value= new Label();
$label_value-> setCaption("Value:");
$label_value-> setClass("label.black");

$label_date= new Label();
$label_date-> setCaption("Date:");
$label_date-> setClass("label.black");

$label_description= new Label();
$label_description-> setCaption("Description:");
$label_description-> setClass("label.black");

$label_categorie= new Label();
$label_categorie-> setCaption("Categorie:");
$label_categorie-> setClass("label.black");



$table -> addControl($label_value,1,1);
$table -> addControl($value,1,2);
$table -> addControl($label_date,2,1);
$table -> addControl($date,2,2);
$table -> addControl($label_description,3,1);
$table -> addControl($description,3,2);
$table -> addControl($label_type,4,1);
$table -> addControl($move_type,4,2);
$table -> addControl($label_categorie,5,1);
$table -> addControl($dc_cat,5,2);

$mainTable-> addControl($table,1,1);

$win -> addControl($mainTable);


function refresh(){
	$url = "http://demo.kikapptools.com/Gastos/crud/getMovement.php?id=".$token."&idMov=".$idMov_trans;
	$httpClient = new httpClient();
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array(
				"value" => DataType::Character(100),
				"moveType" => DataType::Character(2),
				"description" => DataType::Character(200),
				"dates" => DataType::Date(),
				"idCat" => DataType::Numeric(11)
	);
	
	Data::FromJson($struct,$result);
		
	$move_type 	= $struct['moveType'];
	$value 	= $struct['value'];
	$date = $struct['dates'];
	$description 	= $struct['description'];
	$dc_cat= $struct['idCat'];
}

function load_cat(){
	$url1 = "http://demo.kikapptools.com/Gastos/crud/getCategories.php";
	$hc = new httpClient();
	$rs = $hc -> Execute("GET",$url1);

	$st_1 = array(
			array(
					"idCat" => DataType::Numeric(11),
					"name" => DataType::Character(80),
					"desc" => DataType::Character(200)
			)
	);

	Data::FromJson($st_1,$rs);
}

function save() {
	
	ProgressIndicator::ShowWithTitle("Saving...");
	$httpUpdate = new httpClient();
	$httpUpdate -> addVariable('idMov',$idMov_trans);
	$httpUpdate -> addVariable('value',$value);
	$httpUpdate -> addVariable('dates',$date);
	$httpUpdate -> addVariable('description',$description);
	$httpUpdate -> addVariable('move_type',$move_type);
	$httpUpdate -> addVariable('idCat',$dc_cat);
	$httpUpdate-> addVariable("id",$token);
	$httpUpdate -> Execute('POST',"http://demo.kikapptools.com/Gastos/crud/updateMovement.php");
	ProgressIndicator::Hide();
	return;
}

function delete() {
	$input_boolean = new InputBoolean();
	$input_boolean = Interop::Confirm("Are you sure you want to delete this movement?");
	if($input_boolean){
		ProgressIndicator::ShowWithTitle("Deleting...");
		$httpC = new httpClient();		
		$httpC -> addVariable("idMov",$idMov_trans);
		$httpC -> addVariable('value',$value);
		$httpC -> addVariable('dates',$date);
		$httpC -> addVariable('move_type',$move_type);
		$httpC-> addVariable("id",$token);
		$httpC -> Execute('POST',"http://demo.kikapptools.com/Gastos/crud/deleteMovement.php");
		ProgressIndicator::Hide();
		return;
	}
}
?>