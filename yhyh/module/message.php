<?php

$_m_result = $_LhDb->Get_Member($_REQUEST["_m_no"], ($_REQUEST["_auto"] != "false"));
if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_m_result->yhb_group_no;

// 그룹 정보
$_g_result = $_LhDb->Get_Group($_REQUEST["_group"]);

@include_once($_skin_group_root."message.php");
?>