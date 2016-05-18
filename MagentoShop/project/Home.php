<?php 
/***
 * Home.
 */

$win = new SDPanel();
$win -> setCaption("KikApp Store");

$table = new Table();
$table -> setClass("tableGray");

$grid_home = new HorizontalGrid();
$grid_home -> setRowsPerPagePortrait(1);
$grid_home -> setShowPageController(false);

$table_grid = new Canvas();

$grid_home -> addData(grid_load());
$grid_home -> onTap(action());

//inputs and controls
$title 	= new InputText(150);
$title -> setClass("inputtextTitlewhite");
$title -> setAutoGrow(true);
$image 	= new InputImage();
$image -> setClass("imageImageList");
$desc 	= new InputText(300);
$desc -> setClass("inputtextwhite");
$price 	= new InputText(10);
$price -> setClass("inputtextPrice");

$table_desc = new Table();
$table_desc -> setRowsStyle("25dip;35dip;25dip");
$table_desc -> setClass("tableProductThumb");
$table_desc -> addControl($title,1,1);
$table_desc -> addControl($desc,2,1);
$table_desc -> addControl($price,3,1);

$table_grid -> addPosition($image,"0%","100%","0","230dip","0%","100%");
$table_grid -> addPosition($table_desc,"0%","100%","140dip","83dip","0","0");

$grid_home -> addControl($table_grid);


/**
 * Grid of products.
 */

$grid = new Grid();
$table_grid_product = new Table();
$table_grid_product -> setClass("tableGrid");
$table_grid_product -> setRowsStyle("80dip");
$table_grid_product -> setColumnsStyle("35%;65%;15dip");
$table_grid_product -> setHeight("85dip");

$grid -> addData(grid_load_products());
$grid -> onTap(action_prod());

//inputs and controls
$title_prod 	= new InputText(150);
$title_prod -> setClass("input.titleList");
$title_prod -> setAutoGrow(true);
$image_prod 	= new InputImage();
$image_prod -> setClass("image.roundedListImage");
$desc_prod 	= new InputText(300);
$desc_prod -> setClass("input.textDescription");
$price_prod 	= new InputText(10);
$price_prod -> setClass("input.priceList");

$table_desc_prod = new Table();
$table_desc_prod -> setRowsStyle("25dip;35dip;25dip");
$table_desc_prod -> setClass("tableProduct");
$table_desc_prod -> addControl($title_prod,1,1);
$table_desc_prod -> addControl($desc_prod,2,1);
$table_desc_prod -> addControl($price_prod,3,1);

$table_grid_product -> addControl($image_prod,1,1);
$table_grid_product -> addControl($table_desc_prod,1,2);

$grid -> addControl($table_grid_product);

$table -> setRowsStyle("230dip;100%");
$table -> addControl($grid_home,1,1);
$table -> addControl($grid,2,1);

$win -> addControl($table);
$win -> Render();

function grid_load(){
	$url = "http://demo.kikapptools.com/magento/apiKikApp/Products.php?display=home";
	$httpClient = new httpClient();
	
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array(
				array(
					"id" => DataType::Numeric(6),								
					"name" => DataType::Character(150),
					"description" => DataType::Character(300),							
					"pirce" => DataType::Character(10),
					"thumb"=> DataType::Character(200),
					"stock" => DataType::Character(50)
				)
	);
	
	
	Data::FromJson($struct,$result);
	$stock 	= new InputText();
	$id 	= new InputNumeric();
	
	foreach ($struct as $product){		
		$id 	= $product['id'];
		$title 	= $product['name'];
		$desc 	= $product['description'];
		$image 	= $product['thumb'];
		$price 	= $product['pirce'];
		$stock 	= $product['stock'];
	}
	
}

function grid_load_products(){
	$url_prod = "http://demo.kikapptools.com/magento/apiKikApp/Products.php?cId=0";
	$httpClient_prod = new httpClient();

	$result_prod = $httpClient_prod -> Execute('GET',$url_prod);


	$struct_prod = array(
			array(
					"id" => DataType::Numeric(6),
					"name" => DataType::Character(150),
					"description" => DataType::Character(300),
					"pirce" => DataType::Character(10),
					"thumb"=> DataType::Character(200),
					"stock" => DataType::Character(50)
			)
	);

	Data::FromJson($struct_prod,$result_prod);
	$stock_prod = new InputText();
	$id_prod	= new InputNumeric();

	foreach ($struct_prod as $product){
		$id_prod 	= $product['id'];
		$title_prod 	= $product['name'];
		$desc_prod 	= $product['description'];
		$image_prod 	= $product['thumb'];
		$price_prod 	= $product['pirce'];
		$stock_prod 	= $product['stock'];
	}
	
}

function action(){
	$win -> Open("ProductDetail",$id,$title,$desc,$price,$stock);
}

function action_prod(){
	$win -> Open("ProductDetail",$id_prod,$title_prod,$desc_prod,$price_prod,$stock_prod);
}

?>