<?php

$_m_result = $_LhDb->Get_Member($_REQUEST["_m_no"], false);
if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_m_result->yhb_group_no;

// 그룹 정보
$_g_result = $_LhDb->Get_Group($_REQUEST["_group"]);
$_skin_group_web = _lh_yhyh_web."/group_skin/".$_g_result->yhb_skin."/";
$_skin_group_root = _lh_document_root."/".$_skin_group_web;

$referer_url = eregi_replace("^([a-z0-9A-Z])*://", "", $_SERVER['HTTP_REFERER']);
$protocol = str_replace($referer_url, "", $_SERVER['HTTP_REFERER']);
//echo("1:".$_SERVER['HTTP_REFERER']."<br>2:".$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
if($_SERVER['HTTP_REFERER'] != $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) {
	$_LhDb->Return_Url_Set($_SERVER['HTTP_REFERER'], "register_back");
	//echo "<br>save";
}

//echo("<br>3:".$_LhDb->Return_Url_Get("register_back"));

$_back_link = $_LhDb->Return_Url_Get("register_back") ? $_LhDb->Return_Url_Get("register_back") : $_SERVER['HTTP_REFERER'];

?>
<link href="<?=_lh_yhyh_web?>/admin/css/board_register.css" rel="stylesheet" type="text/css">
<?
if($_g_result->yhb_skin) {
	include_once($_skin_group_root."register.php");
} else {
?>
<div class="yhyh_board_register">
	<form class="FormDesignNormal">
		<fieldset>
			<legend>그룹 선택</legend>
			<h3>그룹 선택</h3><br>
			<?
			$_query = "select yhb_number, yhb_name from yh_group order by yhb_name asc";
			$_g_result = $_LhDb->Query($_query);
			while($_g = $_LhDb->Fetch_Object($_g_result)) {
			?>
			<a href="<?=_lh_yhyh_web?>/admin/?_admin=member_register&_order=<?=$_REQUEST["_order"]?>&_group=<?=$_g->yhb_number?>&_grant=<?=$_REQUEST["_grant"]?>&_level=<?=$_REQUEST["_level"]?>&_keyword=<?=$_REQUEST["_keyword"]?>" class="a_button"><?=$_g->yhb_name?></a>
			<? } ?>
		</fieldset>
	</form>
</div>
<?
}
?>
