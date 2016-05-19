<?php

/**
 * KikApp PHP Magento APi
 * @author Kikapp
 * @version 1.0
 * api.php retrieve api methods
 */


require_once('Utils.php');
session_start();
$metodo = (isset($_GET['metodo']) && $_GET['metodo']!=null)?$_GET['metodo']:'';
$response = array();
define("REPLACEINURL", "apiKikApp/");


if($metodo!=''){ 
    
	require_once('../app/Mage.php');
	Mage::app('default');

    switch ($metodo) {
        
        case 'login': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=login
            
            $email     = (isset($_POST['email']))?$_POST['email']:'';
            $password  = (isset($_POST['password']))?$_POST['password']:'';
            if ( $email == '' ) {
                $response['error'] = 'Email is required';
                break;
            }
            if ( $password == '' ) {
                $response['error'] = 'Password is required';
                break;
            }
            
            loginUser( $email, $password, $response );
        
		break;
		
		case 'logout': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=logout&customerToken=token
			
			$customerToken     = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
            logoutUser( $customerToken, $response );
			
		break;
		case 'userData': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userData&customerToken=token

		    $customerToken     = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
            getUserData( $customerToken, $response );
						
		break;
		case 'userOrders': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userOrders&customerToken=token
			
		    $customerToken     = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
            userOrders( $customerToken, $response );			
		
		break;
		case 'userShoppingCart': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userShoppingCart&customerToken=token
			
		    $customerToken     = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
            userShoppingCart($customerToken, $response);			
		
		break;
		case 'userOrderProducts': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userOrderProducts&orderId=179&customerToken=token
			
			$orderId           = (isset($_GET['orderId']))?$_GET['orderId']:'';
            $customerToken     = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $orderId == '' ) {
                $response['error'] = 'El orderId no puede ser vacio';
                break;
            }
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
			
		    userOrderProducts( $customerToken, $orderId, $response);			
		
		break;
        case 'userCartTotal': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=userCartTotal&customerToken=token
            
            $customerToken     = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
            getUserShoppingCartTotal($customerToken, $response);         
        
        break;
        case 'addProductToCart': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=addProductToCart&productId=881&qty=1&customerToken=token
			
		    $productId     = (isset($_GET['productId']))?$_GET['productId']:'';
		    $qty     	   = (isset($_GET['qty']))?$_GET['qty']:'';
            $customerToken = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            			
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
            if ( $productId == '' ) {
                $response['error'] = 'productId is required';
                break;
            }
            if ( $qty == '' ) {
                $response['error'] = 'Quantity is required';
                break;
            }

		    addProductToCart($response, $productId, $qty, $customerToken);
			
		break;
		
		case 'removeProductFromCart': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=removeProductFromCart&productId=881&customerToken=token
			
		    $productId     = (isset($_GET['productId']))?$_GET['productId']:'';
		    $customerToken = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            			
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }
            if ( $productId == '' ) {
                $response['error'] = 'productId is required';
                break;
            }
            
			removeProductFromCart($response, $productId, $customerToken);
			
		break;
		
        case 'clearUserCart': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=clearUserCart&customerToken=token

            $customerToken = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }

            clearUserCart($customerToken);
        
        break;
		case 'createUser': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=createUser, crea un usuario
		    
		    $name     	= (isset($_POST['name']))?$_POST['name']:'';
		    $lastname   = (isset($_POST['lastname']))?$_POST['lastname']:'';
		    $email  	= (isset($_POST['email']))?$_POST['email']:'';
		    $password   = (isset($_POST['password']))?$_POST['password']:'';
            
            if ( $name == '' ) {
                $response['error'] = 'Please provide your name';
                break;
            }else if ( $lastname == '' ) {
                $response['error'] = 'Please provide your last name';
                break;
            }else if ( $email == '' ) {
                $response['error'] = 'Please provide your email address';
                break;
            }else if ( $password == '' ) {
                $response['error'] = 'Please choose a password for your account';
                break;
            }

            $userData = array('name' => $name, 'lastname' => $lastname, 'email' => $email, 'password' => $password);
		    createUser($response, $userData);
		
		break;

		case 'updateUser': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=updateUser, actualiza un usuario, precondicion el usuario debe estar logeado
		    
            $name       = (isset($_POST['name']))?$_POST['name']:'';
            $lastname   = (isset($_POST['lastname']))?$_POST['lastname']:'';
            $email      = (isset($_POST['email']))?$_POST['email']:'';
            $password   = (isset($_POST['password']))?$_POST['password']:'';
            $customerToken = (isset($_POST['customerToken']))?$_POST['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }else if ( $name == '' ) {
                $response['error'] = 'Please provide your name';
                break;
            }else if ( $lastname == '' ) {
                $response['error'] = 'Please provide your last name';
                break;
            }else if ( $email == '' ) {
                $response['error'] = 'Please provide your email address';
                break;
            }            

            $userData = array('name' => $name, 'lastname' => $lastname, 'email' => $email);
		    updateUser($customerToken, $response, $userData);
		
		break;
        case 'checkOutCustomerCart': //http://www.demo.kikapptools.com/magento/apiKikApp/api.php?metodo=checkOutCustomerCart&customerToken=token
                        
            $customerToken = (isset($_GET['customerToken']))?$_GET['customerToken']:'';
            
            if ( $customerToken == '' ) {
                $response['error'] = 'Customer Token is required';
                break;
            }

            checkOutCustomerCart($customerToken, $response);
        
        break;
			default:
            $response['error'] = 'no existe ese metodo';
            
        break;
    }
  
}else{
    $response['error'] = 'no existe ese metodo';
}

$response = json_encode($response);
$response = str_replace("\\","", $response);
print($response);
?>