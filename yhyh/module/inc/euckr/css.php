<link href="<?=_lh_yhyh_web?>/common/css/default.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<?
switch($_REQUEST["_module"]) {
	case "login":
	case "register":
	case "message":
	case "password":
		$_css_web = $_skin_group_web;
	break;
	default:
		$_css_web = eregi("password.php|message.php", $PHP_SELF) ? $_skin_group_web : _lh_skin_web;
}
?>
<link href="<?=$_css_web?>css/default.css" rel="stylesheet" type="text/css">
