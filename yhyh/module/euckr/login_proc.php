<?php
if(!$_POST[yhb_id]) {
	echo("<p class=\"error\" title=\"empty_id\">���̵� �Է����ּ���.</p>");
	exit();
}
if(!$_POST[yhb_pass]) {
	echo("<p class=\"error\" title=\"empty_pass\">��й�ȣ�� �Է����ּ���.</p>");
	exit();
}

$_member = $_LhDb->Login_Check($_POST[yhb_id], $_POST[yhb_pass]);
if($_member->yhb_number) {
	echo("<p class=\"complete\" title=\"\">".$_member->yhb_name."�� �α��� �Ǿ����ϴ�.</p>");
	exit();
} else {
	echo("<p class=\"error\" title=\"no_match\">���̵�� ��й�ȣ�� ���� �ʽ��ϴ�.</p>");
	exit();
}
?>