<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_REQUEST["_id"]) {
	
	$s_field = "
	yhb_number
	, yhb_number_rows
	, yhb_title
	, yhb_name
	, yhb_category
	, yhb_content
	, yhb_reg_date
	, yhb_text1
	, yhb_text2
	, yhb_hit
	";
	
	$query_set = $_LhDb->Get_List_Query($_REQUEST["_id"], "", $s_field);
	$query_list = $query_set->query;
	
	$start_date = $_REQUEST["_day"] ? $_REQUEST["_day"] : "1";
	$start_time = mktime(0, 0, 0, $_REQUEST["_month"], $start_date, $_REQUEST["_year"]);
	$end_date = $_REQUEST["_day"] ? $_REQUEST["_day"] : date("t", $start_time);
	
	if($_REQUEST["_month_end"] && $_REQUEST["_day_end"] && $_REQUEST["_year_end"]) {
		$end_time = mktime(24, 59, 59, $_REQUEST["_month_end"], $_REQUEST["_day_end"], $_REQUEST["_year_end"]);
	} else {
		$end_time = mktime(24, 59, 59, $_REQUEST["_month"], $end_date, $_REQUEST["_year"]);
	}
	
	//$where .= " AND ((yhb_text1 >= '".$start_time."' AND yhb_text1 <= '".$end_time."') OR (yhb_text2 >= '".$start_time."' AND yhb_text2 <= '".$end_time."'))";
	$query_list .= " AND ((yhb_text1 >= '".$start_time."' AND yhb_text1 <= '".$end_time."') OR (yhb_text2 >= '".$start_time."' AND yhb_text2 <= '".$end_time."') OR (yhb_text1 <= '".$start_time."' AND yhb_text2 >= '".$start_time."') OR (yhb_text1 <= '".$end_time."' AND yhb_text2 >= '".$end_time."'))";
	$query_list .= " ORDER by yhb_reg_date asc"; //$query_set->order;
	
	//echo $query_list;
	$result = $_LhDb->Query($query_list);
	
	$i = 0;
	$_result = array();
	
	echo("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
?>
<list query="<?=htmlspecialchars($query_list)?>">
<?
	while($bb = $_LhDb->Fetch_Object($result)) {
		$c_result = $_LhDb->Fetch_Object_Query("select yhb_name, yhb_color, yhb_no from yh_category where yhb_board_name = '".$_REQUEST["_id"]."' AND yhb_no = '".$bb->yhb_category."'");
		//$reset_start = mktime(0, 0, 0, date("n", $bb->yhb_text1), date("d", $bb->yhb_text1), date("Y", $bb->yhb_text1));
		//$reset_end = mktime(0, 0, 0, date("n", $bb->yhb_text2), date("d", $bb->yhb_text2), date("Y", $bb->yhb_text2));
?>
	<row reset_start="<?=$start_time?>" reset_end="<?=$end_time?>" yhb_number="<?=$bb->yhb_number?>" yhb_category_color="<?=$c_result->yhb_color?>" yhb_category_name="<?=$c_result->yhb_name?>" yhb_category_no="<?=$c_result->yhb_no?>" yhb_title="<?=$bb->yhb_title?>" yhb_title_link="" yhb_name="<?=$bb->yhb_name?>" yhb_date="<?=$bb->yhb_reg_date?>" yhb_start_time="<?=$bb->yhb_text1?>" yhb_end_time="<?=$bb->yhb_text2?>" yhb_hit="<?=$bb->yhb_hit?>">
		<![CDATA[<?=nl2br($bb->yhb_content)?>]]>
	</row>
<?
	}
?>
</list>
<?
	exit();
}
echo("");
?>