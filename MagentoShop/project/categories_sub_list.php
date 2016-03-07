<?php 
/***
 * Sub categories list.
 */

$win = new SDPanel();
$win -> setCaption("");

Data::getParm($cat,$name);

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

$list -> addControl($table_list,1,1);
$table -> addControl($list,1,1);

$win -> addControl($table);
$win -> Render();

function action(){
	$win -> Open("products_list",$id);
}

function load_grid(){
	$url = "http://www.demo.kikapptools.com/magento/apiGecko/categorias.php?cId=".$cat;
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