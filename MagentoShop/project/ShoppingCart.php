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
$table -> setClass("table.cartMain");

$list = new Grid();
//$list->setEmptyGridBackgroundImage("img/emptyCart.png");
$list->setEmptyGridBackgroundClass("table.gray");
$list->setEmptyGridText("Your cart is empty");
$list->setPullToRefresh(true);
$list->addData(load_grid());
$list->onTap(view_image());


$id = new InputNumeric();
$name = new InputText();
$name -> setClass("inputtextTitlewhite");


$lbl_qty = new Label();
$lbl_qty -> setCaption("Qty: ");
$qty = new InputText();
$qty -> setClass("inputtextTitlewhite");

$image = new InputImage();
$image -> setClass("imagecheckout");

$lbl_price = new Label();
$lbl_price -> setCaption("$ ");

$price = new InputNumeric(12,4);
$price -> setClass("inputtextTitlewhite");

$table_desc = new Table();
$table_desc -> setColumnsStyle("35dip;15dip;15dip;100%");
$table_desc -> addControl($name,1,1,1,4);
$table_desc -> addControl($lbl_qty,2,1,1,1, "Left","Top");
$table_desc -> addControl($qty,2,2,1,1, "Left","Top");
$table_desc -> addControl($lbl_price,2,3,1,1,"Left","Top");
$table_desc -> addControl($price,2,4,1,1,"Left","Top");
$table_desc->onLongTap(removeProduct());

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


function removeProduct(){
	$isOk = new InputBoolean();
	$isOk = Interop::Confirm("Do you want to remove this product from cart ?");
	
	if($isOk == true){
		
		$token = new InputText(80);
		$token = StorageAPI::Get("token");
		
		if($token != null){		
			ProgressIndicator::Show();
			$url_cart = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=removeProductFromCart&productId=".$id."&customerToken=".$token;
			$hc = new httpClient();
			$rs_cart = $hc -> Execute("GET",$url_cart);
				
			$sdt_rs = array("error"=>DataType::Character(300));
			
			Data::FromJson($sdt_rs,$rs_cart);
					
			$rs = new InputText(300);
			$rs = $sdt_rs['error'];
	
			ProgressIndicator::Hide();
			
			if($rs == "0"){
				$win->Refresh();
			}else{
				echo $rs;
			}
		}else{
			$win -> Open("Login");
		}
	
	}
}

function load_grid(){
	
	ProgressIndicator::Show();	
	
	$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userShoppingCart&customerToken=".$token;
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
		$id 	= $cart["id"];	
		$name 	= $cart["name"];
		$qty 	= $cart["quantity"];
		$price 	= $cart["price"]; 
		$image 	= $cart["url"];			
	}	
	
	ProgressIndicator::Hide();

}

function delete(){		
	
	
	$isOk = new InputBoolean();
	$isOk = Interop::Confirm("Do you want to delete your cart?");
	
	if($isOk == true){
		ProgressIndicator::Show();	
		$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=clearUserCart&customerToken=".$token;
		$httpClient = new httpClient();
		$result = $httpClient -> Execute('GET',$url);
		AndroidAction::GoHome();
		ProgressIndicator::Hide();
	}
}

function checkout(){

	ProgressIndicator::Show();
	$url_cart = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=checkOutCustomerCart&customerToken=".$token;
	$hc = new httpClient();
	$rs_cart = $hc -> Execute("GET",$url_cart);
	$sdt_rs = array("error"=>DataType::Character(50));
		
	Data::FromJson($sdt_rs,$rs_cart);
			
	$rs = new InputText(50);
	$rs = $sdt_rs["error"];

	ProgressIndicator::Hide();
	
	if($rs == null){
		echo "Checkout done!! ";
	}else{
		echo $rs;
	}
	
	$win->refresh();	
	
}

function refresh(){	
	$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userCartTotal&customerToken=".$token;
	$httpClient = new httpClient();
	$result = $httpClient -> Execute("GET",$url);
	
	$struct = array("total"=>DataType::Numeric());
	
	Data::FromJson($struct,$result);
	
	$total = $struct["total"];
		
	if($total==0){
		$table_result->setVisible(false);
	}else{
		$table_result->setVisible(true);
	}
	
}

function view_image(){
	$win -> Open("ProductImage",$id);
}

?>