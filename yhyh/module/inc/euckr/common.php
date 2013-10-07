<?php
if(!$_incoding_mode) {
	$_incoding_mode = eregi("euckr".$_p_pattern, $_SERVER['PHP_SELF']) ? "euckr/" : "";
}
$_start_time = time();
$_yhyh_common_load = true;

if(!$_yhyh_root) {
	$_arr = split("yhyh", dirname($_SERVER['PATH_TRANSLATED']));
	$_yhyh_root = $_arr[0];
}
if(!$_yhyh_web) $_yhyh_web = "/yhyh";

define(_lh_document_root, $_yhyh_root ? $_yhyh_root : $_SERVER['DOCUMENT_ROOT']);
define(_lh_yhyh_web, $_yhyh_web ? $_yhyh_web : "/yhyh");

$_protocol = $_SERVER['HTTPS'] == "on" ? "https://" : "http://";

/*
echo($_SERVER['SERVER_SOFTWARE']);
echo("<br>DOCUMENT_ROOT : ".$_SERVER['DOCUMENT_ROOT']);
echo("<br>HTTP_HOST : ".$_SERVER['HTTP_HOST']);
echo("<br>SERVER_NAME : ".$_SERVER['SERVER_NAME']);
echo("<br>HTTP_REFERER : ".$_SERVER['HTTP_REFERER']);
echo("<br>PATH_TRANSLATED : ".$_SERVER['PATH_TRANSLATED']);
echo("<br>PHP_SELF : ".$_SERVER['PHP_SELF']);
echo("<br>QUERY_STRING : ".$_SERVER['QUERY_STRING']);
echo("<br>REQUEST_METHOD : ".$_SERVER['REQUEST_METHOD']);
echo("<br>REQUEST_URI : ".$_SERVER['REQUEST_URI']);
echo("<br>SCRIPT_FILENAME : ".$_SERVER['SCRIPT_FILENAME']);
echo("<br>SCRIPT_NAME : ".$_SERVER['SCRIPT_NAME']);
echo("<br>HTTP_USER_AGENT : ".$_SERVER['HTTP_USER_AGENT']);
echo($_SERVER['HTTPS']);
*/

include_once(_lh_document_root._lh_yhyh_web."/module/inc/".$_incoding_mode."info.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/".$_incoding_mode."function.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/".$_incoding_mode."dbInfo.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/".$_incoding_mode."class.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/".$_incoding_mode."dbCon.php");
include_once(_lh_document_root._lh_yhyh_web."/module/inc/".$_incoding_mode."session.php");

if(!$_REQUEST["_id"] && !$_REQUEST["_module"]) {
	if($_REQUEST["_no"]) {
		$_REQUEST["_id"] = Board_Row_Id_Get($_REQUEST["_no"]);
	} else {
		if($_LhDb->Get_Board_Id()) {
			$_REQUEST["_id"] = $_LhDb->Get_Board_Id();
		} else {
			//echo(strtolower($_LhDb->Split_Export(dirname($PHP_SELF), "/")));
			if(strtolower($_LhDb->Split_Export(dirname($PHP_SELF), "/")) == "yhyh") {
				echo(_lh_yhyh_web."/admin/");
				$_LhDb->Refresh(0, _lh_yhyh_web."/admin/".$_incoding_mode);
			}
		}
	}
}

// 게시판 정보
$_config = $_LhDb->Get_Board($_REQUEST["_id"]);
$_skin_web = _lh_yhyh_web."/skin/".$_config->yhb_skin."/";

if(!file_exists(_lh_document_root."/".$_skin_web)) define(_lh_skin_web, _lh_yhyh_web."/skin/defaultBoard/");
else define(_lh_skin_web, $_skin_web);


$_skin_root = _lh_document_root."/"._lh_skin_web;
//echo _lh_skin_web;

if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;

// 회원정보
$_member = $_LhDb->Get_Member(""); //$_REQUEST["_m_no"]);
if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
// 그룹 정보
$_group = $_LhDb->Get_Group($_REQUEST["_group"]);
//$_group->yhb_skin = "default"; // 임시임
$_skin_group_web = _lh_yhyh_web."/group_skin/".$_group->yhb_skin."/";
$_skin_group_root = _lh_document_root."/".$_skin_group_web;
?>
