<?php 

/*
 * Main menu
 */

$win = new SDPanel();
$win->setCaption("Tasks list");

$table = new Table();
$table->setClass("table.Menu");
$table-> setRowsStyle("150dip;56dip;56dip;56dip");

//Menu Header
$table_header = new Table();
$table_header -> setClass("table.Header");

$lbl_header = new Image();
$lbl_header->setClass("image.MenuHeader");
$lbl_header->setImage("img/headerTitle.png");
$table_header->addControl($lbl_header,2,1,1,1,"Left","Bottom");

//Home button
$table_home = new Table();
$table_home->setClass("table.MenuItem");
$table_home->setColumnsStyle("20%;80%");

$img_home = new Image();
$img_home->setImage("img/ic_action.png");
$img_home->setClass("image.MenuItem");

$lbl_home = new Label();
$lbl_home->setCaption("To Do");
$lbl_home->setClass("label.MenuItem");

$table_home->onTap(openTodoLits());
$table_home->addControl($img_home,1,1,1,1,"Center","Middle");
$table_home->addControl($lbl_home,1,2,1,1,"Left","Middle");

//Add button
$table_add = new Table();
$table_add->setClass("table.MenuItem");
$table_add->setColumnsStyle("20%;80%");

$img_add = new Image();
$img_add->setImage("img/ic_add.png");
$img_add->setClass("image.MenuItem");

$lbl_add = new Label();
$lbl_add->setCaption("Add task");
$lbl_add->setClass("label.MenuItem");

$table_add->onTap(add());
$table_add->addControl($img_add,1,1,1,1,"Center","Middle");
$table_add->addControl($lbl_add,1,2,1,1,"Left","Middle");

//Deleted button
$table_deleted = new Table();
$table_deleted->setClass("table.MenuItem");
$table_deleted->setColumnsStyle("20%;80%");

$img_deleted = new Image();
$img_deleted->setImage("img/ic_deleted.png");
$img_deleted->setClass("image.MenuItem");

$lbl_deleted = new Label();
$lbl_deleted->setCaption("Done");
$lbl_deleted->setClass("label.MenuItem");

$table_deleted->onTap(openDoneList());
$table_deleted->addControl($img_deleted,1,1,1,1,"Center","Middle");
$table_deleted->addControl($lbl_deleted,1,2,1,1,"Left","Middle");

//Add controls to menu
$table->addControl($table_header,1,1);
$table->addControl($table_home,2,1);
$table->addControl($table_deleted,3,1);
$table->addControl($table_add,4,1);

//Add controls to main screen
$win->addControl($table);
$win->Render();


function Slide(){
	
	$period = new InputNumeric();
	$period->setValue(0);
	
	$statusList = new InputNumeric();
	$statusList->setValue(0);
	
	$win->Open("TodoLits",$period, $statusList);		
}

function openTodoLits(){
	
	$period = new InputNumeric();
	$period->setValue(0);
	
	$statusList = new InputNumeric();
	$statusList->setValue(0);
	
	$win->Open("TodoLits",$period, $statusList);	
}

function add(){
	$win->Open("AddTask");
}

function openDoneList()
{
	$period = new InputNumeric();
	$period->setValue(0);
	
	$statusList = new InputNumeric();
	$statusList->setValue(3);
	
	$win->Open("DoneList", $period, $statusList);	
}



?>