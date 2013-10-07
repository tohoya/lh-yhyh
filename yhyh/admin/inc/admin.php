<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>관리자로그인</title>
<link href="<?=_lh_yhyh_web?>/common/css/default.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/group_skin/default/css/default.css" rel="stylesheet" type="text/css">
<script>
var $_yhyh_web = "<?=_lh_yhyh_web?>";
</script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/browser.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/common.js"></script>
<script>
$(window).load(function() {
	DesignFormInit(".FormDesignNormal");
});

var return_url = "<?=$_REQUEST["_return_url"] ? $_REQUEST["_return_url"] : $_SERVER['REQUEST_URI']?>";

function FormSubmitStart(f) {
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
		//alert(data);
		var pP = $("<p/>").html(data);
		var oP = $("> p", pP);
		if(oP.attr("class") != "error") {
			//alert("로그인 되었습니다!");
			//location.reload();
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
		$("#testView").val(data);
	});
	
}
</script>
</head>

<body>
<div class="yhyh_login">
	<form class="FormDesignNormal" name="yhyh_login" id="yhyh_login" method="POST" onSubmit="return FormSubmitStart(this);">
		<fieldset>
			<legend>관리자 로그인</legend>
			<input type="hidden" name="_module" value="login_proc">
			<input type="hidden" name="_id" value="<?=$_config->yhb_name?>">
			<input type="hidden" name="_m_no" value="<?=$_member->yhb_number?>">
			<div class="yh_view_title_top">
				<h2>ADMINISTRATOR LOGIN</h2>
			</div>
			<div class="yh_login_body">
				<div class="yh_login_left">
					<p class="yhyh_input_text">
						<input type="text" name="yhb_id" id="yhb_id" value="" size="23" title="아이디" alt="필수">
						<label for="yhb_id" class="placeHolder">관리자 아이디</label>
					</p>
					<p class="yhyh_input_text">
						<input type="password" name="yhb_pass" id="yhb_pass" value="" size="23" title="비밀번호" alt="필수">
						<label for="yhb_pass" class="placeHolder">비밀번호</label>
					</p>
				</div>
				<div class="yh_login_right">
					<input type="submit" value="관리자로그인">
				</div>
			</div>
			<div class="yh_login_footer">
			<span>관리자에게만 권한이 있습니다.<br>
			관리자로 로그인해 주시기 바랍니다.</div>
			<p class="yh_login_back">
				<a href="/" class="a_button">홈으로</a>
				<a href="javascript:;" onClick="history.back();" class="a_button">뒤로가기</a>
				<? if($_member->yhb_number) { ?>
				<a href="<?=_lh_yhyh_web?>/?_module=logout&return_url=<?=_lh_yhyh_web?>/admin/" class="a_button">로그아웃 후 다른 아이디로 로그인</a>
				<? } ?>
			</p>
		</fieldset>
	</form>
</div>
</body>
</html>