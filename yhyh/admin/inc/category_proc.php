<?php

if($_POST["_category_save_type"] == "delete") {
	$query = "delete from yh_category where yhb_number = '".$_POST["yhb_number"]."'";
	if($_LhDb->Query($query)) {
		echo("<p class=\"complete\" title=\"".$_POST[yhb_number]."\">카테고리 삭제가 완료 되었습니다.</p>");
	} else {
		echo("<p class=\"error\">삭제하는 도중 실패하였습니다.(".$query.")</p>");
	}
}

if(!$_POST[yhb_group_no]) $_POST[yhb_group_no] = 0;

if($_POST["yhb_number"]) {
	$query = "select yhb_no from yh_category where yhb_number = '".$_POST["yhb_number"]."'";
	$_c_result = $_LhDb->Fetch_Object_Query($query);
	
	$modify =  eregi_replace("\r|\n|\t", "", "yhb_board_name = '".$_POST[yhb_board_name]."'
		, yhb_group_no = '".$_POST[yhb_group_no]."'
		, yhb_name = '".$_POST[yhb_name]."'
		, yhb_color = '".$_POST[yhb_color]."'
		, yhb_url = '".$_POST[yhb_url]."'
	");
	
	$query = "update yh_category set ".$modify." where yhb_number = '".$_POST["yhb_number"]."'";
	if($_LhDb->Query($query)) {
		echo("<p class=\"complete\" id=\"".$_c_result->yhb_no."\" title=\"".$_POST[yhb_number]."\">카테고리 수정내용이 저장 되었습니다.</p>");
	} else {
		echo("<p class=\"error\">수정하는 도중 실패하였습니다.(".$query.")</p>");
	}
	exit();
}


$fields = eregi_replace("\r|\n|\t", "", "yhb_number
, yhb_no
, yhb_group_no
, yhb_board_no
, yhb_board_name
, yhb_name
, yhb_color
, yhb_url
");

$_POST[yhb_no] = TableMax("yh_category", "yhb_no", "10000001", "yhb_board_name = '".$_POST[yhb_board_name]."'"); // 카테고리 번호
$_POST[yhb_number] = TableMax("yh_category", "yhb_number", "1", ""); // 등록 번호

$values = eregi_replace("\r|\n|\t", "", "'".$_POST[yhb_number]."'
, '".$_POST[yhb_no]."'
, '".$_POST[yhb_group_no]."'
, '".$_POST[yhb_board_no]."'
, '".$_POST[yhb_board_name]."'
, '".$_POST[yhb_name]."'
, '".$_POST[yhb_color]."'
, '".$_POST[yhb_url]."'
");

$query = "insert into yh_category(".$fields.") values(".$values.")";

//echo $query;
if($_LhDb->Query($query)) {
	echo("<p class=\"complete\" id=\"".$_POST[yhb_no]."\" title=\"".$_POST[yhb_number]."\">카테고리 등록이 완료 되었습니다.</p>");
} else {
	echo("<p class=\"error\">카테고리 등록 도중 실패하였습니다.(".$query.")</p>");
}

?>