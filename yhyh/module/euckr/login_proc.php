<?php
if(!$_POST[yhb_id]) {
	echo("<p class=\"error\" title=\"empty_id\">아이디를 입력해주세요.</p>");
	exit();
}
if(!$_POST[yhb_pass]) {
	echo("<p class=\"error\" title=\"empty_pass\">비밀번호를 입력해주세요.</p>");
	exit();
}

$_member = $_LhDb->Login_Check($_POST[yhb_id], $_POST[yhb_pass]);
if($_member->yhb_number) {
	echo("<p class=\"complete\" title=\"\">".$_member->yhb_name."님 로그인 되었습니다.</p>");
	exit();
} else {
	echo("<p class=\"error\" title=\"no_match\">아이디와 비밀번호가 맞지 않습니다.</p>");
	exit();
}
?>