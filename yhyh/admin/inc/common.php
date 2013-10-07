<?php
$_start_time = time();
$jquery = true;
include_once(_lh_document_root._lh_yhyh_web."/module/inc/info.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/function.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/dbInfo.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/class.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/dbCon.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/session.php");

// 관리자 정보
$_member = $_LhDb->Get_Member("");

if($_member->yhb_admin != 2) {
	header("Content-Type: text/html; charset=UTF-8");
	if($_member->yhb_number) {
	?>
	<script>
		alert("접속권한이 업습니다.");
	</script>
	<?
	}
	include_once(_lh_document_root._lh_yhyh_web."/admin/inc/admin.php");
	exit();
}

switch(strtolower($_REQUEST["_admin"])) {
	case "category_proc":
	case "board_id_check_proc":
	case "board_register_proc":
	case "board_delete_proc":
	case "group_id_check_proc":
	case "group_register_proc":
	case "group_delete_proc":
	case "member_delete_proc":
		header("Content-Type: text/html; charset=UTF-8");
		include_once(_lh_document_root._lh_yhyh_web."/admin/inc/".$_REQUEST["_admin"].".php");
		exit();
	break;
}
?>
