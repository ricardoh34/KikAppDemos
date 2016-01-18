<?php 
/***
 * My shopping cart
 */

$win = new SDPanel();
$win -> setCaption("My cart");

$token = new InputText(80);
Data::getParm($token);

$acb = new ActionBar();
$btn_delete = new ButtonBar();
$btn_delete -> setCaption("Delete");
$btn_delete -> onTap(delete());
$acb -> addControl($btn_delete);

$win -> addControl($acb);

$table = new Table();

$list = new Grid();
$list -> addData(load_grid());
$list -> onTap(action());

$id = new InputNumeric();
$name = new InputText();
$name -> setClass("inputtextTitlewhite");

$image = new InputImage();
$image -> setClass("imagecheckout");

$price = new InputNumeric(12,4);
$price -> setClass("input.textListwhite");

$table_desc = new Table();
$table_desc -> addControl($name,1,1);
$table_desc -> addControl($price,2,1,1,1,"Right","Bottom");


$table_list = new Table();
$table_list -> setColumnsStyle("95dip;100%");

$table_list -> addControl($image,1,1);
$table_list -> addControl($table_desc,1,2);

$list -> addControl($table_list,1,1);

$table -> addControl($list,1,1);

$table_result = new Table();
$table_result -> setClass("table.checkout");
$table_result -> setRowsStyle("pd;70dip");

$lbl_total = new Label();
$lbl_total -> setCaption("Total: ");

$total = new InputNumeric(12,4);
$total -> setReadOnly(true);
$total -> setClass("input.textPrice");
//$total -> setLabelPosition("Left");
//$total -> setLabelCaption("$");

$btn_checkout = new Button();
$btn_checkout -> setClass("buttonred");
$btn_checkout -> setCaption("CHECKOUT NOW");
$btn_checkout -> onTap(checkout());

$table_total = new Table();
$table_total -> addControl($lbl_total,1,1,1,1,"Left","Middle");
$table_total -> addControl($total,1,2,1,1,"Right","Middle");

$table_result -> addControl($table_total,1,1);
$table_result -> addControl($btn_checkout,2,1,1,1,"Left","Top");

$table -> addControl($table_result,2,1,1,1,"Left","Bottom");

$win -> addControl($table);
$win -> Render();

function action(){
	echo "hay que cambie el detalle del producto...";
}

function load_grid(){	
	
	$url = "http://demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=userShoppingCart&customerToken=".$token;
	$httpClient = new httpClient();
	$result_2 = $httpClient -> Execute('GET',$url);
		
	
	$shoppingCart = array(
		array(
				"id"=>DataType::Numeric(),
				"name" => DataType::Character(),
				"sku" => DataType::Character(50),
				"quantity" => DataType::Numeric(4),
				"price" => DataType::Numeric(12,4),
				"url" => DataType::Character(350)
				
		)
	);

	Data::FromJson($shoppingCart,$result_2);

	foreach ($shoppingCart as $cart){
		$name 	= $cart["name"];
		$price 	= $cart["price"]; 
		$image 	= $cart["url"];			
	}	

}

function delete(){		
	
	$isOk = new InputBoolean();
	$isOk = Interop::Confirm("Do you want to delete cart ?");
	
	if($isOk == true){	
		$url = "http://demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=clearUserCart&customerToken=".$token;
		$httpClient = new httpClient();
		$result = $httpClient -> Execute('GET',$url);
		
		AndroidAction::GoHome();
	}
}

function checkout(){
	echo "check out";
	//http://www.demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=checkOutCustomerCart&customerToken=token
}

function refresh(){	
	$url = "http://demo.kikapptools.com/magento/apiGecko/clientes.php?metodo=userCartTotal&customerToken=".$token;
	$httpClient = new httpClient();
	$result = $httpClient -> Execute('GET',$url);
	
	$struct = array("total"=>DataType::Numeric());
	
	Data::FromJson($struct,$result);
	
	$total = $struct['total'];
}

?>