<?php

/**
 * KikApp PHP Magento APi
 * @author Kikapp
 * @version 1.0
 * Categories.php retrieve categories to app
 */

define('ROOT_CATEGORY', 2);
$cId = (isset($_GET['cId']) && $_GET['cId']!=null)?intval($_GET['cId']):ROOT_CATEGORY;

require_once('../app/Mage.php');
Mage::app();

if($cId!=""){ //busca sub categorias
	$cat = Mage::getModel('catalog/category')->load($cId);
	$subcats = $cat->getChildren();
	
	foreach(explode(',',$subcats) as $subCatid){
			
		$_category = Mage::getModel('catalog/category')->load($subCatid);
		if($_category->getIsActive()) {
	    	
			$json[] = array(
                'id'                    => $_category->getId(),
                'name'                  => str_replace('"',"", $_category->getName()),
                'description'           => str_replace('"',"", $_category->getDescription()),                
                'thumb'                 => (!is_null($_category->getThumbnail()))?Mage::getBaseUrl('media').'catalog/category/'.$_category->getThumbnail():''
            );
			
			
  		}
	}
	
	$arr = json_encode($json);
    echo str_replace("\\","", $arr);
}

?>