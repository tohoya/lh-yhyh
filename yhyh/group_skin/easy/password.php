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
<link href="<?=$_skin_group_web?>css/default.css" rel="stylesheet" type="text/css">
<script>
$(window).load(function() {
	DesignFormInit(".FormDesignNormal");
	Email_Type_Change(<?=$_member->yhb_email_l == "direct" ? "true" : "false"?>, "yhb_email");
});
var return_url = "<?=$_REQUEST["return_url"] ? $_REQUEST["return_url"] : $_SERVER['HTTP_REFERER']?>";

function FormSubmitStart(f) {
	if(!$("#yhb_board_pass").val()) {
		alert("비밀번호를 입력해주세요.");
		$("#yhb_board_pass").focus();
		return false;
	}
	
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
		var pP = $("<p/>").html(data);
		var oP = $("> p", pP);
		if(oP.attr("class") != "error") {
			location.reload();
		} else {
			switch(oP.attr("title")) {
				default:
					$("#yhb_board_pass").select();
					alert(oP.html());
			}
		}
		$("#testView").val(data);
	});
	
}
</script>
<div id="yhyh_board_main_body" class="yhyh_message">
	<form class="FormDesignNormal" name="yhyh_message" id="yhyh_message" method="POST" onSubmit="return FormSubmitStart(this);">
		<fieldset>
			<legend>비밀번호 확인</legend>
			<input type="hidden" name="_checkMode" value="secret">
			<input type="hidden" name="_module" value="pass_check_proc">
			<input type="hidden" name="_no" value="<?=$_REQUEST["_no"]?>">
			<div class="yh_view_title_top">
				<h2>비밀번호 필요</h2></div>
			<div class="yh_password_body">
				<p class="no_list_rows"><?=$s_grant->message?></p>
				<div class="yh_password_input">
					<p class="yhyh_input_text">
						<input type="password" name="yhb_board_pass" id="yhb_board_pass" value="" size="23" title="비밀빈호" alt="필수" placeHolder="비밀번호">
						<label for="yhb_board_pass" class="placeHolder">비밀번호</label>
					</p>
				</div>
			</div>
			<div class="yh_password_footer">
				<input type="submit" value="비밀빈호 확인" />
				<a href="<?=eregi("_no=", $_LhDb->Return_Url_Get("back")) ? $_list_link : $_LhDb->Return_Url_Get("back")?>" class="a_button">뒤로가기</a>
			</div>
		</fieldset>
	</form>
</div>