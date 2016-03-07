<?php 
/**
 * Main object.
 * @author KikApp
 * @version 1.0
 */

$win = new SDPanel();
$win -> setCaption("Gecko CRM");

$mainTable = new Table();

$button = new Button();
$button -> setCaption("Companies");
$button -> onTap(list_companies());

$button_2 = new Button();
$button_2 -> setCaption("Contacts");
$button_2 -> onTap(list_contacts());

$button_3 = new Button();
$button_3 -> setCaption("Meetings");
$button_3 -> onTap(list_meetings());


$mainTable -> addControl($button,1,1);
$mainTable -> addControl($button_2,2,1);
$mainTable -> addControl($button_3,3,1);

$win -> addControl($mainTable);

function list_companies(){
	$win -> Open("list_companies");
}

function list_contacts(){
	$win -> Open("list_contact");
}

function list_meetings(){
	$win -> Open("list_meeting");
}

function back(){
	$ok = new InputBoolean();
	$ok = Interop::Confirm('Do you want to close Gecko CRM app ?');
	
	if($ok == true){
		return;
	}
}

?>