<?php 
/**
 * Main object.
 * @author Kikapp
 * @version 1.0
 */

$win = new SDPanel();
$win -> setCaption("Transactions");

$token = new InputText(80);
Data::getParm($token);

$table = new Table();
$table -> setClass("table.grid");

$gridMov = new Grid();
$gridMov -> addData(gridMov_load_transactions());	//the main use of the gridMov is to load data from a server
$gridMov -> onTap(action_trans());	//what happens when you tap on it
//$gridMov-> setEmptyGridBackgroundImage("img/Android/hdpi/lunchimage1.png"); //pone una imagen si esta vacio
$gridMov -> setEmptyGridText("No movements");
$gridMov -> setEmptyGridTextClass("label.black");

$table_gridMov_transaction = new Table();
$table_gridMov_transaction -> setClass("table.GridProduct");
$table_gridMov_transaction -> setRowsStyle("80dip");
$table_gridMov_transaction -> setColumnsStyle("35%;65%;15dip");
$table_gridMov_transaction -> setHeight("85dip");

//inputs and controls

$type_trans 	= new InputText(15);
$type_trans -> setClass("label");
$type_trans -> setAutoGrow(true);

$desc_trans 	= new InputText(300);
$desc_trans -> setClass("label");

$mount_trans 	= new InputText(10);
$mount_trans -> setClass("label");

$table_desc_trans = new Table();
$table_desc_trans -> setClass("tableProduct");

$label_type= new Label();
$label_type-> setCaption("Move type:");

$label_value= new Label();
$label_value-> setCaption("Value:");

$label_description= new Label();
$label_description-> setCaption("Description:");

$label_movement= new Label();

$label_id= new Label();
$label_id-> setCaption("Movement Id:")

$idMov_trans= new InputNumeric();
$idMov_trans-> setClass("label");

$input_date= new InputText();
$input_date-> setClass("label");

$label_date= new Label();
$label_date-> setCaption("Date:");

$table_desc_trans -> addControl($label_type,1,1);
$table_desc_trans -> addControl($type_trans,1,2);
$table_desc_trans -> addControl($label_description,2,1);
$table_desc_trans -> addControl($desc_trans,2,2);
$table_desc_trans -> addControl($label_value,3,1);
$table_desc_trans -> addControl($mount_trans,3,2);
$table_desc_trans -> addControl($label_date,4,1);
$table_desc_trans -> addControl($input_date,4,2);


$table_gridMov_transaction -> addControl($table_desc_trans,1,1);

$gridMov -> addControl($table_gridMov_transaction);

$table -> setRowsStyle("100%");
$table -> addControl($gridMov,1,1);

$win -> addControl($table);

function action_trans(){

	$token = new InputText(80);
	$token = StorageAPI::Get("token");
	$win -> Open("transaction_detail",$token,$idMov_trans);
}

function gridMov_load_transactions(){
	$url_prod = "http://demo.kikapptools.com/Gastos/crud/getMovements.php?id=".$token;	
	//the server url
	//Remember to configurate your server url on the Manifest.xml on <services>
	$httpClient_prod = new httpClient();
	//$httpClient_prod-> addVariable("id",$token);
	$result_prod = $httpClient_prod -> Execute('GET',$url_prod); 
	//you get a JSON

	//it's a list so you have an array of an array
	$struct_prod = array(
			array(
				   "idMov" => DataType::Numeric(6),
					"moveType" => DataType::Character(150),
					"description" => DataType::Character(300),
					"value" => DataType::Character(10),
               "dates" => DataType::Date(12)
			)
	);

	Data::FromJson($struct_prod,$result_prod); 
	//does a merge of the structure you made and the json you got

	foreach ($struct_prod as $movement){
		$idMov_trans 	= $movement['idMov'];
		$type_trans 	= $movement['moveType'];
		$desc_trans 	= $movement['description'];
		$mount_trans 	= $movement['value'];
      $input_date    = $movement['dates'];
	}
}
function back(){
	AndroidAction::GoHome(); 
}
?>