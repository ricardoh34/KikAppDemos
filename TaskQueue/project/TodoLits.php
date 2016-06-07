<?php 

/***
 * Task List (TO DO)
 */

$win = new SDPanel();
$win->setCaption("Tasks To Do");

$statusList = new InputNumeric();
$period = new InputNumeric();
Data::getParm($period, $statusList);

//Add button to action bar
$bar = new ActionBar();
$barGroup = new ActionGroup();
$barGroup -> setCaption("");
$barGroup -> setImage("img/filterIcon.png");

$btn_today = new ButtonBar();
$btn_today->setCaption("Today");
$btn_today->setClass("image.actionBarIcon");
$btn_today->onTap(Today());

$btn_seven_days = new ButtonBar();
$btn_seven_days->setCaption("Next seven days");
$btn_seven_days->setClass("image.actionBarIcon");
$btn_seven_days->onTap(nextSevenDays());

$btn_fifteen_days = new ButtonBar();
$btn_fifteen_days->setCaption("Next fifteen days");
$btn_fifteen_days->setClass("image.actionBarIcon");
$btn_fifteen_days->onTap(nextFifteenDays());

$btn_thirty_days = new ButtonBar();
$btn_thirty_days->setCaption("Next thirty days");
$btn_thirty_days->setClass("image.actionBarIcon");
$btn_thirty_days->onTap(nextThirtyDays());

$barGroup->addControl($btn_today);
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
$date = new InputDate();
$date->setClass("input.Common");

$statusIcon = new Image();
$statusIcon->setImage("img/tick.png");
$statusIcon->setClass("image.ListIcon");
$statusIcon->onTap(changeToDone());

$table_list = new Table();
$table_list->addControl($name,1,1,1,6,"Left","Middle");
$table_list->addControl($date,1,2,1,2,"Left","Middle");
$table_list->addControl($statusIcon,1,3,1,1,"Middle","Middle");

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
			"created_at" => DataType::Character(20)
		)
	);

	Data::FromJson($struct,$result);
	
	//Add result to screen vars 
	foreach ($struct as $items){
		$id 	= $items['id'];
		$name 	= $items['task'];
		$status = $items['status'];
		$date   = $items['created_at'];
	}	
}

function nextSevenDays(){
	$period->setValue("7");
	$win->Refresh();
}

function Today(){
	$period->setValue("1");
	$win->Refresh();
}

function nextFifteenDays(){
	$period->setValue("15");
	$win->Refresh();
}

function nextThirtyDays(){
	$period->setValue("30");
	$win->Refresh();
}

function detail(){
	$win->Open("TaskDetail",$id);
}

function changeToDone(){
	
	//Ask user confirmation
	$isOk = new InputBoolean();
	$isOk = Interop::Confirm("Do you want to mark this task as done ?");
	
	if($isOk == true){
		
		//Make JSON request
		$urlDelete = "http://demo.kikapptools.com/taskManager/crud/deleteTask.php?action=update&taskID=".$id;
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