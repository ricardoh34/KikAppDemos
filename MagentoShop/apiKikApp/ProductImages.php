<?php

/**
 * KikApp PHP Magento APi
 * @author Kikapp
 * @version 1.0
 * ProductImages.php retrieve product images to app
 */

$pId = (isset($_GET['pId']) && $_GET['pId']!=null)?intval($_GET['pId']):'';

require_once('../app/Mage.php');
Mage::app('default');
$collection_to_encode = array();
define("REPLACEINURL", "apiKikApp/");

if($pId!=""){

    $collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('status', array('eq' => 1)) //show only enabled
    ->setOrder('id', 'ASC')
    ->addAttributeToFilter('entity_id', $pId);

    foreach($collection as $product){ 
        
        $product->load('media_gallery');
        $images = $product->getMediaGalleryImages();
        
        foreach($images as $image){            
                $json[] = array(
                    'url' => str_replace(REPLACEINURL, "", $image->getUrl())
                );			
        }
    }

    if(is_null($json)){
        $json[] = array(
                    'url' => str_replace(REPLACEINURL, "", $product->getImageUrl())
        );
    }

    $arr = json_encode($json);
    echo str_replace("\\","", $arr);
}

?>