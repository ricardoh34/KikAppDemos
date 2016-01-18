<?php 
/***
 * Detail of product
 */

$win = new SDPanel();
$win -> setCaption("Detail of product");

$id = new InputNumeric();
Data::getParm($id,$title,$desc,$price);

$table = new Table();
$table -> setClass("tableGray");

//Action Bar and Action Group.
$apb1 = new ActionBar();
$acg1 = new ActionGroup();
$acg1 -> setCaption("");
$acg1 -> setImage("img/option_list.png");

$btn_facebook = new ButtonBar();
$btn_facebook -> setCaption("Facebook");
$btn_facebook -> setImage("img/Android/hdpi/ic_facebook.png");
$btn_facebook -> onTap(facebook());

$btn_tw = new ButtonBar();
$btn_tw -> setCaption("Twitter");
$btn_tw -> setImage("img/Android/hdpi/ic_tw.png");
$btn_tw -> onTap(twitter());

$btn_sms = new ButtonBar();
$btn_sms -> setCaption("SMS");
$btn_sms -> setImage("img/Android/hdpi/ic_sms.png");
$btn_sms -> onTap(sms());

$btn_email = new ButtonBar();
$btn_email -> setCaption("EMail");
$btn_email -> setImage("img/Android/hdpi/ic_email.png");
$btn_email -> onTap(email());

$acg1 -> addControl($btn_facebook);
$acg1 -> addControl($btn_tw);
$acg1 -> addControl($btn_sms);
$acg1 -> addControl($btn_email);
$apb1 -> addControl($acg1);

//Add action bar to a SDPanel.
$win -> addControl($apb1);

$tableItem1 = new Table();
$tableItem1 -> setClass("tableTableDetailMain");

$title 	= new InputText(150);
$title -> setClass("inputtitleblack");
$title -> setAutoGrow(true);
$title -> setReadOnly(true);

$desc 	= new InputText(300);
$desc -> setClass("attributeTextGray");
$desc -> setReadOnly(true);
$desc -> setAutoGrow(true);

$price 	= new InputText(10);
$price -> setClass("inputTextPrice");
$price -> setReadOnly(true);

$grid = new HorizontalGrid();
$grid -> setRowsPerPagePortrait(1);
$grid -> setShowPageController(true);
$grid -> addData(load_image());
$grid -> onTap(view_image());

$table_grid = new Table();
$table_grid -> setRowsStyle("100%");
$table_grid -> setHeight("100%");

$image = new InputImage();
$image -> setClass("imageImageList");
$table_grid -> addControl($image,1,1);

$grid -> addControl($table_grid,1,1);

$btnAdd = new Button();
$btnAdd -> setClass("buttonred");
$btnAdd -> setCaption("Add to Cart");
$btnAdd -> onTap(add_cart());

$tableButtons = new Table();
$tableButtons -> addControl($btnAdd,1,1);

$tableItem1 -> addControl($title,1,1);
$tableItem1 -> addControl($price,2,1);
$tableItem1 -> addControl($desc,3,1);

$tableItem1 -> addControl($tableButtons,4,1);

$table -> addControl($grid,1,1);
$table -> addControl($tableItem1,2,1);
$table -> setRowsStyle("250dip;100%");
 
$win -> addControl($table);

function start(){
	//$win -> setCaption($title);
}

function load_image(){
	$url = "http://www.demo.kikapptools.com/magento/apiGecko/productos_imagen.php?pId=".$id;
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

function facebook(){
	Facebook::PostToWall($title,$title,$desc,'',$image);
}

function twitter(){
	Twitter::Tweet($title,$image);
}

function email(){
	Interop::SendEmail('',$title,$desc);
}

function sms(){
	Interop::SendSMS('',$title);
}

function view_image(){
	//echo "Product ".$title;	
	//$win -> Open("list_image_product",$id);
}

function add_cart(){
	$token = new InputText(80);
	$token = StorageAPI::Get("token");
		
	if($token != null){		
		ProgressIndicator::Show();
		$url_cart = "http://www.devxtend.com/Gecko/magento/apiGecko/clientes.php?metodo=addProductToCart&qty=1&productId=".$id."&customerToken=".$token;
		$hc = new httpClient();
		$rs_cart = $hc -> Execute("GET",$url_cart);
			
		$sdt_rs = array("error"=>DataType::Character(50));
		
		Data::FromJson($sdt_rs,$rs_cart);
				
		$rs = new InputText(50);
		$rs = $sdt_rs['error'];

		ProgressIndicator::Hide();
		
		if($rs == null){
			echo "Saved! ";
		}else{
			echo $rs;
		}
	}else{
		$win -> Open("Login");
	}	
}

?>