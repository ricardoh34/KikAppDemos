<?php 
/***
 * Categories list
 */

$win = new SDPanel();
$win -> setCaption("Categories");

$table = new Table();

$list = new Grid();
$list -> addData(load_grid());
$list -> onTap(action());

$id = new InputNumeric();
$name = new InputText();
$name -> setClass("inputcategories");

$table_list = new Table();
$table_list -> setHeight("66dip");
$table_list -> addControl($name,1,1,1,1,"Left","Middle");

$list -> addControl($table_list,1,1,1,1,"Left","Middle");
$table -> addControl($list,1,1);
$win -> addControl($table);
$win -> Render();

function action(){
	$win -> Open("categories_sub_list",$id,$name);
}

function load_grid(){
	$url = "http://www.demo.kikapptools.com/magento/apiGecko/categorias.php";
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