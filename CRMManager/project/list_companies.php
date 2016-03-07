<?php 
/**
 * List of companies
 * @author KikApp
 * @version 1.0
 */

$win = new SDPanel();
$win -> setCaption("Companies");

$mainTable = new Table();
$mainTable -> setRowsStyle("100%");
$mainTable -> setWidth("100%");
$mainTable -> setHeight("100%");

$canvas = new Canvas();
$canvas -> setWidth("100%");
$canvas -> setHeight("100%");

$img_add = new Image();
$img_add -> setImage("img/Android/hdpi/plus_circle.png");
$img_add -> onTap(insert());

$list = new Grid(); 
$table_list = new Table();
$table_list -> setColumnsStyle("86dip;100%");

//Controls in grid.
$image 	= new InputImage();
$image -> setClass("image.List");
$name 	= new InputText();
$name -> setClass("input.title");

$address = new InputText();

$table_info = new Table();
$table_info -> addControl($name,1,1,1,1,"Left","Bottom");
$table_info -> addControl($address,2,1);

$table_list -> addControl($image,1,1);
$table_list -> addControl($table_info,1,2);

$list -> addData(load_companies());
$list -> addSearch($name);
$list -> addControl($table_list,1,1);
$list -> onTap(action());

$canvas -> addPosition($list,"0","100%","0","100%","0","0",0);
$canvas -> addPosition($img_add,"100%","60dip","97%","60dip","15dip","3%",1);

$mainTable -> addControl($canvas,1,1);
$win -> addControl($mainTable);

function load_companies(){
	$url = "http://demo.kikapptools.com/CRMManager/crud/getCompanies.php";
	$httpClient = new httpClient();
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array(
		array(
				"CompanyId" => DataType::Numeric(8),
				"CompanyName" => DataType::Character(100),
				"CompanyImage" => DataType::Character(200),
				"CompanyAddress" => DataType::Character(200)
		)
	);
	
	Data::FromJson($struct,$result);
	
	$id = new InputNumeric();
	
	foreach ($struct as $company){
		$id 	= $company['CompanyId'];
		$image 	= $company['CompanyImage'];
		$name 	= $company['CompanyName'];
		$address = $company['CompanyAddress'];
	}
}

function insert(){
	$win -> Open("insert_company");
}

function action(){
	$win -> Open("view_company",$id);
}

?>