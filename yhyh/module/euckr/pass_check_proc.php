<?php
if($_POST["_checkMode"]) {
	//$_password = $_LhDb->Base64("encode", $_POST["yhb_board_pass"]); // base64 ��ȯ
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
		echo("<p class=\"complete\">��й�ȣ�� �½��ϴ�.</p>");
		$_SESSION["yh_board_pass"] = $_password;
		exit();
	} else {
		echo("<p class=\"error\">��й�ȣ�� �ٸ��ϴ�.</p>");
		exit();
	}
} else {
	echo("<p class=\"error\">�������� ������ �̷������ �ʾҽ��ϴ�.</p>");
}
?>
