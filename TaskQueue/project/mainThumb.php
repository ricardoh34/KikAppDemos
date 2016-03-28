<?php 

/***
 * Main list
 */

$win = new SDPanel();
$win->setCaption("Tasks list");

$statusList = new InputNumeric();
Data::getParm($statusList);

//Add button to action bar
$bar = new ActionBar();
$btn_img = new ButtonBar();
$btn_img->setCaption("");
$btn_img->setImage("img/Android/hdpi/ic_list.png");
$btn_img->setClass("image.actionBarIcon");
$btn_img->onTap(changeViewType());
$bar->addControl($btn_img);
$win->addControl($bar);


$table = new Table();
$table->setClass("table.Menu");


$list = new Grid();
$list->addData(load_grid());
//$list->addSearch($name);

$table_grid = new Table();
$table_grid->setClass("table.DetailMain");

$id = new InputNumeric();
$name = new InputText();
$name->setClass("input.Title");
/*
$status   = new InputBoolean();
$status->setLabelCaption("Status");
$status->setLabelPosition("Left");
*/
$statusLabel = new Label();
$statusLabel->setCaption("Done");
$statusLabel->setClass("input.Common");
$statusLabel->onTap(changeStatus());
/*
$statusButton = new ButtonBar();
$statusButton->setCaption("change");
//$statusButton->setImage("img/ico_thumb.png");
$statusButton->setClass("buttonform");
$statusButton->onTap(changeViewType());
*/

$image 	= new InputImage();
$image->setClass("image.ListItem");

$table_list = new Table();

$table_list->addControl($image,1,1,1,2,"Left","Middle");
$table_list->addControl($name,1,2,1,6,"Left","Middle");
$table_list->addControl($statusLabel,1,3,1,1,"Right","Middle");
//$table_list->addControl($status,1,3,1,1,"Right","Middle");
//$table_list->addControl($statusButton,1,4,1,1,"Right","Middle");

$table_grid->addControl($table_list,1,1);
$table_grid->onTap(detail());
$table_grid->onLongTap(delete());

$list->addControl($table_grid);
$table->addControl($list);
$win->addControl($table);
$win->Render();

function load_grid(){
		
	//Make JSON request	
	$url = "http://demo.kikapptools.com/taskManager/crud/getTask.php";
	$httpClient = new httpClient();
	$result = $httpClient->Execute('GET' ,$url);

	//Cast response data type
	$struct = array(
		array(
			"id" 	 => DataType::Numeric(6),
			"task" 	 => DataType::Character(150),
			"status" => DataType::Numeric(1),
			"image"	 => DataType::Character(200)
		)
	);

	Data::FromJson($struct,$result);
	
	//Add result to screen vars 
	foreach ($struct as $items){
		$id 	= $items['id'];
		$name 	= $items['task'];
		$status = $items['status'];
		$image  = "http://demo.kikapptools.com/taskManager/services/".$items['image'];
	}	
}

function changeViewType(){
	$win->Open("mainList");
}

function changeStatus(){
	//$win->Open("ListadoThumb",$cat);
	echo "changeStatus";
}

function detail(){
	echo $id;
	$win->Open("TaskDetail",$id);
}

function delete(){
	$isOk = new InputBoolean();
	$isOk = Interop::Confirm("Do you want to delete this task ?");
	$urlDelete = "http://demo.kikapptools.com/taskManager/crud/deleteTask.php?taskID=".$id;
	
	echo $id;
	
	if($isOk == true){
		
		$httpClient = new httpClient();
		$result = $httpClient->Execute('GET' ,$urlDelete);
		
		$sdtError = array("response"=>DataType::Character(100));
		Data::FromJson($sdtError,$result);
		
		$rsValue = new InputText(100);
		$rsValue = $sdtError['response'];
		
		//$win->setCaption("Guarda");
		
		echo $rsValue;
		$win->Refresh();
		
		//return;
	}
}


?>