<?php 
/***
 * Categories list
 */

$win = new SDPanel();
$win -> setCaption("Categories");

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

$list -> addControl($table_list,1,1,1,1,"Left","Middle");
$table -> addControl($list,1,1);
$win -> addControl($table);
$win -> Render();

function action(){
	$win -> Open("SubCategories",$id,$name);
}

function load_grid(){
	$url = "http://demo.kikapptools.com/magento/apiKikApp/Categories.php";
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

?>