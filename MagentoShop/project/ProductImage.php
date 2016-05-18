<?php 
/***
 * Detail of product.
 */

$win = new SDPanel();
$win -> setCaption("Product images");
$id = new InputNumeric();
Data::getParm($id);

$table = new Table();

$grid = new HorizontalGrid();
$grid -> setRowsPerPagePortrait(1);
$grid -> setShowPageController(true);
$grid -> addData(load_image());
$table_grid = new Table();
$table_grid -> setRowsStyle("100%");
$table_grid -> setHeight("100%");

$image = new InputImage();
$image -> setClass("imageImageList");
$table_grid -> addControl($image,1,1);

$grid -> addControl($table_grid,1,1);
$table -> addControl($grid,1,1);

$table -> setRowsStyle("100%");
$table -> setClass("tableGrid");

$win -> addControl($table);

function load_image(){
	$url = "http://demo.kikapptools.com/magento/apiKikApp/ProductImages.php?pId=".$id;
	$httpClient = new httpClient();
	
	$result = $httpClient -> Execute('GET',$url);

	$str_images = array(
			array(
					"url"=>DataType::Character(350)
			)
	);
	
	Data::FromJson($str_images,$result);
	
	foreach ($str_images as $img){
		$image 	= $img['url'];
	}	
}


?>