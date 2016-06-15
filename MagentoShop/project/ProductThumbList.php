<?php 
/***
 * Thumbnails products list.
 */

$win = new SDPanel();
$win -> setCaption("List of products");

$cat = new InputNumeric();
Data::getParm($cat);

//Action Bar y Action Group.
$apb1 = new ActionBar();

$btn_img = new ButtonBar();
$btn_img -> setCaption("List");
$btn_img -> setImage("img/ic_list_white.png");
$btn_img -> onTap(lista());

$apb1 -> addControl($btn_img);

$win -> addControl($apb1);

$table = new Table();
$table -> setClass("tableGray");

$grid = new Grid();
$table_grid = new Canvas();
$table_grid -> setClass("tableTableDetailMain");

$grid -> addSearch($title);
$grid -> addData(grid_load());
$grid -> onTap(action());
$grid -> setEmptyGridText("No items available");

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

$table_grid -> addPosition($image,"0%","100%","0","185dip","0%","100%");
$table_grid -> addPosition($table_desc,"0%","100%","95dip","70dip","0","0");

$grid -> addControl($table_grid);
$table -> addControl($grid);

$win -> addControl($table);
$win -> Render();

function lista(){
	$win -> Open("ProductList",$cat);
}

//Grid load function
function grid_load(){
	$url = "http://demo.kikapptools.com/magento/apiKikApp/Products.php?cId=".$cat;
	$httpClient = new httpClient();
	
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array(
					array(
							"id" => DataType::Numeric(6),								
							"name" => DataType::Character(150),
							"description" => DataType::Character(300),							
							"pirce" => DataType::Character(10),
							"thumb"=>DataType::Character(200),
							"stock" => DataType::Character(50),
							"href" => DataType::Character(300)
						)
				);
	
	Data::FromJson($struct,$result);
	$prodUrl 	= new InputText();
	$stock  	= new InputText();
	$id 		= new InputNumeric();
	
	foreach ($struct as $product){		
		$id 	= $product['id'];
		$title 	= $product['name'];
		$desc 	= $product['description'];
		$image 	= $product['thumb'];
		$price 	= $product['pirce'];
		$stock 	= $product['stock'];
		$prodUrl= $product['href'];
	}
	
}

function action(){
	$win -> Open("ProductDetail",$id,$title,$desc,$price,$stock,$prodUrl);
}

?>