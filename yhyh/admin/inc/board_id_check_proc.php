<?php
if($_POST["yhb_name"]) {
	$query = "select yhb_number from yh_config_board where yhb_name = '".$_POST["yhb_name"]."'";
	
	if($_LhDb->Query_Row_Num($query) > 0) {
		echo("<p class=\"complete\" title=\"no_use_name\">입력하신 게시판 코드는 현재 사용중인 코드입니다.</p>");
		exit();
	} else {
		echo("<p class=\"complete\" title=\"use_name\">사용가능한 게시판 코드입니다.</p>");
		exit();
	}
}
echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
?>