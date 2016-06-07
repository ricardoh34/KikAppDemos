<?php 

/***
 * Task List (Done)
 */

$win = new SDPanel();
$win->setCaption("Done Tasks");

$statusList = new InputNumeric();
$period = new InputNumeric();
Data::getParm($period, $statusList);

//Add button to action bar
$bar = new ActionBar();
$barGroup = new ActionGroup();
$barGroup -> setCaption("");
$barGroup -> setImage("img/filterIcon.png");

$btn_seven_days = new ButtonBar();
$btn_seven_days->setCaption("Last seven days");
$btn_seven_days->setClass("image.actionBarIcon");
$btn_seven_days->onTap(lastSevenDays());

$btn_fifteen_days = new ButtonBar();
$btn_fifteen_days->setCaption("Last fifteen days");
$btn_fifteen_days->setClass("image.actionBarIcon");
$btn_fifteen_days->onTap(lastFifteenDays());

$btn_thirty_days = new ButtonBar();
$btn_thirty_days->setCaption("Last thirty days");
$btn_thirty_days->setClass("image.actionBarIcon");
$btn_thirty_days->onTap(lastThirtyDays());

$barGroup->addControl($btn_seven_days);
$barGroup->addControl($btn_fifteen_days);
$barGroup->addControl($btn_thirty_days);
$bar->addControl($barGroup);
$win->addControl($bar);

//Items List definition
$table = new Table();
$table->setClass("table.Menu");


$list = new Grid();
$list->addData(load_grid());
$list->setEmptyGridText("Your list is empty");
$list->addSearch($name);

$table_grid = new Table();
$table_grid->setClass("table.DetailMain");

//Items definition
$id = new InputNumeric();
$name = new InputText();
$name->setClass("input.Title");
$deleteIcon = new Image();
$deleteIcon->setImage("img/deleteTask.png");
$deleteIcon->setClass("image.ListIcon");
$deleteIcon->onTap(remove());

$table_list = new Table();
$table_list->addControl($name,1,1,1,6,"Left","Middle");
$table_list->addControl($deleteIcon,1,2,1,1,"Middle","Middle");

$table_grid->addControl($table_list,1,1);
$table_grid->onTap(detail());

//Add controls to main screen
$list->addControl($table_grid);
$table->addControl($list);
$win->addControl($table);
$win->Render();

function load_grid(){
		
	//Make JSON request	
	$url = "http://demo.kikapptools.com/taskManager/crud/getTask.php?status=".$statusList."&period=".$period;
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

function lastSevenDays(){
	$period->setValue("-7");
	$win->Refresh();
}

function lastFifteenDays(){
	$period->setValue("-15");
	$win->Refresh();
}

function lastThirtyDays(){
	$period->setValue("-30");
	$win->Refresh();
}

function detail(){
	$win->Open("TaskDetail",$id);
}

function remove(){
		
	//Ask user confirmation
	$isOk = new InputBoolean();
	$isOk = Interop::Confirm("Do you want to delete this task ?");
	
	if($isOk == true){
		
		//Make JSON request
		$urlDelete = "http://demo.kikapptools.com/taskManager/crud/deleteTask.php?action=delete&taskID=".$id;
		$httpClient = new httpClient();
		$result = $httpClient->Execute('GET' ,$urlDelete);
		
		//Cast response data type
		$sdtError = array("response"=>DataType::Character(100));
		Data::FromJson($sdtError,$result);
		$rsValue = new InputText(100);
		$rsValue = $sdtError['response'];
		
		//Refresh the screen
		$win->Refresh();
		
	}
}


?>