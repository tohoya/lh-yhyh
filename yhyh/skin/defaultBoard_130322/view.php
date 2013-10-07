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
(function() {
	$(window).load(function() {
		DesignFormInit(".FormDesignNormal");
		$(".pass_check_form").slideUp(500, "easeOutCubic", function() {
			$(this).css({
				opacity : 1
			});
		});
	});
})();

function Pass_Check_Form_Show(mode, type) {
	var oF = $("#yhyh_form_send");
	if(oF.length == 0) return;
	var f = oF.get(0);
	
	var func = null;
	if(f._checkMode.value != "" && mode != '' && f._checkMode.value != mode) {
		type = false;
		func = function() {
			$(this).slideDown(500, "easeOutCubic");
		};
	}
	
	f._checkMode.value = (!mode) ? "" : mode;
	
	switch(type) {
		case true:
			$(".pass_check_form").slideDown(500, "easeOutCubic");
		break;
		case false:
			$("#yhb_board_pass").val("");
			$("#yhb_board_pass").change();
			$(".pass_check_form").slideUp(500, "easeOutCubic", func);
		break;
		default:
			$("#yhb_board_pass").val("");
			$("#yhb_board_pass").change();
			$(".pass_check_form").slideToggle(500, "easeOutCubic");
	}
}

function FormSubmitStart(f) {
	
	if($("#yhb_board_pass").FormInputCheck({ msg : "게시판 등록시 비밀번호를 입력하세요."})) return false;
	f._module.value = "pass_check_proc";
	
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
		//alert(data);
		var p = $("> p", $("<p/>").html(data));
		switch(p.attr("class")) {
			case "error":
				alert(p.html());
			break;
			case "complete":
				switch(f._checkMode.value) {
					case "modify":
						location.href = "<?=$_modify_link_original?>";
					break;
					case "delete":
						Rows_Delete_Check("<?=$_REQUEST["_no"]?>");
					break;
				}
			break;
		}
	});
	
}
</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ko_KR/all.js#xfbml=1&appId=115818425251863";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="yhyh_view">
	<div class="yhyh_view_body">
		<div class="yhyh_view_title">
			<h1><?=$_result->title?></h1>
			<div>
				<span><?=date("Y.m.d", $_result->date)?></span>
			</div>
		</div>
		<div class="yhyh_ari">
			<div class="yhyh_info_left">
				<label>작성자 : </label>&nbsp;<a><?=$_result->name?></a>
			</div>
			<div class="yhyh_info_right">
				<? if($_result->ip) { ?><label>IP : </label>&nbsp;<span>(<?=$_result->ip?>)</span>, <? } ?>
				<label>HIT : </label>&nbsp;<span><?=number_format($_result->hit)?></span>, 
				<label>VOTE : </label>&nbsp;<span><?=number_format($_result->vote)?></span>
			</div>
		</div>
		<div class="yhyh_social">
			<span class="fb-like" data-href="<?=$_protocol?><?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></span>
		</div>
		<div class="yhyh_view_content">
			<iframe id="content_frame" src="<?=_lh_yhyh_web?>/?_module=view&_content_frame=content_frame&_no=<?=$_result->number?>"></iframe>
		</div>
		<?
		/*
		if(sizeof($_result->file) > 0) {
			?><img src="<?=$_result->file[0]->url?>"><?
		}
		*/
		?>
		<div class="pass_check_form">
			<form class="FormDesignNormal" name="yhyh_form_send" id="yhyh_form_send" method="POST" onSubmit="return FormSubmitStart(this);">
				<fieldset>
					<legend>수정 혹은 삭제</legend>
					<input type="hidden" name="_module" value="pass_check_proc">
					<input type="hidden" name="_checkMode" value="">
					<input type="hidden" name="_id" value="<?=$_config->yhb_name?>">
					<input type="hidden" name="_no" value="<?=$_REQUEST["_no"]?>">
					<span class="pass_modify_form">
						<input type="password" name="yhb_board_pass" id="yhb_board_pass" alt="필수">
						<label for="yhb_board_pass">비밀번호</label>
						<input type="submit" value="확인">
						<input type="button" value="취소" onClick="Pass_Check_Form_Show('', false);">
					</span>
				</fieldset>
			</form>
		</div>
		<div class="yhyh_view_button">
			<div class="view_button_left">
				<a class="a_button_left" href="<?=$_modify_link?>">수 정</a><a class="a_button_middle" href="<?=$_delete_link?>">삭 제</a><a class="a_button_right" href="<?=$_reply_link?>">답 글</a>
			</div>
			<div class="view_button_right">
				<a href="<?=$_list_link?>" class="a_button">목 록</a>
			</div>
		</div>
	</div>
</div>
