<?php 
/***
 * Home.
 */

$win = new SDPanel();
$win -> setCaption("Home");

$token = new InputText(80);
Data::getParm($token);

$maintable = new Table();
$maintable -> setWidth("100%");
$maintable -> setHeight("100%");
$maintable-> setClass("table.global");
$maintable -> setRowsStyle("100%");

$table_first= new Table();
$table_first-> setWidth("100%");
$table_first-> setHeight("40%");
$table_first-> setClass("table.first");

$table_canvas = new Table();
$table_canvas -> setRowsStyle("100%");
$table_canvas -> setWidth("100%");
$table_canvas -> setHeight("100%");

$canvas = new Canvas();
$canvas -> setWidth("100%");
$canvas -> setHeight("100%");

$label_account= new Label();
$label_account->setCaption("Account");
$label_account->setClass("label.black");

$input_account= new InputNumeric(11);
$input_account-> setReadOnly(true);
$input_account-> setClass("label.black");

$label_blue= new Label();
$label_blue-> setClass("label.account");

$table_account= new Table();
$table_account-> setColumnsStyle("5dip;50%;50%");
$table_account -> addControl($label_blue,1,1,1,1,"","Middle");
$table_account -> addControl($label_account,1,2,1,1,"Center","Middle");
$table_account -> addControl($input_account,1,3,1,1,"Right","Middle");

$label_income= new Label();
$label_income->setCaption("Income");
$label_income->setClass("label.black");

$input_income= new InputNumeric(11);
$input_income-> setReadOnly(true);
$input_income-> setClass("label.black");

$label_green= new Label();
$label_green-> setClass("label.income");

$table_income= new Table();
$table_income-> setColumnsStyle("5dip;50%;50%");
$table_income -> addControl($label_green,1,1,1,1,"","Middle");
$table_income -> addControl($label_income,1,2,1,1,"Center","Middle");
$table_income -> addControl($input_income,1,3,1,1,"Right","Middle");

$label_outcome= new Label();
$label_outcome->setCaption("Outcome");
$label_outcome->setClass("label.black");

$input_outcome= new InputNumeric(11);
$input_outcome-> setReadOnly(true);
$input_outcome-> setClass("label.black");

$label_red= new Label();
$label_red-> setClass("label.outcome");

$table_outcome= new Table();
$table_outcome-> setColumnsStyle("5dip;50%;50%");
$table_outcome -> addControl($label_red,1,1,1,1,"","Middle");
$table_outcome -> addControl($label_outcome,1,2,1,1,"Center","Middle");
$table_outcome -> addControl($input_outcome,1,3,1,1,"Right","Middle");

$table_second= new Table();
$table_second-> setWidth("100%");
$table_second-> setHeight("40%");
$table_second-> setClass("table.first");

$img_add = new Image();
$img_add -> setImage("img/Android/hdpi/plus_circle.png");
$img_add -> onTap(movements());

$label_title= new InputText();
$label_title-> setReadOnly(true);
$label_title->setClass("label.black");

$table_title_month= new Table();
$table_title_month -> addControl($label_title,1,1,1,1,"Center","Middle");

$label_income_month= new Label();
$label_income_month-> setCaption("Income: ");
$label_income_month->setClass("label.black");

$input1= new InputText();
$input1-> setReadOnly(true);
$input1-> setClass("label.black");

$label_green_bis= new Label();
$label_green_bis-> setClass("label.income");

$label_red_bis= new Label();
$label_red_bis-> setClass("label.outcome");

$table_income_month= new Table();
$table_income_month-> setColumnsStyle("5dip;50%;50%");
$table_income_month -> addControl($label_green_bis,1,1,1,1,"","Middle");
$table_income_month -> addControl($label_income_month,1,2,1,1,"Center","Middle");
$table_income_month -> addControl($input1,1,3,1,1,"Right","Middle");

$label_outcome_month= new Label();
$label_outcome_month-> setCaption("Outcome: ");
$label_outcome_month->setClass("label.black");

$input2= new InputText();
$input2-> setReadOnly(true);
$input2-> setClass("label.black");

$table_outcome_month= new Table();
$table_outcome_month-> setColumnsStyle("5dip;50%;50%");
$table_outcome_month -> addControl($label_red_bis,1,1,1,1,"","Middle");
$table_outcome_month -> addControl($label_outcome_month,1,2,1,1,"Center","Middle");
$table_outcome_month -> addControl($input2,1,3,1,1,"Right","Middle");

$table_first-> addControl($table_account,1,1);
$table_first-> addControl($table_income,2,1);
$table_first-> addControl($table_outcome,3,1);

$table_second-> addControl($table_title_month,1,1);
$table_second-> addControl($table_income_month,2,1);
$table_second-> addControl($table_outcome_month,3,1);
//addPosition(control, left, width, top, height, right, bottom, zorder)
$canvas -> addPosition($table_first,"10dip","100%","15dip","60dip","10dip","0%",1);
$canvas -> addPosition($table_second,"10dip","100%","225dip","60dip","10dip","0%",1);
$canvas -> addPosition($img_add,"100%","60dip","97%","60dip","15dip","3%",5);

$table_canvas -> addControl($canvas,1,1);

$maintable-> addControl($table_canvas,1,1);

$win -> addControl($maintable);

function refresh() {
   $url = "http://demo.kikapptools.com/Gastos/crud/getIncome.php?id=".$token;
   $httpClient = new httpClient();
   $result= $httpClient -> Execute('GET',$url);
   
   $struct= array(
            "account" => DataType::Numeric(11),
            "value_income" => DataType::Numeric(11),
            "value_outcome" => DataType::Numeric(11),
            "value_income_month" => DataType::Numeric(11),
            "value_outcome_month" => DataType::Numeric(11),
            "label_actual_month" => DataType::Character(20)
   );
   
   Data::FromJson($struct,$result);

   $input_account = $struct['account'];
   $input_income = $struct['value_income'];
   $input_outcome = $struct['value_outcome'];
   $input1 = $struct['value_income_month'];
   $input2 = $struct['value_outcome_month'];
   $label_title = $struct['label_actual_month'];
}

function movements() {
   if($token=="") {
      $win -> Open("Login");
   }
   else {
      $win -> Open("Movements");
   }
}

function back(){
   $ok = new InputBoolean();
   $ok = Interop::Confirm('Are you sure you want to close Expenses app?');
   if($ok == true){
      return;
   }
}
?>