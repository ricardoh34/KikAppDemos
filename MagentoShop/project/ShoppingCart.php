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
$table -> setRowsStyle("93%;160dip");

$list = new Grid();
$list->setEmptyGridBackgroundClass("table.gray");
$list->setEmptyGridText("Your cart is empty");
//$list->setPullToRefresh(true);
$list->addData(load_grid());
//$list->onTap(view_image());


$id = new InputNumeric();

$image = new InputImage();
$image -> setClass("image.checkout");

$name = new InputText();
$name -> setClass("input.titleProductNameWhite");

$qty = new InputText();
$qty -> setClass("inputtextTitlewhite");

$price = new InputNumeric(12,4);
$price -> setClass("inputtextTitlewhite");
$price -> setLabelPosition("Left");
$price -> setLabelCaption("X $");

$table_desc = new Table();
$table_desc -> setColumnsStyle("5%;5%;90%");
$table_desc -> addControl($name,1,1,1,4,"Left","Top");
$table_desc -> addControl($qty,2,1,1,1, "Left","Top");
$table_desc -> addControl($price,2,2,1,2,"Left","Top");


$table_list = new Table();
$table_list -> setColumnsStyle("95dip;100%");
$table_list -> addControl($image,1,1);
$table_list -> addControl($table_desc,1,2);
$table_list->onLongTap(removeProduct());
//$table_list->onTap(view_image());
$list -> addControl($table_list,1,1);


$table_result = new Table();
$table_result -> setClass("table.checkout");
$table_result -> setRowsStyle("90dip;70dip");

$lbl_total = new Label();
$lbl_total -> setCaption("Total: ");

$lbl_sub_total = new Label();
$lbl_sub_total -> setCaption("Sub total: ");

$lbl_shipping = new Label();
$lbl_shipping -> setCaption("Shipping: ");

$lbl_tax = new Label();
$lbl_tax -> setCaption("Tax: ");

$total = new InputText(10);
$total -> setReadOnly(true);
$total -> setClass("input.textPriceTotal");
//$total -> setLabelPosition("Left");
//$total -> setLabelCaption("$");

$subtotal = new InputText(10);
$subtotal -> setReadOnly(true);
$subtotal -> setClass("input.textPrice");

$shipping = new InputText(10);
$shipping -> setReadOnly(true);
$shipping -> setClass("input.textPrice");

$tax = new InputText(10);
$tax -> setReadOnly(true);
$tax -> setClass("input.textPrice");

$btn_checkout = new Button();
$btn_checkout -> setClass("buttonred");
$btn_checkout -> setCaption("CHECKOUT NOW");
$btn_checkout -> onTap(checkout());

$table_total = new Table();
$table_total -> setColumnsStyle("70%;30%");
$table_total -> setRowsStyle("25dip;25dip;25dip;40dip");

$table_total -> addControl($lbl_sub_total,1,1,1,1,"Left","Middle");
$table_total -> addControl($subtotal,1,2,1,1,"Right","Middle");
$table_total -> addControl($lbl_tax,2,1,1,1,"Left","Middle");
$table_total -> addControl($tax,2,2,1,1,"Right","Middle");
$table_total -> addControl($lbl_shipping,3,1,1,1,"Left","Middle");
$table_total -> addControl($shipping,3,2,1,1,"Right","Middle");
$table_total -> addControl($lbl_total,4,1,1,1,"Left","Middle");
$table_total -> addControl($total,4,2,1,1,"Right","Middle");

$table_result -> addControl($table_total,1,1);
$table_result -> addControl($btn_checkout,2,1,1,1,"Left","Top");

$table -> addControl($list,1,1);
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
		
		$sdt_rs = array("error"=>DataType::Character(50));
		Data::FromJson($sdt_rs,$result);
		$rs = new InputText(300);
		$rs = $sdt_rs["error"];
	
		ProgressIndicator::Hide();
		
		if($rs == "0"){
			AndroidAction::GoHome();
		}else{
			echo $rs;
		}
		
	}
}

function checkout(){

	ProgressIndicator::Show();
	$url_cart = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=checkOutCustomerCart&customerToken=".$token;
	$hc = new httpClient();
	$rs_cart = $hc -> Execute("GET",$url_cart);
	$sdt_rs = array("error"=>DataType::Character(50));
		
	Data::FromJson($sdt_rs,$rs_cart);
			
	$rs = new InputText(300);
	$rs = $sdt_rs["error"];

	ProgressIndicator::Hide();
	echo $rs;
	
	$win->Refresh();	
	
}

function Refresh(){
	$token = StorageAPI::Get("token");
	$url = "http://demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userCartTotal&customerToken=".$token;
	$httpClient = new httpClient();
	$result = $httpClient -> Execute("GET",$url);
	
	$struct = array("total"=>DataType::Character(10), "tax"=>DataType::Character(10), "shipping"=>DataType::Character(10), "subtotal"=>DataType::Character(10));
	
	Data::FromJson($struct,$result);
	
	$total    = $struct["total"];
	$shipping = $struct["shipping"];
	$tax 	  = $struct["tax"];
	$subtotal = $struct["subtotal"];
		
	if($subtotal==0){
		$table_result->setVisible(false);
	}else{
		$table_result->setVisible(true);
	}
	
}
/*
function view_image(){
	$win -> Open("ProductImage",$id, $name);
}*/

?>