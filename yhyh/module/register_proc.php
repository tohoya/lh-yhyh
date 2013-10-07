<?php

$_POST[yhb_joindate] = time();

// 주민번호 및 사업자 번호
$_POST[yhb_office_no] = ($_POST[yhb_office_no_f] || $_POST[yhb_office_no_m] || $_POST[yhb_office_no_l]) ? $_POST[yhb_office_no_f]."-".$_POST[yhb_office_no_m]."-".$_POST[yhb_office_no_l] : ""; // 사업자 번호 조합
$_POST[yhb_kook_no] = ($_POST[yhb_kook_no_f] || $_POST[yhb_kook_no_l]) ? $_POST[yhb_kook_no_f]."-".$_POST[yhb_kook_no_l] : ""; // 주빈번호 조합
$_POST[yhb_birth] = ($_POST[yhb_birth]) ? mktime(0, 0, 0, $_LhDb->Get_Split($_POST[yhb_birth], 1, "\."), $_LhDb->Get_Split($_POST[yhb_birth], 2, "\."), $_LhDb->Get_Split($_POST[yhb_birth], 0, "\.")) : "";

// 전화번호
$_POST[yhb_home_tel] = ($_POST[yhb_home_tel_f] || $_POST[yhb_home_tel_m] || $_POST[yhb_home_tel_l]) ? $_POST[yhb_home_tel_f]."-".$_POST[yhb_home_tel_m]."-".$_POST[yhb_home_tel_l] : "";
$_POST[yhb_handphone] = ($_POST[yhb_handphonel_f] || $_POST[yhb_handphone_m] || $_POST[yhb_handphone_l]) ? $_POST[yhb_handphonel_f]."-".$_POST[yhb_handphone_m]."-".$_POST[yhb_handphone_l] : "";
$_POST[yhb_school_tel] = ($_POST[yhb_school_tel_f] || $_POST[yhb_school_tel_m] || $_POST[yhb_school_tel_l]) ? $_POST[yhb_school_tel_f]."-".$_POST[yhb_school_tel_m]."-".$_POST[yhb_school_tel_l] : "";
$_POST[yhb_office_tel] = ($_POST[yhb_office_tel_f] || $_POST[yhb_office_tel_m] || $_POST[yhb_office_tel_l]) ? $_POST[yhb_office_tel_f]."-".$_POST[yhb_office_tel_m]."-".$_POST[yhb_office_tel_l] : "";
$_POST[yhb_office_charge_tel] = ($_POST[yhb_office_charge_tel_f] || $_POST[yhb_office_charge_tel_m] || $_POST[yhb_office_charge_tel_l]) ? $_POST[yhb_office_charge_tel_f]."-".$_POST[yhb_office_charge_tel_m]."-".$_POST[yhb_office_charge_tel_l] : "";
$_POST[yhb_fax] = ($_POST[yhb_fax_f] || $_POST[yhb_fax_m] || $_POST[yhb_fax_l]) ? $_POST[yhb_fax_f]."-".$_POST[yhb_fax_m]."-".$_POST[yhb_fax_l] : "";
$_POST[yhb_email] = "";
if($_POST[yhb_email_l] == "direct") {
	if($_POST[yhb_email_f] && $_POST[yhb_email_m]) {
		$_POST[yhb_email] = $_POST[yhb_email_f]."@".$_POST[yhb_email_m];
	}
} else {
	if($_POST[yhb_email_f] && $_POST[yhb_email_l]) {
		$_POST[yhb_email] = $_POST[yhb_email_f]."@".$_POST[yhb_email_l];
	}
}

$_ext = split("/",$_config->yhb_ext); // 사용할 확장자
if($_POST[yhb_email]) $_POST[yhb_email] = isEmail($_POST[yhb_email]); // 이메일 체크
if($_POST[yhb_homepage]) $_POST[yhb_homepage] = isLink($_POST[yhb_homepage]); // 홈페이지 url 체크
$_POST[yhb_ip] = $_SERVER['REMOTE_ADDR'];

// 추가된 파일 정리
$arr = array();
$arr = split(",", $_POST[file_add_LHtmlEditers]);
$count = sizeof($arr);
$update = "board_no = '".$_POST[yhb_number]."', group_no = '".$_POST[yhb_group_no]."'";
for($i = 0; $i < $count; $i++) {
	if(trim($arr[$i])) {
		$where .= $where ? " OR seq = '".trim($arr[$i])."'" : " where seq = '".trim($arr[$i])."'";
	}
}
if($where) {
	$query = "update yh_file set ".$update.$where;
	$_LhDb->Query($query);
}
echo $_POST[yhb_number];
// 삭제된 파일 정리
$arr = array();
$arr = split(",", $_POST[file_delete_LHtmlEditers]);
$count = sizeof($arr);
for($i = 0; $i < $count; $i++) {
	if(trim($arr[$i])) {
		$query = "select s_name from yh_file where seq = '".trim($arr[$i])."'";
		$ff = $_LhDb->Fetch_Object_Query($query);
		$url = _lh_document_root._lh_yhyh_web."/upload/".$ff->s_name;
		if(file_exists($url)) {
			@unlink($url);
		}
		$query = "delete from yh_file where seq = '".trim($arr[$i])."'";
	}
}

if($_POST["_m_no"]) {
	/*
	, yhb_point1 = '".$_POST[yhb_point1]."'
	, yhb_point2 = '".$_POST[yhb_point2]."'
	*/
	$modify =  eregi_replace("\r|\n|\t", "", "yhb_name = '".$_POST[yhb_name]."'
		, yhb_nickname = '".$_POST[yhb_nickname]."'
		, yhb_kook_no = '".$_POST[yhb_kook_no]."'
		, yhb_sexy = '".$_POST[yhb_sexy]."'
		, yhb_birth = '".$_POST[yhb_birth]."'
		, yhb_job = '".$_POST[yhb_job]."'
		, yhb_hobby = '".$_POST[yhb_hobby]."'
		, yhb_homepage = '".$_POST[yhb_homepage]."'
		, yhb_email = '".$_POST[yhb_email]."'
		, yhb_msn = '".$_POST[yhb_msn]."'
		, yhb_email_s = '".$_POST[yhb_email_s]."'
		, yhb_profile = '".$_POST[yhb_profile]."'
		, yhb_online = '".$_POST[yhb_online]."'
		, yhb_board_name = '".$_POST[yhb_board_name]."'
		, yhb_photo = '".$_POST[yhb_photo]."'
		, yhb_home_post = '".$_POST[yhb_home_post]."'
		, yhb_home_address = '".$_POST[yhb_home_address]."'
		, yhb_home_address_etc = '".$_POST[yhb_home_address_etc]."'
		, yhb_home_tel = '".$_POST[yhb_home_tel]."'
		, yhb_handphone = '".$_POST[yhb_handphone]."'
		, yhb_fax = '".$_POST[yhb_fax]."'
		, yhb_add_address = '".$_POST[yhb_add_address]."'
		, yhb_school_name = '".$_POST[yhb_school_name]."'
		, yhb_school_post = '".$_POST[yhb_school_post]."'
		, yhb_school_address = '".$_POST[yhb_school_address]."'
		, yhb_school_address_etc = '".$_POST[yhb_school_address_etc]."'
		, yhb_school_tel = '".$_POST[yhb_school_tel]."'
		, yhb_school_class = '".$_POST[yhb_school_class]."'
		, yhb_school_year = '".$_POST[yhb_school_year]."'
		, yhb_school_section = '".$_POST[yhb_school_section]."'
		, yhb_school_number = '".$_POST[yhb_school_number]."'
		, yhb_office_post = '".$_POST[yhb_office_post]."'
		, yhb_office_address = '".$_POST[yhb_office_address]."'
		, yhb_office_address_etc = '".$_POST[yhb_office_address_etc]."'
		, yhb_office_name = '".$_POST[yhb_office_name]."'
		, yhb_office_level = '".$_POST[yhb_office_level]."'
		, yhb_office_position = '".$_POST[yhb_office_position]."'
		, yhb_office_tel = '".$_POST[yhb_office_tel]."'
		, yhb_office_no = '".$_POST[yhb_office_no]."'
		, yhb_office_owner = '".$_POST[yhb_office_owner]."'
		, yhb_office_charge = '".$_POST[yhb_office_charge]."'
		, yhb_office_charge_tel = '".$_POST[yhb_office_charge_tel]."'
		, yhb_office_project = '".$_POST[yhb_office_project]."'
		, yhb_office_kind = '".$_POST[yhb_office_kind]."'
		, yhb_kind_style = '".$_POST[yhb_kind_style]."'
		, yhb_auto_login = '".$_POST[yhb_auto_login]."'
		, yhb_user_question = '".$_POST[yhb_user_question]."'
		, yhb_user_answer = '".$_POST[yhb_user_answer]."'
		, yhb_open_info = '".$_POST[yhb_open_info]."'
		, yhb_open_email = '".$_POST[yhb_open_email]."'
		, yhb_open_homepage = '".$_POST[yhb_open_homepage]."'
		, yhb_open_msn = '".$_POST[yhb_open_msn]."'
		, yhb_open_profile = '".$_POST[yhb_open_profile]."'
		, yhb_open_home_address = '".$_POST[yhb_open_home_address]."'
		, yhb_open_home_tel = '".$_POST[yhb_open_home_tel]."'
		, yhb_open_office_address = '".$_POST[yhb_open_office_address]."'
		, yhb_open_office_tel = '".$_POST[yhb_open_office_tel]."'
		, yhb_open_handphone = '".$_POST[yhb_open_handphone]."'
		, yhb_open_birth = '".$_POST[yhb_open_birth]."'
		, yhb_open_photo = '".$_POST[yhb_open_photo]."'
		, yhb_use_nickname = '".$_POST[yhb_use_nickname]."'
	");
	if(trim($_POST[yhb_group_no])) $modify .= ", yhb_group_no = '".$_POST[yhb_group_no]."'";
	if(trim($_POST[yhb_level])) $modify .= ", yhb_level = '".$_POST[yhb_level]."'";
	if(trim($_POST[yhb_admin])) $modify .= ", yhb_admin = '".$_POST[yhb_admin]."'";
	
	$query = "update yh_member set ".$modify." where yhb_number = '".$_POST["_m_no"]."'";
	if($_LhDb->Query($query)) {
		echo("<p class=\"complete\" title=\"modify\">회원정보 저장이 완료 되었습니다.</p>");
	} else {
		echo("<p class=\"error\">회원정보 저장에 실패하였습니다.(".$query.")</p>");
	}
	exit();
}
$_POST[yhb_logoutdate] = $_POST[yhb_joindate];
$reg_start_time = mktime(0,0,0, date("m", $_POST[yhb_joindate]), 1, date("Y", $_POST[yhb_joindate])); // 카운티할 회원 시작시간
$reg_end_time = mktime(23,59,59, date("m", $_POST[yhb_joindate]), date("t", $_POST[yhb_joindate]), date("Y", $_POST[yhb_joindate])); // 카운티항 회원 마감시간
if(!$_POST["_group"]) {
	$group_query = "select MAX(yhb_number) as number from yh_group";
	$group_result = $_LhDb->Fetch_Object_Query($group_query);
	$_POST["_group"] = $group_result->number; // 그룹 코드
}
if(!$_POST[yhb_group_no]) $_POST[yhb_group_no] = $_POST["_group"];
$_POST[yhb_number] = TableMax("yh_member", "yhb_number", $_POST[yhb_group_no]."".date("ym", $_POST[yhb_joindate])."001", "yhb_joindate >= '".$reg_start_time."' AND yhb_joindate <= '".$reg_end_time."'"); // 회원 번호

if(!trim($_POST[yhb_level])) $_POST[yhb_level] = $_group->yhb_level; // 레벨 초기값
if(!trim($_POST[yhb_level])) $_POST[yhb_level] = 10; // 레벨 초기값
$_POST[yhb_point1] = 0; // 포인트1 초기값
$_POST[yhb_point2] = 0; // 포인트2 초기값
$_POST[yhb_mysign] = md5($_POST[yhb_joindate] + $_POST[yhb_id] + $_POST[yhb_pass] + $_POST[yhb_name]);

$fields = eregi_replace("\r|\n|\t", "", "yhb_number
, yhb_group_no
, yhb_id
, yhb_pass
, yhb_name
, yhb_mysign
, yhb_level
, yhb_nickname
, yhb_kook_no
, yhb_sexy
, yhb_birth
, yhb_brith
, yhb_job
, yhb_hobby
, yhb_homepage
, yhb_email
, yhb_msn
, yhb_email_s
, yhb_profile
, yhb_online
, yhb_joindate
, yhb_logoutdate
, yhb_point1
, yhb_point2
, yhb_board_name
, yhb_photo
, yhb_home_post
, yhb_home_address
, yhb_home_address_etc
, yhb_home_tel
, yhb_handphone
, yhb_fax
, yhb_add_address
, yhb_school_name
, yhb_school_post
, yhb_school_address
, yhb_school_address_etc
, yhb_school_tel
, yhb_school_class
, yhb_school_year
, yhb_school_section
, yhb_school_number
, yhb_office_post
, yhb_office_address
, yhb_office_address_etc
, yhb_office_name
, yhb_office_level
, yhb_office_position
, yhb_office_tel
, yhb_office_no
, yhb_office_owner
, yhb_office_charge
, yhb_office_charge_tel
, yhb_office_project
, yhb_office_kind
, yhb_kind_style
, yhb_admin
, yhb_auto_login
, yhb_user_question
, yhb_user_answer
, yhb_open_info
, yhb_open_email
, yhb_open_homepage
, yhb_open_msn
, yhb_open_profile
, yhb_open_home_address
, yhb_open_home_tel
, yhb_open_office_address
, yhb_open_office_tel
, yhb_open_handphone
, yhb_open_birth
, yhb_open_photo
, yhb_use_nickname
, old_check
");

$values = eregi_replace("\r|\n|\t", "", "'".$_POST[yhb_number]."'
 , '".$_POST[yhb_group_no]."'
 , '".$_POST[yhb_id]."'
 , '".$_LhDb->Base64("encode", $_POST[yhb_pass])."'
 , '".$_POST[yhb_name]."'
 , '".$_POST[yhb_mysign]."'
 , '".$_POST[yhb_level]."'
 , '".$_POST[yhb_nickname]."'
 , '".$_POST[yhb_kook_no]."'
 , '".$_POST[yhb_sexy]."'
 , '".$_POST[yhb_birth]."'
 , '".$_POST[yhb_brith]."'
 , '".$_POST[yhb_job]."'
 , '".$_POST[yhb_hobby]."'
 , '".$_POST[yhb_homepage]."'
 , '".$_POST[yhb_email]."'
 , '".$_POST[yhb_msn]."'
 , '".$_POST[yhb_email_s]."'
 , '".$_POST[yhb_profile]."'
 , '".$_POST[yhb_online]."'
 , '".$_POST[yhb_joindate]."'
 , '".$_POST[yhb_logoutdate]."'
 , '".$_POST[yhb_point1]."'
 , '".$_POST[yhb_point2]."'
 , '".$_POST[yhb_board_name]."'
 , '".$_POST[yhb_photo]."'
 , '".$_POST[yhb_home_post]."'
 , '".$_POST[yhb_home_address]."'
 , '".$_POST[yhb_home_address_etc]."'
 , '".$_POST[yhb_home_tel]."'
 , '".$_POST[yhb_handphone]."'
 , '".$_POST[yhb_fax]."'
 , '".$_POST[yhb_add_address]."'
 , '".$_POST[yhb_school_name]."'
 , '".$_POST[yhb_school_post]."'
 , '".$_POST[yhb_school_address]."'
 , '".$_POST[yhb_school_address_etc]."'
 , '".$_POST[yhb_school_tel]."'
 , '".$_POST[yhb_school_class]."'
 , '".$_POST[yhb_school_year]."'
 , '".$_POST[yhb_school_section]."'
 , '".$_POST[yhb_school_number]."'
 , '".$_POST[yhb_office_post]."'
 , '".$_POST[yhb_office_address]."'
 , '".$_POST[yhb_office_address_etc]."'
 , '".$_POST[yhb_office_name]."'
 , '".$_POST[yhb_office_level]."'
 , '".$_POST[yhb_office_position]."'
 , '".$_POST[yhb_office_tel]."'
 , '".$_POST[yhb_office_no]."'
 , '".$_POST[yhb_office_owner]."'
 , '".$_POST[yhb_office_charge]."'
 , '".$_POST[yhb_office_charge_tel]."'
 , '".$_POST[yhb_office_project]."'
 , '".$_POST[yhb_office_kind]."'
 , '".$_POST[yhb_kind_style]."'
 , '".$_POST[yhb_admin]."'
 , '".$_POST[yhb_auto_login]."'
 , '".$_POST[yhb_user_question]."'
 , '".$_POST[yhb_user_answer]."'
 , '".$_POST[yhb_open_info]."'
 , '".$_POST[yhb_open_email]."'
 , '".$_POST[yhb_open_homepage]."'
 , '".$_POST[yhb_open_msn]."'
 , '".$_POST[yhb_open_profile]."'
 , '".$_POST[yhb_open_home_address]."'
 , '".$_POST[yhb_open_home_tel]."'
 , '".$_POST[yhb_open_office_address]."'
 , '".$_POST[yhb_open_office_tel]."'
 , '".$_POST[yhb_open_handphone]."'
 , '".$_POST[yhb_open_birth]."'
 , '".$_POST[yhb_open_photo]."'
 , '".$_POST[yhb_use_nickname]."'
 , '".$_SERVER['HTTP_HOST']."'
");

$query = "insert into yh_member(".$fields.") values(".$values.")";

//echo $query;
if($_LhDb->Query($query)) {

	// 메일이 있을 경우 발송
	/*
	if(isEmail($_POST[yhb_email])) {
		@send_mail($_POST[yhb_email], "join");
	}
	*/
	$load_page = "<p class=\"complete\" title=\"register\">회원 등록이 완료 되었습니다.</p>";
	echo($load_page);
} else {
	echo("<p class=\"error\">회원 등록 도중 실패하였습니다.(".$query.")</p>");
}

?>