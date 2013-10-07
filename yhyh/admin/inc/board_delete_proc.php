<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_POST["_id"]) {
	
	$rows_select = "select yhb_number from yh_board where yhb_board_name = '".$_POST["_id"]."'";
	$rows_delete = "delete from yh_board where yhb_board_name = '".$_POST["_id"]."'";
	
	if($_LhDb->Query_Row_Num($rows_select) > 0) {
		$query = "select s_name from yh_file where board_name = '".$_POST["_id"]."'";
		$result = $_LhDb->Query($query);
		
		if($_LhDb->Query($rows_delete)) {
			while($f = $_LhDb->Fetch_Object($result)) {
				$file_url = _lh_document_root._lh_yhyh_web."/upload/".$f->s_name;
				if(file_exists($file_url)) {
					@unlink($file_url);
				}
			}
			$query = "delete from yh_file where board_name = '".$_POST["_id"]."'";
			$_LhDb->Query($query);
		}
	}
	
	$query = "delete from yh_config_board where yhb_name = '".$_POST["_id"]."'";
	if($_LhDb->Query($query)) {
		$query = "delete from yh_category where yhb_baord_name = '".$_POST["_id"]."'";
		$_LhDb->Query($query);
		echo("<p class=\"complete\">게시판 삭제가 완료되었습니다.</p>");
	} else {
		echo("<p class=\"error\">게시판을 삭제하는 동안 문제가 발생하였습니다.</p>");
	}
}
echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
?>