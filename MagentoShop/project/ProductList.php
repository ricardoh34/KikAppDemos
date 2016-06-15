<?php 
/*** 
 * Products list.
 */

$win = new SDPanel();
$win -> setCaption("List of products");

$cat = new InputNumeric();
Data::getParm($cat);

//Action Bar and Action Group.
$acb = new ActionBar();

$btn_img = new ButtonBar();
$btn_img -> setCaption("");
$btn_img -> setImage("img/ic_view_stream_white.png");
$btn_img -> onTap(changeView());

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
$grid -> setEmptyGridText("No items available");

//inputs and controls
$title 	= new InputText(150);
$title -> setClass("input.titleList");
$title -> setAutoGrow(true);
$image 	= new InputImage();
$image -> setClass("image.roundedListImage");
$desc 	= new InputText(300);
$desc -> setClass("input.textDescription");
$price 	= new InputText(10);
$price -> setClass("input.priceList");

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

function changeView(){
	$win -> Open("ProductThumbList",$cat);
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
	$prodUrl= new InputText();
	$id 	= new InputNumeric();
	$stock  = new InputText();

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