<?php 
/***
 * Task detail screen
 */

$win = new SDPanel();
$win->setCaption("Task Detail");

//Get var sent from main screen
$id = new InputNumeric();
$id->setClass("input.Common");
Data::getParm($id);

//Define the action bar
$bar = new ActionBar();
$btn_delete = new ButtonBar();
$btn_delete->setCaption("Delete");
$btn_delete->onTap(removeTask());
$btn_delete->setImage("img/deleteBar.png");
$bar->addControl($btn_delete);
$win->addControl($bar);

//Items definition
$name = new InputText();
$name->setClass("input.Common");
$description = new InputText();
$description->setClass("input.Common");
$date = new InputDate();
$date->setLabelCaption("Task date");
$date->setClass("input.Common");

$addToCalendar = new InputBoolean();
$addToCalendar->setLabelCaption("Add to my calendar");
$addToCalendar->setLabelPosition("Right");

//Main table definition
$table = new Table();
$table->setClass("table.Background");

$table_form = new Table();
$table_form->setClass("table.Form");
$table_form->setRowsStyle("65dip;65dip;120dip;55dip");
$table_form->setHeight("90%");

$table_form->addControl($name,1,1,1,2);
$table_form->addControl($date,2,1,1,2);
$table_form->addControl($description,3,1,1,2,"Left","Top");
$table_form->addControl($addToCalendar,4,1,1,1,"Left","Top");

$btn_save = new Button();
$btn_save->setCaption("Save");
$btn_save->setClass("button.Form");
$btn_save->onTap(save());

$table_button = new Table();
$table_button->addControl($btn_save,1,1);
$table_button->setHeight("10%");

$table->addControl($table_form,1,1);
$table->addControl($table_button,2,1,1,1,"Center","Bottom");

//Add controls to main screen
$win->addControl($table);
$win->Render();

function save(){
	
	ProgressIndicator::Show();
	
	//Make JSON request with variables
	$request = new httpClient();	
	$request->addVariable('task',$name);
	$request->addVariable('taskID',$id);
	$request->addVariable('created_at',$date);
	$request->addVariable('description',$description);
	
	$url = "http://demo.kikapptools.com/taskManager/crud/updateTask.php";
	$result = $request->Execute("POST",$url);	
	
	//Cast response data type
	$sdtError = array("response"=>DataType::Character(100));
	Data::FromJson($sdtError,$result);
	
	$rsValue = new InputText(100);
	$rsValue = $sdtError['response'];
	
	if($addToCalendar){
		Calendar::Schedule($name,$date,$date,"10:00:00","11:00:00",$name);
	}
	
	ProgressIndicator::Hide();
	$win->Open("main");
		 
}

function Start(){
	
	//Make JSON request	
	$url = "http://demo.kikapptools.com/taskManager/crud/getTask.php?id=".$id;
	$httpClient = new httpClient();
	$result = $httpClient->Execute('GET' ,$url);
	
	//Cast response data type
	$struct = array(
			"id" 	 => DataType::Numeric(6),
			"task" 	 => DataType::Character(150),
			"status" => DataType::Numeric(1),
			"image"	 => DataType::Character(200),
			"created_at" => DataType::Character(20),
			"description" => DataType::Character(254)
	);

	//Retrieve data to elements on screen
	Data::FromJson($struct,$result);
	
	$name   = $struct['task'];	
	$status = $struct['status'];
	$date   = $struct['created_at'];
	$description   = $struct['description'];

}

function removeTask(){
	
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
		
		//returns to the app home page.
		AndroidAction::GoHome();
		
	}
}

?>
