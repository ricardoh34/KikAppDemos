<?php 


$win = new SDPanel();
$win -> setCaption("Movements");


$maintable = new Table();
$maintable -> setRowsStyle("100%");
$maintable -> setWidth("100%");
$maintable -> setHeight("100%");
$maintable-> setClass("table.white");

$label_budget= new Label();
$label_budget-> setCaption("Add Budget");
$label_budget-> setClass("label.blue");
$label_budget-> onTap(budget());

$label_income= new Label();
$label_income-> setCaption("Income");
$label_income-> setClass("label.green");
$label_income-> onTap(income());

$label_outcome= new Label();
$label_outcome-> setCaption("Outcome");
$label_outcome-> setClass("label.red");
$label_outcome-> onTap(outcome());


$maintable-> addControl($label_budget,1,1,1,1,"Center","Middle");
$maintable-> addControl($label_income,2,1,1,1,"Center","Middle");
$maintable-> addControl($label_outcome,3,1,1,1,"Center","Middle");

$win -> addControl($maintable);


function budget() {
   $win-> Open("Budget");
}

function income() {
	$moveType= new InputText();
	$moveType->setValue("0");
	$win -> Open("IncomeOutcome",$moveType);
}

function outcome() {
	$moveType= new InputText();
	$moveType->setValue("1");
	$win -> Open("IncomeOutcome",$moveType);
}
?>