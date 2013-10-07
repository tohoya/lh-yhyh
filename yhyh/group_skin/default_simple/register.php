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
	Email_Type_Change(<?=$_m_result->yhb_email_l == "direct" ? "true" : "false"?>, "yhb_email");
	$("#yhb_id").change(function() {
		$("#_id_check").val("");
	});
});

function Member_Id_Check() {
	if($("#yhb_id").FormInputCheck({ msg : "아이디를 4자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 4, max_msg : "영문/숫자 주합으로 4자 이상 적어주시기 바랍니다." }, no_eng_num : "아이디는 영문 숫자 조합만 가능합니다."})) return;
	$.post("<?=_lh_yhyh_web?>/", { "_module" : "id_check_proc", "user_id" : $("#yhb_id").val() }, function(data) {
		//alert(data);
		var p = $("> p", $("<p/>").html(data));
		switch(p.attr("class")) {
			case "error":
				alert(p.html());
				$("#_id_check").val("");
			break;
			case "complete":
				switch (p.attr("title")) {
					case "use_id":
						alert(p.html());
						$("#_id_check").val(1);
					break;
					case "no_use_id":
						alert(p.html());
						$("#_id_check").val("");
					break;
				}
			break;
		}
	});
}

function FormSubmitStart(f) {
	if($("#yhb_id").FormInputCheck({ msg : "아이디를 4자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 4, max_msg : "영문/숫자 주합으로 4자 이상 적어주시기 바랍니다." }, no_eng_num : "아이디는 영문 숫자 조합만 가능합니다." })) return false;
	if($("#_id_check").FormInputCheck({ msg : "아이디 중복체크 확인을 해주세요."})) return false;
	
	if($("#yhb_pass").FormInputCheck({ msg : "비밀번호를 4자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 4, max_msg : "영문/숫자 주합으로 4자 이상 적어주시기 바랍니다." }})) return false;
	if($("#yhb_pass_check").FormInputCheck({ msg : "비밀번호 확인을 4자 이상 영문/숫자 조합으로 입력해주세요.", length_limit : { max : 4, max_msg : "영문/숫자 주합으로 4자 이상 적어주시기 바랍니다." }, same : { target : "#yhb_pass", msg : "비밀번호 확인 번호가 맞지 않습니다." } })) return false;
	
	
	if($("#yhb_name").FormInputCheck({ msg : "이름을 입력해주세요."})) return false;
	
	if($("#yhb_nickname").FormInputCheck({ msg : "사용하실 닉네임을 입력해주세요."})) return false;
	if($("#yhb_birth").FormInputCheck({ msg : "생년월일을 입력해주세요."})) return false;
	if($("#yhb_kook_no_f").FormInputCheck({ msg : "주민번호 앞자리를 입력해주세요."})) return false;
	if($("#yhb_kook_no_l").FormInputCheck({ msg : "주민번호 뒷자리를 입력해주세요."})) return false;
	if($("#yhb_email_f").FormInputCheck({ msg : "이메일 정보를 입력해주세요."})) return false;
	if($("#yhb_email_l").val() == "direct") {
		if($("#yhb_email_m").FormInputCheck({ msg : "도메인을 직접 입력해주세요."})) return false;
	}
	if($("#yhb_email_l").FormInputCheck({ msg : "도메인을 선택해주세요."})) return false;
	
	if($("#yhb_home_tel_f").FormInputCheck({ msg : "연락처 정보를 입력해주세요.", only_number : "연락처에는 숫자만 입력해주세요."})) return false;
	if($("#yhb_home_tel_m").FormInputCheck({ msg : "연락처 정보를 입력해주세요.", only_number : "연락처에는 숫자만 입력해주세요."})) return false;
	if($("#yhb_home_tel_l").FormInputCheck({ msg : "연락처 정보를 입력해주세요.", only_number : "연락처에는 숫자만 입력해주세요."})) return false;
	//return false;
	if($("#yhb_handphone").FormInputCheck({ msg : "휴대폰 번호를 입력해주세요."})) return false;
	if($("#yhb_job").FormInputCheck({ msg : "직업 정보를 입력해주세요."})) return false;
	if($("#yhb_hobby").FormInputCheck({ msg : "취미 정보를 입력해주세요."})) return false;
	if($("#yhb_homepage").FormInputCheck({ msg : "홈페이지 주소를 입력해주세요."})) return false;
	if($("#yhb_home_post").FormInputCheck({ msg : "우편번호 검색을 하여 주소를 입력해주세요."})) return false;
	if($("#yhb_home_address").FormInputCheck({ msg : "우편번호 검색을 하여 주소를 입력해주세요."})) return false;
	if($("#yhb_fax").FormInputCheck({ msg : "팩스번호를 입력해주세요."})) return false;
	if($("#yhb_school_name").FormInputCheck({ msg : "학교명을 입력해주세요."})) return false;
	if($("#yhb_school_class").FormInputCheck({ msg : "학과 정보를 입력해주세요."})) return false;
	if($("#yhb_school_year").FormInputCheck({ msg : "학년을 입력해주세요."})) return false;
	if($("#yhb_school_section").FormInputCheck({ msg : "반을 입력해주세요."})) return false;
	if($("#yhb_school_number").FormInputCheck({ msg : "학번을 입력해주세요."})) return false;
	if($("#yhb_school_post").FormInputCheck({ msg : "우편번호 검색을 하여 주소를 입력해주세요."})) return false;
	if($("#yhb_school_address").FormInputCheck({ msg : "우편번호 검색을 하여 주소를 입력해주세요."})) return false;
	if($("#yhb_office_name").FormInputCheck({ msg : "회사명을 입력해주세요."})) return false;
	if($("#yhb_office_owner").FormInputCheck({ msg : "대표자 이름을 입력해주세요."})) return false;
	if($("#yhb_office_no_f").FormInputCheck({ msg : "사업자번호 앞자리 3자리를 입력해주세요."})) return false;
	if($("#yhb_office_no_m").FormInputCheck({ msg : "사업자번호 중간자리 2자리를 입력해주세요."})) return false;
	if($("#yhb_office_no_l").FormInputCheck({ msg : "사업자번호 앞자리 5자리를 입력해주세요."})) return false;
	if($("#yhb_office_project").FormInputCheck({ msg : "종목 정보를 입력해주세요."})) return false;
	if($("#yhb_office_kind").FormInputCheck({ msg : "업태 정보를 입력해주세요."})) return false;
	if($("#yhb_kind_style").FormInputCheck({ msg : "회사유형을 입력해주세요."})) return false;
	if($("#yhb_office_post").FormInputCheck({ msg : "우편번호 검색을 하여 주소를 입력해주세요."})) return false;
	if($("#yhb_office_address").FormInputCheck({ msg : "우편번호 검색을 하여 주소를 입력해주세요."})) return false;
	if($("#yhb_office_charge_name").FormInputCheck({ msg : "담당자 이름을 입력해주세요."})) return false;
	if($("#yhb_office_charge_tel").FormInputCheck({ msg : "담당자 연락처를 입력해주세요."})) return false;
	if($("#yhb_office_level").FormInputCheck({ msg : "부서를 입력해주세요."})) return false;
	if($("#yhb_office_position").FormInputCheck({ msg : "직책을 입력해주세요."})) return false;
	
	return $(f).YhyhFormSubmit("<?=_lh_yhyh_web?>/", function(data) {
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
						<? if($_REQUEST["_id"]) { ?>
						location.href = "<?=$_REQUEST["_id"] ? $_list_link : "/"?>";
						<? } else if($_REQUEST["_admin"]) { ?>
						location.href = "<?=$PHP_SELF?>?_admin=member_manager<?=$query_string?>";
						<? } ?>
					break;
				}
			break;
		}
		//$("#testView").val(data);
	});
	
}
</script>
<div id="yhyh_board_main_body" class="yhyh_register">
	<div class="yhyh_board_register">
	<form class="FormDesignNormal" name="yhyh_regist" id="yhyh_regist" method="POST" onSubmit="return FormSubmitStart(this);">
		<fieldset>
			<legend>회원가입</legend>
			<input type="hidden" name="_module" value="register_proc">
			<input type="hidden" name="_id" value="<?=$_config->yhb_name?>">
			<input type="hidden" name="_m_no" value="<?=$_m_result->yhb_number?>">
			<input type="hidden" name="_return_url" value="<?=$_REQUEST["_return_url"]?>">
			<? if(!$_m_result->yhb_number) { ?>
			<input type="hidden" name="_id_check" id="_id_check" value="" alt="필수">
			<? } ?>
			<table class="yhyh_table_member">
				<? if($_LhDb->Get_Admin()) { ?>
				<tbody>
					<tr><th colspan="4" class="yh_regist_title"><h2>그룹/권한/등급 정보(관리자 모드)</h2></th></tr>
					<? if($_LhDb->Get_Admin() == "s") { ?>
					<tr class="yhyh_input_text">
						<th><span class="field_title">회원그룹</span></th>
						<td colspan="3">
							<select name="yhb_group_no">
								<option value="">그룹 비포함 회원</option>
							<?
							if(!$_m_result->yhb_number) $_m_result->yhb_group_no = ($_REQUEST["_group"]) ? $_REQUEST["_group"] : 101;
							$query = "select yhb_number, yhb_name from yh_group order by yhb_number asc";
							$result = $_LhDb->Query($query);
							while($gg = $_LhDb->Fetch_Object($result)) {
							?>
								<option value="<?=$gg->yhb_number?>"<?=$_m_result->yhb_group_no == $gg->yhb_number ? " selected" : ""?>><?=$gg->yhb_name?> 그룹</option>
							<? } ?>
							</select>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<? } ?>
					<tr class="yhyh_input_text">
						<th><span class="field_title">회원권한/등급</span></th>
						<td colspan="3">
							<? if($_LhDb->Get_Admin() == "s") { ?>
							<span class="field_title">권한</span>
							<select name="yhb_admin">
								<option value="">없음</option>
								<option value="1"<?=$_m_result->yhb_admin == 1 ? " selected" : ""?>>그룹관리자</option>
								<option value="2"<?=$_m_result->yhb_admin == 2 ? " selected" : ""?>>최고관리자</option>
							</select>
							<span class="field_title">등급</span>
							<? } ?>
							<select name="yhb_level">
								<?
								if(!$_m_result->yhb_number) $_m_result->yhb_level = ($_group->yhb_level) ? $_group->yhb_level : 10;
								for($i = 10; $i > 0; $i--) {
								?>
								<option value="<?=$i?>"<?=$_m_result->yhb_level == $i ? " selected" : ""?>>Lv. <?=$i?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<? if($_LhDb->Get_Admin() != "b") { ?>
					<tr class="yhyh_input_text">
						<th><span class="field_title">게시판관리</span></th>
						<td colspan="3">
							<select name="yhb_board_name">
								<option value="">없음</option>
								<option value="1">board1</option>
								<option value="2">board2</option>
							</select>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<? } ?>
				</tbody>
				<? } ?>
				<tbody>
					<tr><th colspan="4" class="yh_regist_title_top"><h2>계정 정보</h2></th></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title"><? if(!$_m_result->yhb_number) { ?><b class="yh_essential">*</b><? } ?>아이디</span></th>
						<td colspan="3">
						<? if($_m_result->yhb_number) { ?>
							<?=$_m_result->yhb_id?>
						<? } else { ?>
							<input type="text" name="yhb_id" id="yhb_id" value="<?=$_m_result->yhb_id?>" size="25" title="아이디" alt="필수">
							<label for="yhb_id" class="placeHolder">4자 이상 영문/숫자 조합</label>
							<input type="button" value="아이디중복체크" onClick="Member_Id_Check();">
						<? } ?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title"><? if(!$_m_result->yhb_number) { ?><b class="yh_essential">*</b><? } ?>비밀번호</span></th>
						<td colspan="3">
						<? if($_m_result->yhb_number) { ?>
							<?=$_LhDb->Text_Replace($_LhDb->Base64("decode", $_m_result->yhb_pass), 2, "", "*")?><span>&nbsp;&nbsp;</span><input type="button" class="text_button_form" value="비밀번호변경">
						<? } else { ?>
							<input type="password" name="yhb_pass" id="yhb_pass" value="" size="20" title="비밀번호" alt="필수">
							<label for="yhb_pass" class="placeHolder">비밀번호</label>
							<span class="field_title"><b class="yh_essential">*</b>비밀번호 확인</span>
							<input type="password" name="yhb_pass_check" id="yhb_pass_check" value="" size="20" title="비밀번호 확인" alt="필수">
							<label for="yhb_pass_check" class="placeHolder">확인 번호 입력</label>
						<? } ?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				<!--/tbody>
				<tbody>
					<tr><th colspan="4" class="yh_regist_title"><h2>기본 정보(필수)</h2></th></tr-->
					<tr class="yhyh_input_text">
						<th><span class="field_title"><b class="yh_essential">*</b>회원이름</span></th>
						<td colspan="3">
							<input type="text" name="yhb_name" id="yhb_name" value="<?=$_m_result->yhb_name?>" size="25" title="회원이름" alt="필수">
							<label for="yhb_name" class="placeHolder">회원이름</label>
							<span class="field_title">닉네임</span>
							<input type="text" name="yhb_nickname" id="yhb_nickname" value="<?=$_m_result->yhb_nickname?>" size="20" title="닉네임">
							<label for="yhb_nickname" class="placeHolder">닉네임</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">생년월일/성별</span></th>
						<td colspan="3">
							<input name="yhb_birth" type="text" class="text_align_center" id="yhb_birth" title="생년월일" value="<?=$_m_result->yhb_birth ? date("Y.m.d", $_m_result->yhb_birth) : ""?>" size="15" maxlength="10">
							<label for="yhb_birth" class="placeHolder">ex) 1970.11.11</label>
							<span>&nbsp;&nbsp;</span>
							<input type="radio" name="yhb_sexy" id="yhb_sexy_m" value="M"<?=strtolower($_m_result->yhb_sexy) == "m" ? " checked" : ""?>><label for="yhb_sexy_m">남성</label>
							<span>&nbsp;</span>
							<input type="radio" name="yhb_sexy" id="yhb_sexy_f" value="F"<?=strtolower($_m_result->yhb_sexy) == "f" ? " checked" : ""?>><label for="yhb_sexy_f">여성</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title"><b class="yh_essential">*</b>이메일</span></th>
						<td colspan="3">
							<input type="text" name="yhb_email_f" id="yhb_email_f" value="<?=$_m_result->yhb_email_f?>" size="20" title="이메일" alt="필수">
							<label for="yhb_email_f" class="placeHolder">ex) id</label>
							<span>@&nbsp;</span>
							<input type="text" name="yhb_email_m" id="yhb_email_m" value="<?=$_m_result->yhb_email_m?>" size="20" title="직접입력" alt="필수">
							<label for="yhb_email_m" class="placeHolder">직접입력</label>
							<select name="yhb_email_l" id="yhb_email_l" onChange="Email_Type_Change(false, 'yhb_email', this);" alt="필수">
								<option value="">도메인선택</option>
							<?
							$_result_count = sizeof($_email_type);
							for($i = 0; $i < $_result_count; $i++) {
							?>
								<option value="<?=$_email_type[$i]?>"<?=$_m_result->yhb_email_l == $_email_type[$i] ? " selected" : ""?>><?=$_email_type[$i]?></option>
							<? } ?>
								<option value="direct"<?=$_m_result->yhb_email_l == "direct" ? " selected" : ""?>>직접입력</option>
							</select>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title"><b class="yh_essential">*</b>연락처</span></th>
						<td colspan="3">
							<select name="yhb_home_tel_f" id="yhb_home_tel_f">
								<option value="">선택</option>
							<?
							$_result_count = sizeof($_tel_type);
							for($i = 0; $i < $_result_count; $i++) {
							?>
								<option value="<?=$_tel_type[$i]?>"<?=$_m_result->yhb_home_tel_f == $_tel_type[$i] ? " selected" : ""?>><?=$_tel_type[$i]?></option>
							<? } ?>
							</select>
							<input name="yhb_home_tel_m" type="text" class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" id="yhb_home_tel_m" title="연락처 앞자리" value="<?=$_m_result->yhb_home_tel_m?>" size="8" maxlength="4" alt="필수">
							<label for="yhb_home_tel_m" class="placeHolder">ex) 0000</label>
							<input name="yhb_home_tel_l" type="text" class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" id="yhb_home_tel_l" title="연락처 뒷자리" value="<?=$_m_result->yhb_home_tel_l?>" size="8" maxlength="4" alt="필수">
							<label for="yhb_home_tel_l" class="placeHolder">ex) 0000</label>
							<span class="field_title">휴대폰</span>
							<select name="yhb_handphone_f" id="yhb_handphone_f">
								<option value="">선택</option>
							<?
							$_result_count = sizeof($_mobile_type);
							for($i = 0; $i < $_result_count; $i++) {
							?>
								<option value="<?=$_mobile_type[$i]?>"<?=$_m_result->yhb_handphone_f == $_mobile_type[$i] ? " selected" : ""?>><?=$_mobile_type[$i]?></option>
							<? } ?>
							</select>
							<input name="yhb_handphone_m" type="text" class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" id="yhb_handphone_m" title="휴대폰 번호 앞자리" value="<?=$_m_result->yhb_handphone_m?>" size="8" maxlength="4" alt="">
							<label for="yhb_handphone_m" class="placeHolder">ex) 0000</label>
							<input name="yhb_handphone_l" type="text" class="text_align_center" onKeyUp="$(this).FormInputNumberCheck(true);" id="yhb_handphone_l" title="휴대폰 번호 뒷자리" value="<?=$_m_result->yhb_handphone_l?>" size="8" maxlength="4" alt="">
							<label for="yhb_handphone_l" class="placeHolder">ex) 0000</label>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">주소</span></th>
						<td colspan="3">
							<table class="yh_address_form">
								<tbody>
									<tr>
										<td>
											<input type="text" name="yhb_home_post" class="text_align_center" id="yhb_home_post" value="<?=$_m_result->yhb_home_post?>" size="12" title="우편번호검색">
											<label for="yhb_home_post" class="placeHolder">ex) 000-000</label>
											<input type="button" value="우편번호검색">
										</td>
									</tr>
									<tr>
										<td>
											<input type="text" name="yhb_home_address" id="yhb_home_address" value="<?=$_m_result->yhb_home_address?>" size="60" title="집 주소">
											<label for="yhb_home_address" class="placeHolder">ex) 서울시 영등포구 문래동</label>
										</td>
									</tr>
									<tr>
										<td>
											<input type="text" name="yhb_home_address_etc" id="yhb_home_address_etc" value="<?=$_m_result->yhb_home_address_etc?>" size="50" title="나머지 주소">
											<label for="yhb_home_address_etc" class="placeHolder">ex) 번지, 층, 호</label>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
			</table>
			<div class="yhyh_write_button">
				<input type="submit" value="<?=$_m_result->yhb_number ? "회원정보 저장" : "회원등록 신청하기"?>">
				<a class="a_button" href="<?=$_back_link ? $_back_link : "javascript:;\" onClick=\"history.back(); return false;\""?>">이전으로</a>
			</div>
			<textarea cols="100" rows="20" id="testView" style="display:none;"></textarea>
		</fieldset>
	</form>
	</div>
</div>