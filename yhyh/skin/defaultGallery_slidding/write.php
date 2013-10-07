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
<script type="text/javascript" src="<?=_lh_yhyh_web?>/se/js/HuskyEZCreator.js" charset="utf-8"></script>
<script>
		
var oEditors = [];
(function() {
	$(window).load(function() {
		var editers = new LHtmlEditerClass();
		editers.Init("#LHtmlEditers", "#yhb_content", $("#yhb_file").val(), 680, 450);
		$("#yhb_board_pass_view").change(function() {
			switch($(this).get(0).checked) {
				case true:
					var o = $("#yhb_board_pass").clone();
					o.attr("type", "text");
				break;
				case false:
					var o = $("#yhb_board_pass").clone();
					o.attr("type", "password");
				break;
			}
			var oLabel = $("label[for=yhb_board_pass]");
			$("#yhb_board_pass").replaceWith(o);
			$("#yhb_board_pass").focus(function() {
				oLabel.css("visibility", "hidden");
			})
			.blur(function() {
				if(!$(this).val()) {
					oLabel.css("visibility", "visible");
				}
			});
		});
		
		DesignFormInit(".FormDesignNormal");
		nhn.husky.EZCreator.createInIFrame({
			oAppRef: oEditors,
			elPlaceHolder: "yhb_content",
			sSkinURI: "<?=_lh_yhyh_web?>/se/SmartEditor2Skin.html",	
			htParams : {
				bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
				bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
				bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
				fOnBeforeUnload : function(){
					//alert("아싸!");	
				}
			}, //boolean
			fOnAppLoad : function(){
				//예제 코드
				//oEditors.getById["content_textarea"].exec("PASTE_HTML", [$("#content_textarea").val()]);
			},
			fCreator: "createSEditor2"
		});
	});
})();


function FormSubmitStart(f) {
	
	if($("[name=yhb_title]", f).length > 0 && !f.yhb_title.value) {
		alert("글 제목을 입력해주세요.");
		f.yhb_title.focus();
		return false;
	}
	
	if($("[name=yhb_name]", f).length > 0 && !f.yhb_name.value) {
		alert("작성자 이름을 입력해주세요.");
		f.yhb_name.focus();
		return false;
	}
	
	if($("[name=yhb_board_pass]", f).length > 0 && f.yhb_board_pass.value.length < 4) {
		alert("비밀번호를 4글자 이상 영문, 숫자 조합으로 입력해주세요.");
		f.yhb_board_pass.focus();
		return false;
	}
	
	oEditors.getById["yhb_content"].exec("UPDATE_CONTENTS_FIELD", []);
	
	f.write_complete_LHtmlEditers.value = 1;
	f._module.value = "write_proc";
	
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
		//alert(data);
		var p = $("<p/>").html(data);
		var type = $("> p", p).attr("title");

		if($("> p", p).attr("title") == "error") {
			//alert($("> p", p).attr("title"));
			$(".yhyh_textarea").css("display", "block").find("> textarea").val(data);
		} else {
			switch(type) {
				case "write":
					alert("글 작성이 완료 되었습니다.");
					location.href = "<?=$PHP_SELF?><?=$query_string ? "?".$query_string : ""?>";
				break;
				case "modify":
					alert("글 수정이 완료 되었습니다.");
					location.href = "<?=$PHP_SELF?><?=$query_string ? "?".$query_string."&_no=".$_REQUEST["_no"] : "?_no=".$_REQUEST["_no"]?>"; //?_id=<?=$_REQUEST["_id"]?>&_no=<?=$_REQUEST["_no"]?>";
					//location.reload();
				break;
				case "reply":
					alert("답글 작성이 완료 되었습니다.");
					location.href = "<?=$PHP_SELF?><?=$query_string ? "?".$query_string : ""?>";
				break;
			}
		}
	});
	
}

</script>
<div class="yhyh_write">
	<form class="FormDesignNormal" name="yhyh_write" id="yhyh_write" method="POST" onSubmit="return FormSubmitStart(this);" enctype="multipart/form-data">
		<fieldset>
			<legend>글쓰기</legend>
			<input type="hidden" name="_module" value="write_proc">
			<input type="hidden" name="_writeMode" value="<?=$_REQUEST["_writeMode"]?>">
			<input type="hidden" name="_id" value="<?=$_REQUEST["_id"]?>">
			<input type="hidden" name="_no" value="<?=$_REQUEST["_no"]?>">
			<input type="hidden" name="yhb_html" value="2">
			<input type="hidden" id="yhb_file" name="yhb_file" value="<?=$_files?>">
			<input type="hidden" id="write_complete_LHtmlEditers" name="write_complete_LHtmlEditers" value="">
			<input type="hidden" id="file_add_LHtmlEditers" name="file_add_LHtmlEditers" value="">
			<input type="hidden" id="file_delete_LHtmlEditers" name="file_delete_LHtmlEditers" value="">
			<div class="yhyh_input_text">
				<span class="field_title"><b>*</b>제목</span>
				<input type="text" name="yhb_title" id="yhb_title" value="<?=$_result->title?>" size="108" title="글 제목">
				<label for="yhb_title" class="placeHolder">글 제목</label>
			</div>
			<div class="yhyh_textarea">
				<textarea name="yhb_content" id="yhb_content" cols="100" rows="20"><?=$_result->content?></textarea>
			</div>
			<div id="LHtmlEditers" class="yhyh_content">
			</div>
			<div class="yhyh_input_text">
				<span class="field_title"><b>*</b>이름</span>
				<input type="text" name="yhb_name" id="yhb_name" value="<?=$_result->name?>" size="20" title="이름">
				<label for="yhb_name" class="placeHolder">작성자 이름</label>
				<span class="field_title">이메일</span>
				<input type="text" name="yhb_email" id="yhb_email" value="<?=$_result->email?>" size="30" title="@이메일.com">
				<label for="yhb_email" class="placeHolder">@이메일.com</label>
				<span class="field_title">홈페이지</span>
				<input type="text" name="yhb_homepage" id="yhb_homepage" value="<?=$_result->homepage?>" size="30" title="http://홈페이지">
				<label for="yhb_homepage" class="placeHolder">http://홈페이지</label>
			</div>
			<? if(strtolower($_REQUEST["_writeMode"]) != "modify" && !$_member->yhb_number) { ?>
			<div class="yhyh_input_text">
				<span class="field_title"><b>*</b>비밀번호</span>
				<input type="password" name="yhb_board_pass" id="yhb_board_pass" value="" size="16" title="비밀번호">
				<label for="yhb_board_pass" class="placeHolder">비밀번호</label>
				<? if(strtolower($_REQUEST["_writeMode"]) != "modify") { ?>
				<span class="field_title"><b>*</b>수정/삭제등에 필요합니다.</span>
				<? } else { ?>
				<span class="field_title"><b>*</b>수정하려면 등록시 비밀번호를 입력하세요.</span>
				<? } ?>
				<input type="checkbox" id="yhb_board_pass_view">
				<label for="yhb_board_pass_view">비밀번호 보기</label>
			</div>
			<? } ?>
			<div class="yhyh_input_text">
				<input type="checkbox" name="yhb_secret" id="yhb_secret" value="1"<?=$_result->secret_checked?>>
				<label for="yhb_secret">비밀글 작성</label>
				<input type="checkbox" name="yhb_notice" id="yhb_notice" value="1"<?=$_result->notice_checked?>>
				<label for="yhb_notice">공지글 작성</label>
				<!--input type="checkbox" name="yhb_check" id="yhb_check" value="1"<?=$_result->check_checked?>>
				<label for="yhb_check">글 공개하기</label>
				<input type="checkbox" name="yhb_reply_mail" id="yhb_reply_mail" value="1"<?=$_result->reply_mail_checked?>>
				<label for="yhb_reply_mail">메일로 답장 받기</label-->
			</div>
			<div class="yhyh_write_button">
				<input type="submit" value="저장하기">
				<a href="<?=$_back_link?>" class="a_button">돌아가기</a>
			</div>
		</fieldset>
	</form>
</div>
