<?php

/**
 * KikApp PHP Magento APi
 * @author Kikapp
 * @version 1.0
 * Utils.php implement user api methods
 */
 

function loginUser( $email, $password, &$response ){
   // ob_start();
    //$websiteId = Mage::app()->getWebsite()->getId();
    //$store = Mage::app()->getStore();
    //$customer = Mage::getModel("customer/customer");
    //$customer->website_id = $websiteId;
    //$customer->setStore($store);
    try {
        //$customer->loadByEmail($email);
        
      //  $sessionChk = Mage::getSingleton('checkout/session');
		//$session->start();
        
        $session = Mage::getSingleton('customer/session');
        $session->login($email, $password);
        //$session = Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
        
		/*
		$sessionUpdate = Mage::getSingleton('checkout/session');
		$sessionUpdate->start();
		$sessionUpdate->setCartWasUpdated(true);
		*/
		
		if ($session->isLoggedIn()) {
        		
        	$cart = Mage::getSingleton('checkout/cart');
			$cart->init();
			$cart->save();
		
        	$response['customerToken'] = encrypt_decrypt('encrypt', $session->getCustomer()->getId());
			$response['userName'] = $session->getCustomer()->getFirstname();
		}else{
			$response['error'] = "Login error";
		}
        
    }catch(Exception $e){
		$response['error'] = $e->getMessage();
    }

}

function createUser(&$response, $data){
		
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
		createUserAddress($customer);
	    $response = array('id' => $customer->getId(), "error"=>0);
	    
	}
	catch (Exception $e) {
	    $response["error"] =  $e->getMessage();
	}
	
}

function createUserAddress($customer){
	
	try{
	    $_custom_address = array('firstname' => 'Test', 'lastname' => 'Test', 'street' => 'Sample Street 10', 'city' => 'California', 
					'postcode' => '90034', 'telephone' => '123456', 'country_id' => 'US', 'region_id' => '12');
		
		
	    $customAddress   = Mage::getModel('customer/address');
	    $customAddress->setData($_custom_address)
	        ->setCustomerId($customer->getId()) // this is the most important part
	        ->setIsDefaultBilling('1')  // set as default for billing
	        ->setIsDefaultShipping('1') // set as default for shipping
	        ->setSaveInAddressBook('1');
	    $customAddress->save();
	}
	catch (Exception $e) {
	    throw $e;
	}
	
}

///var/www/html/demo.kikapptools.com/magento/app/code/core/Mage/Customer/Model

function checkUserToken($customerToken){
		
	$customerId  	= encrypt_decrypt('decrypt', $customerToken);
	$customer 		= Mage::getModel('customer/customer')->load($customerId);
	
    if ($customer!=null && $customer->getWebsiteId()) {
        Mage::app($customer->getWebsiteId(), 'website');
        $session = Mage::getSingleton('customer/session');
        $session->loginById($customerId);
		
		/*
		 * $customerData = $customer->getData();
    	foreach ($customerData as $key => $value) {
			error_log("*".$key."-".$value);	
		}
		 * */
		
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
	
	$userId = checkUserToken($customerToken);

	if($userId!==false) {		
		$response['logout'] =  $userId;
		Mage::getSingleton('customer/session')->logout();		
	} else {
		$response["error"] =  "User is not logged";
	}

}

function removeProductFromCart( &$response, $productId, $customerToken ){
	
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
			
			$quote = Mage::getSingleton('checkout/session')->getQuote();
			$quote->collectTotals()->save();
			
		} catch (Exception $e) {
		    $response['error'] = $e->getMessage();
		}
				
		
	} else {
		$response["error"] =  "User token is not valid";
	}
	

	
}

function addProductToCart( &$response, $productId, $qty, $customerToken ){
		
	
		     
	$store = Mage::getSingleton('core/store')->load(1);
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}

	
	if($userId!==false) {
	
		try{
			Mage::getSingleton("core/session", array("name" => "frontend"));
			$product = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore()->getId())->load($productId);
					 
			if ((int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty()<=0) {
				$response['error'] = "There is not stock for this product";

			}else{
				
				/** @var \Mage_Core_Model_Session */
				$coresession = Mage::getSingleton('core/session', array('name'=>'frontend'));
				
				/** @var \Mage_Checkout_Model_Session $session */
				$session = Mage::getSingleton('checkout/session');
				$session->start();
				
				/** @var \Mage_Checkout_Model_Cart $cart */
				$cart = Mage::getSingleton('checkout/cart');
				$cart->init();
				
				$errors = array();
			    try {
			
			        $params = array(
			            'product' => $productId,
			            'qty' => 1
			        );
			
			      
			        $cart->addProduct($product, $params);
			    } catch (\Exception $e) {
			        $errors[$product['id']] = $e->getMessage() . ' (Product: ' . print_r($product, true) . ')';
			    }
				
				$cart->save();
				$session->setCartWasUpdated(true);
				
				if ($errors) {
				    var_dump($errors);
				    exit;
				}
				
				if ($cart->getQuote()->getHasError()) {
				    var_dump($cart->getQuote()->getHasError());
				    exit;
				}
				/*
				$guarda = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
				
				
				foreach ($guarda as $item) {
					error_log($item->getData('product_id'));
				}
				*/
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
	
	$sessionCustomer = Mage::getSingleton("customer/session");	
	$userId = checkUserToken($customerToken);
				
	if($userId!==false) {
		$response[] = Mage::getModel('customer/customer')->load($userId)->getData();
	} else {
		$response["error"] =  "User token is not valid";
	}
	
}

function updateUser($customerToken, &$response, $data){
	
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

function clearUserCart($customerToken, &$response){
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}
				
	if($userId!==false){
			
		$cart = Mage::getSingleton('checkout/cart');			
		$cart->truncate();
		$cart->save();
		$cart->init();
		$cart->save();
		if(!isset($response['error'])){
			$response['error'] = "0";
		}		

	} else {
		$response['error'] = "1";
	}
	
}

function userShoppingCart($customerToken, &$response){
	
	$store = Mage::getSingleton('core/store')->load(1);
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}

	if($userId!==false) {
	
		Mage::getSingleton("core/session", array("name" => "frontend"));
		$session = Mage::getSingleton('customer/session');
	    $session->loginById($userId);
		
		$items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
		
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
	
	$userId = checkUserToken($customerToken);
	$store = Mage::getSingleton('core/store')->load(1);
				
	if($userId!==false){
			
		$customer = Mage::getModel('customer/customer')->load($userId);
		$quote = Mage::getModel('sales/quote')->setSharedStoreIds($storeIds)->loadByCustomer($customer);
		$items = $quote->getAllVisibleItems();
		
		if(!empty($items)){
		
			try{
				
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
			
				$response['error'] = "Checkout done, the order #".$orderId." was created!";
				clearUserCart(null, $response);
			}
			catch (Exception $e) {
			    $response["error"] =  $e->getMessage();
			}

		} else {
			$response["error"] =  "Your cart is empty";
		}

	} else {
		$response["error"] =  "User token is not valid";
	}
	
}


function userOrders($customerToken, &$response){
			
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
	
	//$store = Mage::getSingleton('core/store')->load(1);
	
	if(!is_null($customerToken)){
		$userId = checkUserToken($customerToken);
	}else{
		$userId = true;
	}

	if($userId!==false) {
		
		$tax = 0;
		$shipping = 0;
		$subTotal = 0;
		$grandTotal = 0;
		
		$quote = Mage::getSingleton('checkout/session')->getQuote();
		$items = $quote->getAllVisibleItems();
		if(!empty($items)){
				
			$totals =  $quote->getTotals();
			$tax = $quote->getShippingAddress()->getData('tax_amount');
			$shipping = isset($totals["shipping"])?$totals["shipping"]->getValue():0;
			//$grandTotal = $totals["grand_total"]->getValue();
			
			foreach($items as $item) {			    
				$subTotal = $subTotal + intval(($item->getQty())*($item->getPrice()));
			}
			$grandTotal = $subTotal+$tax+$shipping;
		}
		$response = array('total' => "$ ".number_format($grandTotal,2), 
						  'tax' => number_format($tax,2), 
						  'shipping'=> number_format($shipping, 2), 
						  'subtotal'=> number_format($subTotal, 2)
						);

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