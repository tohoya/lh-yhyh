<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_POST["_group"]) {
	
	$rows_select = "select yhb_number from yh_board where yhb_group_no = '".$_POST["_group"]."'";
	$rows_delete = "delete from yh_board where yhb_group_no = '".$_POST["_group"]."'";
	
	if($_LhDb->Query_Row_Num($rows_select) > 0) {
		$query = "select s_name from yh_file where group_no = '".$_POST["_group"]."'";
		$result = $_LhDb->Query($query);
		
		if($_LhDb->Query($rows_delete)) {
			while($f = $_LhDb->Fetch_Object($result)) {
				$file_url = _lh_document_root._lh_yhyh_web."/upload/".$f->s_name;
				if(file_exists($file_url)) {
					@unlink($file_url);
				}
			}
			$query = "delete from yh_file where group_no = '".$_POST["_group"]."'";
			$_LhDb->Query($query);
		}
	}
	
	$query = "delete from yh_group where yhb_number = '".$_POST["_group"]."'";
	if($_LhDb->Query($query)) {
		$query = "delete from yh_config_board where yhb_group_no = '".$_POST["_group"]."'";
		$_LhDb->Query($query);
		$query = "delete from yh_member where yhb_group_no = '".$_POST["_group"]."' AND yhb_admin != '2'";
		$_LhDb->Query($query);
		$query = "delete from yh_category where yhb_group_no = '".$_POST["_group"]."'";
		$_LhDb->Query($query);
		echo("<p class=\"complete\">그룹 삭제가 완료되었습니다.</p>");
	} else {
		echo("<p class=\"error\">그룹 삭제하는 동안 문제가 발생하였습니다.</p>");
	}
}
echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
?>