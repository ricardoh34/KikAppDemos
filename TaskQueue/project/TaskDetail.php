<?php 
/***
 * Detail of product
 */

$win = new SDPanel();
//$win->setCaption("Task Detail");

$id = new InputNumeric();
$id->setClass("input.Common");

Data::getParm($id);

$name = new InputText();
$name->setClass("input.Common");

$date = new InputDate();
$date->setLabelCaption("Task date");
$date->setClass("input.Common");

$status = new InputBoolean();
$status->setLabelCaption("Status");
$status->setLabelPosition("Right");
$status->setClass("input.Common");

$image = new InputImage();

$table = new Table();
$table->setClass("table.Background");

$table_form = new Table();
$table_form->setClass("table.Form");
$table_form->setRowsStyle("76dip;76dip;76dip");
$table_form->setHeight("90%");

$btn_save = new Button();
$btn_save->setCaption("Save");
$btn_save->setClass("button.Form");
$btn_save->onTap(save());

$table_form->addControl($id,1,1);
$table_form->addControl($name,2,1);
$table_form->addControl($date,3,1);
$table_form->addControl($image,4,1);
$table_form->addControl($status,5,1);

$table_button = new Table();
$table_button->addControl($btn_save,1,1);
$table_button->setHeight("10%");

$table->addControl($table_form,1,1);
$table->addControl($table_button,2,1,1,1,"Center","Bottom");

$win->addControl($table);
$win->Render();

function save(){
	
	ProgressIndicator::Show();
	$request = new httpClient();	
	
	$request->addVariable('status',$status);
	$request->addVariable('task',$name);
	$request->addVariable('image',$image);
	$request->addVariable('taskID',$id);
	
	$url = "http://demo.kikapptools.com/taskManager/crud/addTask.php";
	$result = $request->Execute("POST",$url);	
	$sdtError = array("response"=>DataType::Character(100));
	Data::FromJson($sdtError,$result);
	
	$rsValue = new InputText(100);
	$rsValue = $sdtError['response'];
	ProgressIndicator::Hide();
	$win->Open("main");
		 
}

function Start(){
	//Make JSON request	
	$url = "http://demo.kikapptools.com/taskManager/crud/getTask.php?id=".$id;
	$httpClient = new httpClient();
	//$httpClient->addVariable('id', $id);
	$result = $httpClient->Execute('GET' ,$url);
	
	//Cast response data type
	$struct = array(
			"id" 	 => DataType::Numeric(6),
			"task" 	 => DataType::Character(150),
			"status" => DataType::Numeric(1),
			"image"	 => DataType::Character(200)
	);

	//Retrieve data to elements on screen
	Data::FromJson($struct,$result);
	
	$name  = $struct['task'];	
	$status = $struct['status'];
	$image  = $struct['image'];
	
	//set caption 
	$win -> setCaption($name);
}

?>