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
});
</script>
<div id="yhyh_board_main_body" class="yhyh_register_view">
	<div class="FormDesignNormal">
			<table class="yhyh_table_member">
				<tbody>
					<tr><th colspan="4" class="yh_regist_title_top"><h2 class="view_style">계정 정보</h2></th></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">아이디</span></th>
						<td colspan="3">
							<?=$_member->yhb_id?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">비밀번호</span></th>
						<td colspan="3">
							<?=$_LhDb->Text_Replace($_LhDb->Base64("decode", $_member->yhb_pass), 2, "", "*")?><span>&nbsp;&nbsp;</span><input type="button" class="text_button_form" value="비밀번호변경">
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">회원그룹</span></th>
						<td colspan="3">
							<?=$_member->yhb_group_name?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">회원권한/등급</span></th>
						<td colspan="3">
							<span class="field_title">권한 :</span>
							<?=$_member->yhb_admin ? ($_member->yhb_admin == "2") ? "최고관리자" : "그룹관리자" : ""?>
							<span class="field_title">, 등급 :</span>
							Lv. <?=$_member->yhb_level?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">게시판관리</span></th>
						<td colspan="3">
							<?=$_member->yhb_board_name?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">회원이름</span></th>
						<td colspan="3">
							<?=$_member->yhb_name?><?=$_member->yhb_nickname ? "(닉네임 : ".$_member->yhb_nickname.")" : ""?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">생년월일/성별</span></th>
						<td colspan="3">
							<?=$_member->yhb_birth?>
							<span>&nbsp;&nbsp;</span>
							<span class="yhyh_sex_type_<?=strtolower($_member->yhb_sexy) == "m" ? "m" : ""?>">&nbsp;남성</span>
							<span class="yhyh_sex_type_<?=strtolower($_member->yhb_sexy) == "f" ? "f" : ""?>">&nbsp;여성</span>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">주민번호</span></th>
						<td colspan="3">
							<?=$_member->yhb_kook_no_f ? $_member->yhb_kook_no_f : ""?><?=$_member->yhb_kook_no_l ? "-".$_LhDb->Text_Replace($_member->yhb_kook_no_l, 0, "", "*") : ""?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">이메일</span></th>
						<td colspan="3">
							<?=$_member->yhb_email ? $_member->yhb_email : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">연락처</span></th>
						<td colspan="3">
							<?=$_member->yhb_home_tel ? $_member->yhb_home_tel : "정보없음"?>
							<span class="field_title">, 휴대폰 :</span>
							<?=$_member->yhb_handphone ? $_member->yhb_handphone : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">직업</span></th>
						<td colspan="3">
							<?=$_member->yhb_job ? $_member->yhb_job : "정보없음"?>
							<span class="field_title">, 취미 :</span>
							<?=$_member->yhb_hobby ? $_member->yhb_hobby : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">홈페이지</span></th>
						<td colspan="3">
							<?=$_member->yhb_homepage ? $_member->yhb_homepage : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">주소</span></th>
						<td colspan="3">
							<?=$_member->yhb_home_post?>
							<?=$_member->yhb_home_address?>
							<?=$_member->yhb_home_address_etc?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">팩스번호</span></th>
						<td colspan="3">
							<?=$_member->yhb_fax ? $_member->yhb_fax : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
				<tbody>
					<tr><th colspan="4" class="yh_regist_title"><h2 class="view_style">학교 정보</h2></th></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">학교명</span></th>
						<td colspan="3">
							<?=$_member->yhb_school_name ? $_member->yhb_school_name : "정보없음"?>
							<span class="field_title">, 연락처 :</span>
							<?=$_member->yhb_school_tel ? $_member->yhb_school_tel : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">학과/년/반/학번</span></th>
						<td colspan="3">
							<span class="field_title">학과 :</span>
							<?=$_member->yhb_school_class ? $_member->yhb_school_class : "정보없음"?>
							<span class="field_title">, 학년 :</span>
							<?=$_member->yhb_school_year ? $_member->yhb_school_year : "정보없음"?>
							<span class="field_title">, 반 :</span>
							<?=$_member->yhb_school_section ? $_member->yhb_school_section : "정보없음"?>
							<span class="field_title">, 학번 :</span>
							<?=$_member->yhb_school_number ? $_member->yhb_school_number : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">주소</span></th>
						<td colspan="3">
							<?=$_member->yhb_school_post?>
							<?=$_member->yhb_school_address?>
							<?=$_member->yhb_school_address_etc?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
				<tbody>
					<tr><th colspan="4" class="yh_regist_title"><h2 class="view_style">직장 정보</h2></th></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">회사명</span></th>
						<td colspan="3">
							<?=$_member->yhb_office_name ? $_member->yhb_office_name : "정보없음"?>
							<span class="field_title">, 대표자명 :</span>
							<?=$_member->yhb_office_owner ? $_member->yhb_office_owner : "정보없음"?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">사업자번호</span></th>
						<td colspan="3">
							<?=$_member->yhb_office_no?>
							<span class="field_title">, 전화번호 :</span>
							<?=$_member->yhb_office_tel?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">종목</span></th>
						<td colspan="3">
							<?=$_member->yhb_office_project?>
							<span class="field_title">, 업태 :</span>
							<?=$_member->yhb_office_kind?>
							<span class="field_title">, 회사유형 :</span>
							<?=$_member->yhb_kind_style?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">주소</span></th>
						<td colspan="3">
							<?=$_member->yhb_office_post?>
							<?=$_member->yhb_office_address?>
							<?=$_member->yhb_office_address_etc?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
				<tbody>
					<tr><th colspan="4" class="yh_regist_title"><h2 class="view_style">담당자 정보</h2></th></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">담당자 이름</span></th>
						<td colspan="3">
							<?=$_member->yhb_office_charge?>
							<span class="field_title">, 담당자 연락처 :</span>
							<?=$_member->yhb_office_charge_tel?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
					<tr class="yhyh_input_text">
						<th><span class="field_title">부서</span></th>
						<td colspan="3">
							<?=$_member->yhb_office_level?>
							<span class="field_title">, 직책 :</span>
							<?=$_member->yhb_office_position?>
						</td>
					</tr>
					<tr><td colspan="4" class="yh_register_width_g"></td></tr>
				</tbody>
			</table>
	</div>
</div>