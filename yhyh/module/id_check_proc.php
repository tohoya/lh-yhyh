<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_POST["user_id"]) {
	$query = "select yhb_number from yh_member where yhb_id = '".$_POST["user_id"]."'";
	
	if($_LhDb->Query_Row_Num($query) > 0) {
		echo("<p class=\"complete\" title=\"no_use_id\">입력하신 아이디는 현재 사용중인 아이디입니다.</p>");
		exit();
	} else {
		echo("<p class=\"complete\" title=\"use_id\">사용가능한 아이디입니다.</p>");
		exit();
	}
}
echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
?>