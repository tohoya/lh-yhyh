<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_POST["_idxs"]) {
	$_delete_idxs = split(",", $_POST["_idxs"]);
	$_delete_count = count($_delete_idxs);
	if($_delete_count > 0 && $_delete_idxs) {
		$_where = "";
		$_f_where = "";
		for($i = 0; $i < $_delete_count;$i++) {
			$_POST[_no] = trim($_delete_idxs[$i]);
			
			if($_POST[_no]) {
				$_where .= $_where ? " OR " : "";
				$_f_where .= $_f_where ? " OR " : "";
				
				$_where .= "yhb_number = '".$_POST[_no]."'";
				$_f_where .= "board_no = '".$_POST[_no]."'";
			}
		}
		
		if($_where) {
			$_where = " AND (".$_where.")";
		}
		
		if($_f_where) {
			$_f_where = " AND (".$_f_where.")";
		}
		
		if($_where) {
			$query = "select yhb_id, yhb_pass from yh_board where yhb_number != ''".$_where;
			$bb = $_LhDb->Fetch_Object_Query($query);
	
			$_member = $_LhDb->Get_Member();
			echo $_member->yhb_admin."/".$_REQUEST["_id"];
			if($_LhDb->Get_Admin()) {
				$rows_select = "select yhb_number from yh_board where yhb_number != ''".$_where;
				$rows_delete = "delete from yh_board where yhb_number != ''".$_where;
			} else {
				if($bb->yhb_id && $bb->yhb_pass) {
					$rows_select = "select yhb_number from yh_board where yhb_number != ''".$_where."' AND yhb_id = '".$_member->yhb_id."' AND yhb_pass = '".$_member->yhb_pass."'";
					$rows_delete = "delete from yh_board where yhb_number != ''".$_where."' AND yhb_id = '".$_member->yhb_id."' AND yhb_pass = '".$_member->yhb_pass."'";
				} else {
					if($_SESSION["yh_board_pass"] != "") {
						$rows_select = "select yhb_number from yh_board where yhb_number != ''".$_where."' AND yhb_board_pass = '".$_SESSION["yh_board_pass"]."'";
						$rows_delete = "delete from yh_board where yhb_number != ''".$_where."' AND yhb_board_pass = '".$_SESSION["yh_board_pass"]."'";
					} else {
						echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.2</p>");
						exit();
					}
				}
			}
			
			if($_LhDb->Query_Row_Num($rows_select) > 0) {
				$query = "select s_name from yh_file where board_no != ''".$_f_where;
				//echo $query;
				$result = $_LhDb->Query($query);
				
				if($_LhDb->Query($rows_delete)) {
					while($f = $_LhDb->Fetch_Object($result)) {
						$file_url = _lh_document_root._lh_yhyh_web."/upload/".$f->s_name;
						if(file_exists($file_url)) {
							//echo $file_url;
							@unlink($file_url);
						}
					}
					$query = "delete from yh_file where board_no != ''".$_f_where;
					$_LhDb->Query($query);
					
					echo("<p class=\"complete\">삭제가 완료되었습니다.</p>");
					$_SESSION["yh_board_pass"] = "";
				} else {
					echo("<p class=\"error\">삭제 중에 애러가 발생하였습니다. 다시 시도하여 주세요.</p>");
				}
				exit();
			} else {
				echo("<p class=\"error\">삭제할 글이 없습니다.</p>");
				exit();
			}
		}
	}
}
echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
?>