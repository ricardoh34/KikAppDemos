<?php 

$win = new SDPanel();
$win -> setCaption("Balance");

$token = new InputText(80);

$maintable = new Table();
$maintable -> setRowsStyle("100%");
$maintable -> setWidth("100%");
$maintable -> setHeight("100%");

$input = new InputNumeric(11);
$input -> setClass("label.black");
$input -> setReadOnly(true);


$label = new Label();
$label -> setCaption("Total balance:");
$label -> setClass("label.black");

$table = new Table();
$table -> setRowsStyle("100%");
$table -> setWidth("100%");
$table -> setHeight("65%");
$table -> setClass("table.account");

$table -> addControl($label,1,1,1,1,"Center","Middle");
$table -> addControl($input,1,2,1,1,"Center","Middle");

$maintable -> addControl($table,1,1);

$win -> addControl($maintable);


function clientStart() {
	$token = StorageAPI::Get("token");
	$url = "http://demo.kikapptools.com/Gastos/crud/getAccount.php";
	$httpClient = new httpClient();
   $httpClient-> addVariable("id",$token);
	$result= $httpClient -> Execute('POST',$url);
	
	$struct= array(
				"money" => DataType::Numeric(11)
	);
	
	Data::FromJson($struct,$result);

	$input = $struct['money'];

}

function back(){
	AndroidAction::GoHome(); 
}
?>