<?php if($코딩시사용용) { ?>
<link href="../../common/css/default.css" rel="stylesheet" type="text/css">
<link href="../../common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="../default - Copy/css/default.css" rel="stylesheet" type="text/css">
<script src="../../common/js/jquery.min.js"></script>
<script src="../../common/js/jquery.easing.1.3.js"></script>
<script src="../../common/js/jquery.form.js"></script>
<script src="../../common/js/jquery.lh.string1st.js"></script>
<script src="../../common/js/jquery.lh.calender1st.js"></script>
<script src="../../common/js/common.js"></script>
<? } ?>
<link href="<?=$_skin_group_web?>css/default.css" rel="stylesheet" type="text/css">
<script>
$(window).load(function() {
	DesignFormInit(".FormDesignNormal");
	Email_Type_Change(<?=$_member->yhb_email_l == "direct" ? "true" : "false"?>, "yhb_email");
});
var return_url = "<?=$_REQUEST["return_url"] ? $_REQUEST["return_url"] : $_SERVER['HTTP_REFERER']?>";

function FormSubmitStart(f) {
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
		var pP = $("<p/>").html(data);
		var oP = $("> p", pP);
		if(oP.attr("class") != "error") {
			alert("로그인 되었습니다!");
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
<div id="yhyh_board_main_body" class="yhyh_message">
	<form class="FormDesignNormal" name="yhyh_message" id="yhyh_message" method="POST">
		<fieldset>
			<legend>회원 로그인</legend>
			<input type="hidden" name="_module" value="login_proc">
			<input type="hidden" name="_id" value="<?=$_config->yhb_name?>">
			<input type="hidden" name="_m_no" value="<?=$_member->yhb_number?>">
			<div class="yh_view_title_top">
				<h2>메세지</h2></div>
			<div class="yh_message_body">
				<p class="no_list_rows"><?=$_grant->message?></p>
			</div>
			<div class="yh_message_footer">
				<? if($_grant->button->login == true) { ?>
				<a href="<?=$_login_grant_link?>" class="a_button">회원로그인</a>
				<? } ?>
				<? if($_grant->button->list == true) { ?>
				<a href="<?=$_list_link?>" class="a_button">목록보기</a>
				<? } ?>
				<? if($_grant->button->back == true && $_LhDb->Return_Url_Get("back") != "/") { ?>
				<a href="<?=$_LhDb->Return_Url_Get("back")?>" class="a_button">뒤로가기</a>
				<? } ?>
			</div>
		</fieldset>
	</form>
</div>