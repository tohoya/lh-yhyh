<?php
//echo($_SESSION["yh_board_pass"] + "/" + $_POST["_no"]);
if($_POST["_m_no"]) {
	
	
	$query = "delete from yh_member where yhb_number = '".$_POST["_m_no"]."'";
	if($_LhDb->Query($query)) {
		echo("<p class=\"complete\">회원 삭제가 완료되었습니다.</p>");
	} else {
		echo("<p class=\"error\">회원을 삭제하는 동안 문제가 발생하였습니다.</p>");
	}
	exit();
}
echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
?>