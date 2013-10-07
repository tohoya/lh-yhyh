<?php if($코딩시사용용) { ?>
<link href="../../common/css/default.css" rel="stylesheet" type="text/css">
<link href="../../common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="css/default.css" rel="stylesheet" type="text/css">
<script src="../../common/js/jquery.min.js"></script>
<script src="../../common/js/jquery.easing.1.3.js"></script>
<script src="../../common/js/jquery.form.js"></script>
<script src="../../common/js/jquery.lh.string1st.js"></script>
<script src="../../common/js/jquery.lh.calender1st.js"></script>
<script src="../../common/js/common.js"></script>
<? } ?>
<script>
$(window).load(function() {
	DesignFormInit(".FormDesignNormal");
	Email_Type_Change(<?=$_member->yhb_email_l == "direct" ? "true" : "false"?>, "yhb_email");
});
<?
/*
$_return_url = (eregi("_module=login", $_SERVER['HTTP_REFERER']) == eregi("_module=login", $_SERVER['REQUEST_URI'])) ? "/" : $_SERVER['HTTP_REFERER'];
$_return_board = $_REQUEST["_id"] ? $PHP_SELF."?_id=".$_REQUEST["_id"] : "";
$_url = ($_return_url== "/" && $_return_board) ? $_return_board : $_return_url;
*/
if($_REQUEST["_returnType"] == "admin") $_return_admin = "/yhboard/";

$return_url = $_REQUEST["_return_url"] ? $_REQUEST["_return_url"] : $_login_return_link;

?>
var return_url = "<?=$return_url?>";
var return_admin = "<?=$_return_admin?>";
var return_board = "<?=$_return_board?>";
<?
?>
function FormSubmitStart(f) {
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
		
		var pP = $("<p/>").html(data);
		var oP = $("> p", pP);
		if(oP.attr("class") != "error") {
			//alert("로그인 되었습니다!");
			//location.reload();
			if(return_admin) {
				window.open(return_admin, "");
			}
			location.href = return_url;
		} else {
			switch(oP.attr("title")) {
				case "empty_id":
					alert("아이디가 입력되지 않았습니다.");
					$("#yhb_id").focus();
				break;
				case "empty_pass":
					alert("비밀번호가 입력되지 않습니다.");
					$("#yhb_pass").focus();
				break;
				case "no_match":
					alert("입력한 아이디와 비밀번호가 일치하지 않습니다.");
				break;
				default:
					alert(oP.html());
			}
		}
		//$("#testView").val(data);
	});
}
</script>
<div id="yhyh_board_main_body" class="yhyh_login">
	<form class="FormDesignNormal" name="yhyh_login" id="yhyh_login" method="POST" onSubmit="return FormSubmitStart(this);">
		<fieldset>
			<legend>회원 로그인</legend>
			<input type="hidden" name="_module" value="login_proc">
			<input type="hidden" name="_id" value="<?=$_config->yhb_name?>">
			<input type="hidden" name="_m_no" value="<?=$_member->yhb_number?>">
			<div class="yh_view_title_top">
				<h2>MEMBER LOGIN</h2>
			</div>
			<div class="yh_login_body">
				<div class="yh_login_left">
					<p class="yhyh_input_text">
						<input type="text" name="yhb_id" id="yhb_id" value="" size="23" title="아이디" alt="필수">
						<label for="yhb_id" class="placeHolder">아이디</label>
					</p>
					<p class="yhyh_input_text">
						<input type="password" name="yhb_pass" id="yhb_pass" value="" size="23" title="비밀번호" alt="필수">
						<label for="yhb_pass" class="placeHolder">비밀번호</label>
					</p>
				</div>
				<div class="yh_login_right">
					<input type="submit" value="회원로그인">
				</div>
			</div>
			<div class="yh_login_footer">
				<span>회원가입을 원하시면 <a href="<?=$PHP_SELF?>?_module=register&_id=<?=$_config->yhb_name?>&_group=<?=$_group->yhb_number?>" class="a_button" title="회원가입">회원가입</a> 버튼을 클릭하여<br>회원가입을 진행하시기 바랍니다.
			</div>
			<p class="yh_login_back">
				<? if($_return_url == "/") { ?>
					<a href="/" class="a_button">홈으로</a>
				<? } ?>
				<? if($_back_link) { ?>
					<a href="<?=$_back_link?>" class="a_button">뒤로가기</a>
				<? } ?>
			</p>
		</fieldset>
	</form>
</div>