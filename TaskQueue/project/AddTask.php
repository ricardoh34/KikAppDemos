<?php 
/***
 * Task add screen
 */

$win = new SDPanel();
$win->setCaption("Add Task");

//Items definition
$id = new InputNumeric();
$id->setClass("input.Common");

$name = new InputText();
$name-> setInviteMessage("Task name");
$name->setClass("input.Common");

$description = new InputText();
$description-> setInviteMessage("Description");
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
	
	//Check form values
	if($name == null){
		echo "Name is a required field.";
	}else{
		
		$compareDefaultDate = new InputDate();
		if($date==$compareDefaultDate){
			echo "Date is a required field.";
		}else{
			if($description == null){
				echo "Please provide a description";
			}else{
	
				ProgressIndicator::Show();
				
				//Make JSON request with variables
				$request = new httpClient();	
				$request->addVariable('task',$name);
				$request->addVariable('taskID',$id);
				$request->addVariable('created_at',$date);
				$request->addVariable('description',$description);
				
				$url = "http://demo.kikapptools.com/taskManager/crud/addTask.php";
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
				//returns to the app home page.
				AndroidAction::GoHome();
			}
		}
	}
}

?>