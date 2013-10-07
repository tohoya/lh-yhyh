<?php
if($_POST["_checkMode"]) {
	//$_password = $_LhDb->Base64("encode", $_POST["yhb_board_pass"]); // base64 변환
	$_password = $_POST["yhb_board_pass"];
	switch(strtolower($_POST["_checkMode"])) {
		case "secret":
			$query = "select yhb_number_up from yh_board where yhb_number = '".$_POST["_no"]."'";
			$bb = $_LhDb->Fetch_Object_Query($query);
			$query = "select yhb_number from yh_board where yhb_number_up = '".$bb->yhb_number_up."' AND yhb_board_pass = '".$_password."'";
		break;
		case "modify":
		case "delete":
			$query = "select yhb_number from yh_board where yhb_number = '".$_POST["_no"]."' AND yhb_board_pass = '".$_password."'";
		break;
	}
	if($_LhDb->Query_Row_Num($query) > 0) {
		echo("<p class=\"complete\">비밀번호가 맞습니다.</p>");
		$_SESSION["yh_board_pass"] = $_password;
		exit();
	} else {
		echo("<p class=\"error\">비밀번호가 다릅니다.</p>");
		exit();
	}
} else {
	echo("<p class=\"error\">정상적인 동작이 이루어지지 않았습니다.</p>");
}
?>
