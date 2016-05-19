<?php

/**
 * KikApp PHP Magento APi
 * @author Kikapp
 * @version 1.0
 * Utils.php implement user api methods
 */
 

function loginUser( $email, $password, &$response ){
    
    umask(0);
    ob_start();
    Mage::getSingleton("core/session", array("name" => "frontend"));

    $websiteId = Mage::app()->getWebsite()->getId();
    $store = Mage::app()->getStore();
    $customer = Mage::getModel("customer/customer");
    $customer->website_id = $websiteId;
    $customer->setStore($store);
    try {
        $customer->loadByEmail($email);
        $session = Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
        $session->login($email, $password);
        $response['customerToken'] = encrypt_decrypt('encrypt', $session->getCustomer()->getId());
        
    }catch(Exception $e){
		$response['error'] = $e->getMessage();
    }

}

function checkUserToken($customerToken){
    
		$customerId  	= encrypt_decrypt('decrypt', $customerToken);
    	$customer 		= Mage::getModel('customer/customer')->load($customerId);

        if ($customer!=null && $customer->getWebsiteId()) {
	        Mage::init($customer->getWebsiteId(), 'website');
	        $session = Mage::getSingleton('customer/session');
	        $session->loginById($customerId);
	        if($session->isLoggedIn()) {
	        	return $customerId;
	        }else{
	        	return false;
	        }

	    }else{
			return false;
	    }
}

function logoutUser( $customerToken, &$response ){
	
	umask(0);
	Mage::app('default');
	$userId = checkUserToken($customerToken);

	if($userId!==false) {		
		$response['logout'] =  $userId;
		Mage::getSingleton('customer/session')->logout();		
	} else {
		$response["error"] =  "User is not logged";
	}

}

function removeProductFromCart( &$response, $productId, $customerToken ){
	
	umask(0);
	Mage::app('default');
	$store = Mage::getSingleton('core/store')->load(1);
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}
	
	if($userId!==false) {
	
		try{
			$response['error'] = "1";
			$cartHelper = Mage::helper('checkout/cart');
			$items = $cartHelper->getCart()->getItems(); 
			
			foreach ($items as $item) 
			{
			   	
			   if($item->getProductId()==$productId){
			   		$quote = Mage::getSingleton('checkout/session')->getQuote();
			   		$quote->removeItem($item->getId())->save();
				   	$response['error'] = "0";
					break;
			   }
			}
			
		} catch (Exception $e) {
		    $response['error'] = $e->getMessage();
		}
				
		
	} else {
		$response["error"] =  "User token is not valid";
	}
	

	
}

function addProductToCart( &$response, $productId, $qty, $customerToken ){
	
	umask(0);
	Mage::app('default');
	$store = Mage::getSingleton('core/store')->load(1);
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}
	
	if($userId!==false) {
	
		try{

			$product = Mage::getModel('catalog/product')->load($productId);
			if ((int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty()<=0) {
				$response['error'] = "There is not stock for this product";

			}else{
				
				$cart = Mage::getSingleton('checkout/cart');
 				$items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
				if(count($items)==0){
					$cart->truncate();
					$cart->save();
					$cart->getItems()->clear()->save();	
				}
				
				$quote = Mage::getSingleton('checkout/session')->getQuote();
				$quote->addProduct($product, 1);
				$quote->collectTotals()->save();
				$quote->setCartWasUpdated(true);
				$response['error'] = "0";
				
			}
			
		} catch (Exception $e) {
		    $response['error'] = $e->getMessage();
		}
				
		
	} else {
		$response["error"] =  "User token is not valid";
	}

}


function getUserData($customerToken, &$response){
	
	umask(0);
	Mage::app('default');
	Mage::getSingleton('core/session', array('name' => 'frontend'));
	$sessionCustomer = Mage::getSingleton("customer/session");
		
	$userId = checkUserToken($customerToken);
				
	if($userId!==false) {
		$response[] = Mage::getModel('customer/customer')->load($userId)->getData();
	} else {
		$response["error"] =  "User token is not valid";
	}
	
}

function createUser(&$response, $data){
	
	umask(0);
	Mage::app('default');
	Mage::getSingleton('core/session', array('name' => 'frontend'));	
	$websiteId = Mage::app()->getWebsite()->getId();
	$store = Mage::app()->getStore();
	 
	$customer = Mage::getModel("customer/customer");
	$customer   ->setWebsiteId($websiteId)
	            ->setStore($store)
	            ->setFirstname($data["name"])
	            ->setLastname($data["lastname"])
	            ->setEmail($data["email"])
	            ->setPassword($data["password"]);
	 
	try{
	    $customer->save();
	    $response = array('id' => $customer->getId(), "error"=>0);
	    
	}
	catch (Exception $e) {
	    $response["error"] =  $e->getMessage();
	}			
	
}

function updateUser($customerToken, &$response, $data){

	umask(0);
	Mage::app('default');	
	$websiteId = Mage::app()->getWebsite()->getId();
	$store = Mage::app()->getStore();
	$userId = checkUserToken($customerToken);
				
	if($userId!==false) {
		
	    $customer = Mage::getModel('customer/customer')->load($userId);
	    $customer->setWebsiteId($websiteId)
	            ->setStore($store)
	            ->setFirstname($data["name"])
	            ->setLastname($data["lastname"])
	            ->setEmail($data["email"]);
	 
		try{
		    $customer->save();
		    $response = array('id' => $customer->getId(), "error" => 0);
		    
		}
		catch (Exception $e) {
		    $response["error"] =  $e->getMessage();
		}

	}else {
		$response["error"] =  "User token is not valid";
	}

	
}

function clearUserCart($customerToken){
	
	umask(0);
	Mage::app('default');
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}
				
	if($userId!==false){
						
		Mage::getSingleton('checkout/cart')->truncate();
		Mage::getSingleton('checkout/cart')->save();
		return true;

	} else {
		return false;
	}
	
}

function userShoppingCart($customerToken, &$response){
	
	umask(0);
	Mage::app('default');
	$store = Mage::getSingleton('core/store')->load(1);
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}

	if($userId!==false) {
	
		$items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
		foreach($items as $item) {

			$collection = Mage::getModel('catalog/product')
		    ->getCollection()
		    ->addAttributeToSelect('*')
		    ->addAttributeToFilter('status', array('eq' => 1)) //show only enabled
		    ->setOrder('id', 'ASC')
		    ->addAttributeToFilter('entity_id', $item->getProductId())
		    ->setCurPage(1);
			
			$url = "";

		    foreach($collection as $productImg){ 
		        
		        $productImg->load('media_gallery');
		        $images = $productImg->getMediaGalleryImages();
		        foreach($images as $image){
		            	
		            if(is_null($url) || $url==''){			            
		            	$url = str_replace(REPLACEINURL, "", $image['url']);
					}else{
						break;
					}
					
		        }
								
		    }
			
			if(is_null($url) || $url==''){
			        $url = str_replace(REPLACEINURL, "", $item->getImageUrl());
			}
			
			if(is_null($url) || $url==''){
			        $url = "http://demo.kikapptools.com/magento/apiKikApp/img/appicon.png";
			}

			$response[] = array(
                'id'              => $item->getProductId(),
                'name'            => $item->getName(),
                'sku'             => $item->getSku(),
                'quantity'		  => $item->getQty(),
                'price'		  	  => $item->getPrice(),
                'url'			  => $url
            );								
		}

	} else {
		$response["error"] =  "User token is not valid";
	}
	
}

function checkOutCustomerCart($customerToken, &$response){
	
	umask(0);
	Mage::app('default');
	$userId = checkUserToken($customerToken);
	$store = Mage::getSingleton('core/store')->load(1);
				
	if($userId!==false){

		$items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
		if(!empty($items)){
		
			try{
				
				$quote = Mage::getSingleton('checkout/session')->getQuote();
				$addressData = array('firstname' => 'Test', 'lastname' => 'Test', 'street' => 'Sample Street 10', 'city' => 'California', 
				'postcode' => '90034', 'telephone' => '123456', 'country_id' => 'US', 'region_id' => '12');

				$billingAddress = $quote -> getBillingAddress() -> addData($addressData);
				$shippingAddress = $quote -> getShippingAddress() -> addData($addressData);
				
				$shippingAddress -> setCollectShippingRates(true) -> collectShippingRates() -> setShippingMethod('flatrate_flatrate') -> setPaymentMethod('checkmo');
				
				$quote -> getPayment() -> importData(array('method' => 'checkmo'));
				$quote -> collectTotals();
				$quote -> reserveOrderId();
				$quote -> save();
				$service = Mage::getModel('sales/service_quote', $quote);
				$service -> submitAll();
				$orderId = $service->getOrder()->getId(); 
				 
				// Resource Clean-Up
				$quote = $customer = $service = null;
				 
				// Finished
				clearUserCart(null);
				$response['orderID'] = $orderId;
			}
			catch (Exception $e) {
			    $response["error"] =  $e->getMessage();
			}

		} else {
			$response["error"] =  "customers cart is empty";
		}

	} else {
		$response["error"] =  "User token is not valid";
	}
	
}


function userOrders($customerToken, &$response){
			
	umask(0);
	Mage::app('default');		
	$userId = checkUserToken($customerToken);
				
	if($userId!==false) {
		
	    $field          = 'customer_id';
	    $collection     = Mage::getModel("sales/order")->getCollection()->addAttributeToSelect('*')->addFieldToFilter($field, $userId);
						   
	    foreach ($collection as $order) {
		    
			$orderedItems = $order->getAllVisibleItems();
			$response[] = array(
                'id'              		=> $order->getId(),
                'status'          		=> $order->getStatus(),
                'date'    				=> $order->getCreatedAt(),
                'order_currency_code'	=> $order->getOrderCurrencyCode(),
                'email'           		=> $order->getCustomerEmail(),
                'price'			  		=> strip_tags($order->formatPrice($order->getGrandTotal())),
                'shipping'		  		=> $order->getShippingAddress()->getName(),
                'totalProducts'	  		=> count($orderedItems)
            );			
			
		}

	}else {
		$response["error"] =  "User token is not valid";
	}
		
}

function userOrderProducts($customerToken, $orderId, &$response){
			
	umask(0);
	Mage::app('default');
	$userId = checkUserToken($customerToken);
				
	if($userId!==false) {
		
	    $order     		= Mage::getModel("sales/order")->load($orderId);	        
		$orderedItems 	= $order->getAllVisibleItems();
		
		foreach ($orderedItems as $item) {
			$response[] = array(
	            'productId'              => $item->getData('product_id')
	        );
		}

	}else {
		$response["error"] =  "User token is not valid";
	}
		
}

function getUserShoppingCartTotal($customerToken, &$response){
	
	umask(0);
	Mage::app('default');
	$store = Mage::getSingleton('core/store')->load(1);
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}

	if($userId!==false) {
		
		$total = 0;
		$items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
		foreach($items as $item) {			    
			$total = $total + intval(($item->getQty())*($item->getPrice()));
		}
		$response = array('total' => $total);

	} else {
		$response["error"] =  "User token is not valid";
	}
	
}

function getProductOptions($productId){
	
	$product = Mage::getModel("catalog/product")->load($productId);
	$theoptions = $product->getOptions();
	foreach($theoptions as $opval)
	{
		echo $opval->getOptionId().'|'.$opval->getTitle().'<br>';
	}
	
}

function getActivPaymentMethods()
{
   $payments = Mage::getSingleton('payment/config')->getActiveMethods();
   return $payments;
} 


function encrypt_decrypt($action, $string) {
    
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

?>