<?php

/**
 * KikApp PHP Magento APi
 * @author Kikapp
 * @version 1.0
 * Products.php retrieve products to app
 */

$pId = (isset($_GET['pId']) && $_GET['pId']!=null)?intval($_GET['pId']):'';
$cId = (isset($_GET['cId']) && $_GET['cId']!=null)?intval($_GET['cId']):'';
$display = (isset($_GET['display']) && $_GET['display']!=null)?$_GET['display']:'';

require_once('../app/Mage.php');
Mage::app();
$collection_to_encode = array();
define("REPLACEINURL", "apiKikApp/");

if($display!=""){
    
    $collection = Mage::getResourceModel('reports/product_collection')
                   ->addAttributeToSelect('*')
                   ->setVisibility(array(1));

    $collection->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');                
    $collection->setOrder('rating_summary', 'asc');
    $collection->setPage(1, 6);

}else if($pId!="" && $cId!=""){
    
    $collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('status', array('eq' => 1)) //show only enabled
    ->setOrder('id', 'ASC')
    ->addAttributeToFilter('category_id', $cId)
    ->addAttributeToFilter('entity_id', $pId);

}else if($pId!=""){

    $collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('status', array('eq' => 1)) //show only enabled
    ->setOrder('id', 'ASC')
    ->addAttributeToFilter('entity_id', $pId);

}else if($cId!=""){

    $collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('status', array('eq' => 1)) //show only enabled
    ->setOrder('id', 'ASC')
    ->addAttributeToFilter('category_id', $cId);


}else{ //todos los productos
    
     $collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('status', array('eq' => 1)) //show only enabled
    ->setPageSize(50)
    ->setOrder('id', 'ASC')
    ->load();
}
    
	$nameAnt = "";
	//$json[] = array();
    foreach($collection as $product){ 

        $cat = $product->getCategoryIds();
        $cat = (!empty($cat))?$cat[0]:'';
		$stock = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId())->getQty();
		$stock = ($stock>0)?$stock:0;
		
		
		if($stock!=0 && $nameAnt!=$product->getName()){
        	$json[] = array(
                'id'                    => $product->getId(),
                'name'                  => str_replace('"',"", $product->getName()),
                'description'           => str_replace('"',"", $product->getShortDescription()),
                'pirce'                 => Mage::helper('core')->currency($product->getPrice(), true, false),
                'href'                  => str_replace(REPLACEINURL, "", $product->getProductUrl()),
                'thumb'                 => str_replace(REPLACEINURL, "", $product->getImageUrl()),
                'category'              => $cat,
                'stock'              	=> "Available ".$stock
            );
			$nameAnt = $product->getName();
		}
		
    }
	
	//$json = array_unique($json);

    $arr = json_encode($json);
    echo str_replace("\\","", $arr);

?>