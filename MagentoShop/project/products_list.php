<?php 
/*** 
 * Products list.
 */

$win = new SDPanel();
$win -> setCaption("List of products");

Data::getParm($cat);

$cat = new InputNumeric();
//$categoria -> setValue($cat);

//Action Bar and Action Group.
$acb = new ActionBar();

$btn_img = new ButtonBar();
$btn_img -> setCaption("");
$btn_img -> setImage("img/ico_thumb.png");
$btn_img -> onTap(imagen());

$acb -> addControl($btn_img);

$win -> addControl($acb); //Add action bar in panel.

$table = new Table();
$table -> setClass("tableGray");

$grid = new Grid();
$table_grid = new Table();
$table_grid -> setClass("tableGrid");
$table_grid -> setRowsStyle("80dip");
$table_grid -> setColumnsStyle("35%;65%;15dip");
$table_grid -> setHeight("85dip");

$grid -> addSearch($title);
$grid -> addData(grid_load());
$grid -> onTap(action());

//inputs and controls
$title 	= new InputText(150);
$title -> setClass("attributetitleList");
$title -> setAutoGrow(true);
$image 	= new InputImage();
$image -> setClass("imageImageList2");
$desc 	= new InputText(300);
$desc -> setClass("attributeTextGray");
$price 	= new InputNumeric(8,2);
$price -> setClass("attributeTextPrice");

$table_desc = new Table();
$table_desc -> setRowsStyle("25dip;35dip;25dip");
$table_desc -> setClass("tableProduct");
$table_desc -> addControl($title,1,1);
$table_desc -> addControl($desc,2,1);
$table_desc -> addControl($price,3,1);

$table_grid -> addControl($image,1,1);
$table_grid -> addControl($table_desc,1,2);

$grid -> addControl($table_grid);
$table -> addControl($grid);

$win -> addControl($table);
$win -> Render();

function imagen(){
	$win -> Open("thumb_list",$cat);
}

//Function load data in grid.
function grid_load(){
	$url = "http://www.demo.kikapptools.com/magento/apiGecko/productos.php?cId=".$cat;
	$httpClient = new httpClient();
	
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array(
					array(
							"id" => DataType::Numeric(6),								
							"name" => DataType::Character(150),
							"description" => DataType::Character(300),							
							"pirce" => DataType::Numeric(8,2),
							"thumb"=>DataType::Character(200)
						)
				);
	
	Data::FromJson($struct,$result);

	foreach ($struct as $product){		
		$id 	= $product['id'];
		$title 	= $product['name'];
		$desc 	= $product['description'];
		$image 	= $product['thumb'];
		$price 	= $product['pirce'];
	}
	
	$id = new InputNumeric();
}
	
function action(){
	$win -> Open("product_detail",$id,$title,$desc,$price);
}

?>