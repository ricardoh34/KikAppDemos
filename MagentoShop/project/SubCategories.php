<?php 
/***
 * Sub categories list.
 */

$win = new SDPanel();

$cat = new InputNumeric();
Data::getParm($cat,$name);

$table = new Table();

$list = new Grid();
$list -> addData(load_grid());
$list->setEmptyGridText("No items available");
$list -> onTap(action());

$id = new InputNumeric();
$name = new InputText();
$name -> setClass("input.categories");

$table_list = new Table();
$table_list -> setHeight("60dip");
$table_list -> setClass("table.categorias");
$table_list -> addControl($name,1,1,1,1,"Left","Middle");

$list -> addControl($table_list,1,1);
$table -> addControl($list,1,1);
$win -> addControl($table);
$win -> Render();

function action(){
	$win -> Open("ProductList",$id);
}

function load_grid(){
	$url = "http://demo.kikapptools.com/magento/apiKikApp/Categories.php?cId=".$cat;
	$httpClient = new httpClient();
	
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array(
			array(
					"id" => DataType::Numeric(6),
					"name" => DataType::Character(150)
			)
	);
	
	Data::FromJson($struct,$result);
	
	foreach ($struct as $product){
		$id 	= $product['id'];
		$name 	= $product['name'];
	}	
}

function start(){
	$win -> setCaption($name);
}

?>