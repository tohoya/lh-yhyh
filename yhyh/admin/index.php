<?
// 반드시 설정되어저야 한다.
include_once("../module/inc/root_info.php");
include_once("./inc/common.php");

if(!$_REQUEST["_admin"]) $_REQUEST["_admin"] = "group_manager";

switch(strtolower($_REQUEST["_admin"])) {
	case "group_manager":
		$pgCode = "1/1";
		$_head_title = "그룹 관리 리스트";
	break;
	case "group_register":
		$pgCode = "1/2";
		$_head_title = "그룹 신규등록/수정";
	break;
	case "board_manager":
		$pgCode = "2/1";
		$_head_title = "게시판 관리 리스트";
	break;
	case "board_register":
		$pgCode = "2/2";
		$_head_title = "게시판 신규등록/수정";
	break;
	case "member_manager":
		$_head_title = "회원 관리 리스트";
		$pgCode = "3/1";
	break;
	case "member_register":
		$pgCode = "3/2";
		$_head_title = "회원 신규등록/수정";
	break;
	case "popup_manager":
		$pgCode = "4/1";
		$_head_title = "팝업창 관리";
	break;
}

$query = "select yhb_number from yh_member where yhb_group_no != ''";
$_count->member = $_LhDb->Query_Row_Num($query);

$query = "select yhb_number from yh_config_board";
$_count->board = $_LhDb->Query_Row_Num($query);

$query = "select yhb_number from yh_board";
$_count->rows = $_LhDb->Query_Row_Num($query);

$query = "select yhb_number from yh_group";
$_count->group = $_LhDb->Query_Row_Num($query);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$_head_title?> - No Developer Board Manager</title>
<link href="<?=_lh_yhyh_web?>/common/css/default.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/admin/css/common.css" rel="stylesheet" type="text/css">
<script>
var $_yhyh_web = "<?=_lh_yhyh_web?>";
</script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/browser.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.navi.height.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/common.js"></script>
<script src="<?=_lh_yhyh_web?>/admin/js/common.js"></script>
<script>
$(window).load(function() {
	var left_navi = new Lh_Navi_Show();
	left_navi.Init("#navi_left", "left_menu", 1, "<?=$pgCode?>");
	
	DesignFormInit(".FormDesignNormal");
});
</script>
</head>

<body id="yhyh_board_main_body">
	<div id="wrap">
		<div id="header">
			<h1>No Developer Board Manager</h1>
			<span>
				<label>administrator Name : </label>
				<?=$_member->yhb_name?>	(<?=$_member->yhb_id?>)
				<a href="<?=_lh_yhyh_web?>/?_module=logout&return_url=<?=_lh_yhyh_web?>/admin/" class="a_button">Logout</a>
				<a href="<?=$PHP_SELF?>?_admin=member_register&_m_no=<?=$_member->yhb_number?>" class="a_button">Modify</a>
			</span>
		</div>
		<div id="content">
			<div id="left_layout">
				<div id="status_area">
					<h2>Status</h2>
					<p>그룹 수 : <strong><?=number_format($_count->group)?></strong> 개</p>
					<p>회원 수 : <strong><?=number_format($_count->member)?></strong> 명</p>
					<p>게시판 수 : <strong><?=number_format($_count->board)?></strong> 개</p>
					<p>게시물 수 : <strong><?=number_format($_count->rows)?></strong> 개 글</p>
				</div>
				<div id="navi_left">
					<ul class="_menu_body">
						<li class="<?=$_REQUEST["_admin"] == "group_manager" || $_REQUEST["_admin"] == "group_register" ? "depth_01_selecter " : ""?>depth_01_button _button_body"><a href="?_admin=group_manager" class="_button_link">그룹관리</a>
							<ul class="_menu_body">
								<li class="depth_02_button _button_body"><a href="?_admin=group_manager" class="<?=$_REQUEST["_admin"] == "group_manager" ? "depth_02_selecter " : ""?>_button_link">&loz;&nbsp;그룹리스트</a></li>
								<li class="depth_02_button _button_body"><a href="?_admin=group_register" class="<?=$_REQUEST["_admin"] == "group_register" ? "depth_02_selecter " : ""?>_button_link">&loz;&nbsp;신규그룹등록</a></li>
							</ul>
						</li>
						<li class="<?=$_REQUEST["_admin"] == "board_manager" || $_REQUEST["_admin"] == "board_register" ? "depth_01_selecter " : ""?>depth_01_button _button_body"><a href="?_admin=board_manager" class="_button_link">게시판관리</a>
							<ul class="_menu_body">
								<li class="depth_02_button _button_body"><a href="?_admin=board_manager" class="<?=$_REQUEST["_admin"] == "board_manager" ? "depth_02_selecter " : ""?>_button_link">&loz;&nbsp;게시판리스트</a></li>
								<li class="depth_02_button _button_body"><a href="?_admin=board_register" class="<?=$_REQUEST["_admin"] == "board_register" ? "depth_02_selecter " : ""?>_button_link">&loz;&nbsp;신규게시판등록</a></li>
							</ul>
						</li>
						<li class="<?=$_REQUEST["_admin"] == "member_manager" || $_REQUEST["_admin"] == "member_register" ? "depth_01_selecter " : ""?>_button_body"><a href="?_admin=member_manager" class="_button_link">회원관리</a>
							<ul class="_menu_body">
								<li class="depth_02_button _button_body"><a href="?_admin=member_manager" class="<?=$_REQUEST["_admin"] == "member_manager" ? "depth_02_selecter " : ""?>_button_link">&loz;&nbsp;회원리스트</a></li>
								<li class="depth_02_button _button_body"><a href="?_admin=member_register" class="<?=$_REQUEST["_admin"] == "member_register" ? "depth_02_selecter " : ""?>_button_link">&loz;&nbsp;신규회원등록</a></li>
							</ul>
						</li>
						<li class="<?=$_REQUEST["_admin"] == "popup_manager" ? "depth_01_selecter " : ""?>_button_body"><a href="?_admin=popup_manager" class="_button_link">팝업창</a>
							<ul class="_menu_body">
								<li class="depth_02_button _button_body"><a href="?_admin=popup_manager" class="<?=$_REQUEST["_admin"] == "popup_manager" ? "depth_02_selecter " : ""?>_button_link">&loz;&nbsp;팝업창관리</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div id="content_area">
				<h2><p><?=$_REQUEST["_admin"]?><span>No Developer Board</span></p></h2>
				<div id="contentW">
					<?
					$load_file = _lh_document_root._lh_yhyh_web."/admin/inc/".$_REQUEST["_admin"].".php";
					@include_once($load_file);
					?>
				</div>
			</div>
		</div>
		<div id="footer">copyright by 2003 ~ 2013 No Developer Board</div>
	</div>
</body>
</html>