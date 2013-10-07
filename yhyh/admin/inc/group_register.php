<?
// 그룹 정보
$_g_result = $_LhDb->Get_Group($_REQUEST["_group"]);

$p = "^[&]|(&)*_admin=".$_p_pattern."|(&)*_group=".$_p_pattern;
$query_string = eregi_replace("^&|&$", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
if($query_string) $query_string = "&".$query_string;

?>
<link href="<?=_lh_yhyh_web?>/admin/css/group_register.css" rel="stylesheet" type="text/css">
<script>
$(window).load(function() {
	DesignFormInit(".FormDesignNormal");
	$("#yhb_name").change(function() {
		if($("#yhb_name").val() == "" || $("#yhb_name").val() != $("#_group_name").val()) {
			$("#_id_check").val("");
		}
	});
});

function Group_Id_Check() {
	if($("#yhb_name").FormInputCheck({ msg : "그룹이름은 2자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 2, max_msg : "영문/숫자 주합으로 2자 이상 적어주시기 바랍니다." }})) return;
	$.post("<?=_lh_yhyh_web?>/admin/", { "_admin" : "group_id_check_proc", "yhb_name" : $("#yhb_name").val() }, function(data) {
		var p = $("> p", $("<p/>").html(data));
		switch(p.attr("class")) {
			case "error":
				alert(p.html());
				$("#_id_check").val("");
			break;
			case "complete":
				switch (p.attr("title")) {
					case "use_name":
						alert(p.html());
						$("#_id_check").val(1);
					break;
					case "no_use_name":
						alert(p.html());
						$("#_id_check").val("");
					break;
				}
			break;
		}
	});
}

function FormSubmitStart(f) {
	if($("#yhb_name").FormInputCheck({ msg : "그룹이름은 2자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 2, max_msg : "영문/숫자 주합으로 2자 이상 적어주시기 바랍니다." }, no_eng_num : "그룹이름은 영문 숫자 조합만 가능합니다." })) return false;
	if($("#yhb_name").val() != "" && $("#yhb_name").val() == $("#_group_name").val()) {
		$("#_id_check").val(1);
	}
	if($("#_id_check").FormInputCheck({ msg : "그룹이름 중복체크 확인을 해주세요."})) return false;
	
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/admin/", function(data) {
		//alert(data);
		var p = $("> p", $("<p/>").html(data));
		switch(p.attr("class")) {
			case "error":
				alert(p.html());
			break;
			case "complete":
				alert(p.html());
				switch(p.attr("title")) {
					case "modify":
						location.reload();
					break;
					case "register":
						location.href = "<?=$PHP_SELF?>?_admin=group_manager<?=$query_string?>";
					break;
				}
			break;
		}
		//$("#testView").css("display", "block").val(data);
	});
	
}

function Group_Admin_Grant(This, target_name) {
	if($("select[name=" + target_name + "]").length > 0) {
		$("select[name=" + target_name + "]").get(0).disabled = $(This).get(0).checked;
	}
}

function Group_Change_Regist(This) {
	location.href = "<?=$PHP_SELF?>?_admin=group_register<?=$query_string?>&_group=" + $(This).val()
}

</script>
<div class="yhyh_group_register">
	<form class="FormDesignNormal" name="yhyh_group_regist" id="yhyh_group_regist" method="POST" onSubmit="return FormSubmitStart(this);">
		<fieldset>
			<legend>게시판 등록/수정</legend>
			<input type="hidden" name="_admin" value="group_register_proc">
			<input type="hidden" name="_group" id="_group" value="<?=$_g_result->yhb_number?>">
			<input type="hidden" id="_group_name" value="<?=$_g_result->yhb_name?>">
			<input type="hidden" name="_id_check" id="_id_check" value="<?=$_g_result->yhb_number ? "1" : ""?>" alt="필수">
			<table class="yhyh_table_member">
				<tbody>
					<tr><th colspan="3" class="yh_regist_title_top"><h2>그룹 기본 정보</h2></th><td class="yh_middle_submit"><input type="submit" value="그룹정보저장"><a href="<?=$PHP_SELF?>?_admin=group_manager<?=$query_string?>" class="a_button">뒤로가기</a></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">그룸 이름</span></th>
						<td colspan="3">
							<? if($_g_result->yhb_name) { ?>
							<select name="yhb_name_change" onChange="Group_Change_Regist(this);">
							<?
							$query_group = "select yhb_name, yhb_number from yh_group order by yhb_name asc";
							$result_group = $_LhDb->Query($query_group);
							while($gc = $_LhDb->Fetch_Object($result_group)) {
							?>
							<option value="<?=$gc->yhb_number?>"<?=$gc->yhb_number == $_g_result->yhb_number ? " selected" : ""?>><?=$gc->yhb_name?></option>
							<? } ?>
							</select>
							<? } ?>
							<input type="text" name="yhb_name" id="yhb_name" value="<?=$_g_result->yhb_name?>" size="20" title="그룹 이름" alt="필수">
							<label for="yhb_name" class="placeHolder">한글/영문/숫자 입력</label>
							<input type="button" value="그룹이름중복체크" onClick="Group_Id_Check();">
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">그룹스킨/언어 설정</span></th>
						<td colspan="3">
							<span class="field_title">스킨</span>
							<select name="yhb_skin">
<?
if(trim(!$_g_result->yhb_skin)) $_g_result->yhb_skin = $_REQUEST["_skin"] ? $_REQUEST["_skin"] : "default";
$skin_dir = opendir(_lh_document_root._lh_yhyh_web."/group_skin");
while($dir = readdir($skin_dir)) {
	if(!eregi("\.",$dir)) {
?>
								<option value="<?=$dir?>"<?=$dir == $_g_result->yhb_skin ? " selected" : ""?>><?=$dir?></option>
<? } } ?>
							</select>
							<span class="field_title">언어</span>
							<select name="yhb_language">
								<option value="board">게시판설정으로 사용</option>
								<option value="">한국어(Korea, 게시판포함)</option>
								<option value="eng"<?=$_g_result->yhb_language == "eng" ? " selected" : ""?>>영어(English, 게시판포함)</option>
								<option value="jp"<?=$_g_result->yhb_language == "jp" ? " selected" : ""?>>일본어(Japan, 게시판포함)</option>
							</select>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">출력 설정</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_open_group" id="yhb_open_group" value="1"<?=$_g_result->yhb_name ? $_g_result->yhb_open_group == 1 ? " checked" : "" : " checked"?>>
							<label for="yhb_open_group">그룹 공개</label>
							<input type="checkbox" name="yhb_use_join" id="yhb_use_join" value="1"<?=$_g_result->yhb_name ? $_g_result->yhb_use_join == 1 ? " checked" : "" : " checked"?>>
							<label for="yhb_use_join">회원가입 공개</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">신규가입 레벨</span></th>
						<td colspan="3">
							<select name="yhb_level">
								<? for($i = 10; $i > 0; $i--) { ?>
								<option value="<?=$i?>"<?=$_g_result->yhb_level == $i ? " selected" : ""?>>Lv. <?=$i < 10 ? $i : "10(Guest와 동일)"?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">관리자 승인 게시판</span></th>
						<td colspan="3">
							<input type="checkbox" name="yhb_use_agreement" id="yhb_use_agreement" value="1"<?=$_g_result->yhb_use_agreement == 1 ? " checked" : ""?>>
							<label for="yhb_use_agreement">체크 할 경우 관리자가 출력 승인을 해야만 출력이 됩니다.</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">포인트 관리</span></th>
						<td colspan="3">
							<span class="field_title">회원가입</span>
							<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_join_point" id="yhb_join_point" value="<?=($_g_result->yhb_join_point) ? $_g_result->yhb_join_point : 50?>" size="5" title="회원가입 포인트">
							<label for="yhb_join_point" class="placeHolder">숫자</label>
							<span class="field_title">로그인(1일1회)</span>
							<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_login_point" id="yhb_login_point" value="<?=($_g_result->yhb_login_point) ? $_g_result->yhb_login_point : 20?>" size="5" title="로그인 포인트">
							<label for="yhb_login_point" class="placeHolder">숫자</label>
							<span class="field_title">글작성</span>
							<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_board_point" id="yhb_board_point" value="<?=($_g_result->yhb_board_point) ? $_g_result->yhb_board_point : 10?>" size="5" title="글작성 포인트">
							<label for="yhb_board_point" class="placeHolder">숫자</label>
							<span class="field_title">뎃글</span>
							<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_memo_point" id="yhb_memo_point" value="<?=($_g_result->yhb_memo_point) ? $_g_result->yhb_memo_point : 5?>" size="5" title="뎃글 포인트">
							<label for="yhb_memo_point" class="placeHolder">숫자</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">자동 등업 조건</span></th>
						<td colspan="3">
							<table class="yh_address_form">
								<tbody>
									<tr>
										<td>
											<span class="field_title">Lv.9</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_9" id="yhb_point_limit_9" value="<?=$_g_result->yhb_point_limit_9?>" size="6" title="9등업 포인트">
											<label for="yhb_point_limit_9" class="placeHolder">포인트</label>
											<span class="field_title">Lv.8</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_8" id="yhb_point_limit_8" value="<?=$_g_result->yhb_point_limit_8?>" size="6" title="8등업 포인트">
											<label for="yhb_point_limit_8" class="placeHolder">포인트</label>
											<span class="field_title">Lv.7</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_7" id="yhb_point_limit_7" value="<?=$_g_result->yhb_point_limit_7?>" size="6" title="7등업 포인트">
											<label for="yhb_point_limit_7" class="placeHolder">포인트</label>
											<span class="field_title">Lv.6</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_6" id="yhb_point_limit_6" value="<?=$_g_result->yhb_point_limit_6?>" size="6" title="6등업 포인트">
											<label for="yhb_point_limit_6" class="placeHolder">포인트</label>
											<span class="field_title">Lv.5</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_5" id="yhb_point_limit_5" value="<?=$_g_result->yhb_point_limit_5?>" size="6" title="5등업 포인트">
											<label for="yhb_point_limit_5" class="placeHolder">포인트</label>
										</td>
									</tr>
									<tr>
										<td>
											<span class="field_title">Lv.4</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_4" id="yhb_point_limit_4" value="<?=$_g_result->yhb_point_limit_4?>" size="6" title="4등업 포인트">
											<label for="yhb_point_limit_4" class="placeHolder">포인트</label>
											<span class="field_title">Lv.3</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_3" id="yhb_point_limit_3" value="<?=$_g_result->yhb_point_limit_3?>" size="6" title="3등업 포인트">
											<label for="yhb_point_limit_3" class="placeHolder">포인트</label>
											<span class="field_title">Lv.2</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_2" id="yhb_point_limit_2" value="<?=$_g_result->yhb_point_limit_2?>" size="6" title="2등업 포인트">
											<label for="yhb_point_limit_2" class="placeHolder">포인트</label>
											<span class="field_title">Lv.1</span>
											<input class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" type="text" name="yhb_point_limit_1" id="yhb_point_limit_1" value="<?=$_g_result->yhb_point_limit_1?>" size="6" title="1등업 포인트">
											<label for="yhb_point_limit_1" class="placeHolder">포인트</label>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="yhyh_write_button">
				<input type="submit" value="그룹정보저장">
				<a href="<?=$PHP_SELF?>?_admin=group_manager<?=$query_string?>" class="a_button">뒤로가기</a>
			</div>
			<textarea cols="100" rows="20" id="testView" style="display:none;"></textarea>
		</fieldset>
	</form>
</div>
<? if($_solo_mode) { ?>
</body>
</html>
<? } ?>