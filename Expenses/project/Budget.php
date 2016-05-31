<?php 


$win = new SDPanel();
$win -> setCaption("Add Budget");

$token = new InputText(80);

$table = new Table();
$table -> setRowsStyle("100%");
$table -> setWidth("100%");
$table -> setHeight("100%");
$table-> setClass("table.white");

$table_form = new Table();
$table_form -> setRowsStyle("76dip;76dip");
$table_form -> setClass("table.forms");

$money_value= new InputNumeric(15);
$money_value-> setClass("input.form");

$input_date= new InputDate();
$input_date-> setClass("input.form");

$button= new Button();
$button-> setCaption("Confirm");
$button-> onTap(confirm());
$button-> setClass("button.blue");

$table_form-> addControl($money_value,1,1,1,1,"Center","Middle");
$table_form-> addControl($input_date,2,1,1,1,"Center","Middle");

$table_button = new Table();

$table_button-> addControl($button,1,1,1,1,"Center","Middle");

$table -> addControl($table_form,1,1);
$table -> addControl($table_button,2,1);

$win -> addControl($table);



function confirm() {
   $token = StorageAPI::Get("token");
	ProgressIndicator::ShowWithTitle("Adding budget...");
	$httpUpdate = new httpClient();
	$httpUpdate -> addVariable('money',$money_value);
	$httpUpdate -> addVariable('dates',$input_date);
	$httpUpdate-> addVariable("id",$token);
	$httpUpdate -> Execute('POST',"http://demo.kikapptools.com/Gastos/crud/updateAccount.php");
	ProgressIndicator::Hide();
	AndroidAction::GoHome();
}
?>