<?php

$_POST[yhb_reg_date] = time();

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

$_POST[yhb_point_limit] = "";
for($i = 1; $i < 10; $i++) {
	$_POST[yhb_point_limit] .= ($i > 1) ? "*".$_POST["yhb_point_limit_".$i] : $_POST["yhb_point_limit_".$i];
}

$_ext = split(",",$_config->yhb_ext); // 사용할 확장자

$_POST[yhb_email] = isEmail($_POST[yhb_email]); // 이메일 체크


if($_POST["_group"]) {
	/*
	, yhb_point1 = '".$_POST[yhb_point1]."'
	, yhb_point2 = '".$_POST[yhb_point2]."'
	*/
	$modify =  eregi_replace("\r|\n|\t", "", "yhb_name = '".$_POST[yhb_name]."'
		, yhb_skin = '".$_POST[yhb_skin]."'
		, yhb_language = '".$_POST[yhb_language]."'
		, yhb_open_group = '".$_POST[yhb_open_group]."'
		, yhb_join_icon = '".$_POST[yhb_join_icon]."'
		, yhb_icon = '".$_POST[yhb_icon]."'
		, yhb_join_return_url = '".$_POST[yhb_join_return_url]."'
		, yhb_level = '".$_POST[yhb_level]."'
		, yhb_header_url = '".$_POST[yhb_header_url]."'
		, yhb_header = '".$_POST[yhb_header]."'
		, yhb_footer_url = '".$_POST[yhb_footer_url]."'
		, yhb_footer = '".$_POST[yhb_footer]."'
		, yhb_join_point = '".$_POST[yhb_join_point]."'
		, yhb_login_point = '".$_POST[yhb_login_point]."'
		, yhb_board_point = '".$_POST[yhb_board_point]."'
		, yhb_memo_point = '".$_POST[yhb_memo_point]."'
		, yhb_point_limit = '".$_POST[yhb_point_limit]."'
		, yhb_remailaddress = '".$_POST[yhb_remailaddress]."'
		, yhb_remail_style = '".$_POST[yhb_remail_style]."'
		, yhb_board_remail = '".$_POST[yhb_board_remail]."'
		, yhb_use_header_html = '".$_POST[yhb_use_header_html]."'
		, yhb_use_footer_html = '".$_POST[yhb_use_footer_html]."'
		, yhb_use_nomail = '".$_POST[yhb_use_nomail]."'
		, yhb_use_agreement = '".$_POST[yhb_use_agreement]."'
		, yhb_use_join = '".$_POST[yhb_use_join]."'
		, yhb_use_nickname = '".$_POST[yhb_use_nickname]."'
		, yhb_use_kook_no = '".$_POST[yhb_use_kook_no]."'
		, yhb_use_sexy = '".$_POST[yhb_use_sexy]."'
		, yhb_use_birth = '".$_POST[yhb_use_birth]."'
		, yhb_use_job = '".$_POST[yhb_use_job]."'
		, yhb_use_hobby = '".$_POST[yhb_use_hobby]."'
		, yhb_use_homepage = '".$_POST[yhb_use_homepage]."'
		, yhb_use_email = '".$_POST[yhb_use_email]."'
		, yhb_use_msn = '".$_POST[yhb_use_msn]."'
		, yhb_use_email_s = '".$_POST[yhb_use_email_s]."'
		, yhb_use_profile = '".$_POST[yhb_use_profile]."'
		, yhb_use_add = '".$_POST[yhb_use_add]."'
		, yhb_use_photo = '".$_POST[yhb_use_photo]."'
		, yhb_use_home_address = '".$_POST[yhb_use_home_address]."'
		, yhb_use_home_tel = '".$_POST[yhb_use_home_tel]."'
		, yhb_use_handphone = '".$_POST[yhb_use_handphone]."'
		, yhb_use_fax = '".$_POST[yhb_use_fax]."'
		, yhb_use_add_address = '".$_POST[yhb_use_add_address]."'
		, yhb_use_school = '".$_POST[yhb_use_school]."'
		, yhb_use_school_name = '".$_POST[yhb_use_school_name]."'
		, yhb_use_school_address = '".$_POST[yhb_use_school_address]."'
		, yhb_use_school_tel = '".$_POST[yhb_use_school_tel]."'
		, yhb_use_school_class = '".$_POST[yhb_use_school_class]."'
		, yhb_use_school_year = '".$_POST[yhb_use_school_year]."'
		, yhb_use_school_section = '".$_POST[yhb_use_school_section]."'
		, yhb_use_school_number = '".$_POST[yhb_use_school_number]."'
		, yhb_use_office = '".$_POST[yhb_use_office]."'
		, yhb_use_office_address = '".$_POST[yhb_use_office_address]."'
		, yhb_use_office_name = '".$_POST[yhb_use_office_name]."'
		, yhb_use_office_level = '".$_POST[yhb_use_office_level]."'
		, yhb_use_office_position = '".$_POST[yhb_use_office_position]."'
		, yhb_use_office_tel = '".$_POST[yhb_use_office_tel]."'
		, yhb_use_office_no = '".$_POST[yhb_use_office_no]."'
		, yhb_use_office_owner = '".$_POST[yhb_use_office_owner]."'
		, yhb_use_office_charge = '".$_POST[yhb_use_office_charge]."'
		, yhb_use_office_charge_tel = '".$_POST[yhb_use_office_charge_tel]."'
		, yhb_use_kind_style = '".$_POST[yhb_use_kind_style]."'
		, yhb_use_header_url = '".$_POST[yhb_use_header_url]."'
		, yhb_use_header = '".$_POST[yhb_use_header]."'
		, yhb_use_footer_url = '".$_POST[yhb_use_footer_url]."'
		, yhb_use_footer = '".$_POST[yhb_use_footer]."'
	");
	
	$query = "update yh_group set ".$modify." where yhb_number = '".$_POST["_group"]."'";
	if($_LhDb->Query($query)) {
		echo("<p class=\"complete\" title=\"modify\">그룹 정보 저장이 완료 되었습니다.</p>");
	} else {
		echo("<p class=\"error\">그룹 정보 저장에 실패하였습니다.(".$query.")</p>");
	}
	exit();
}

$_POST[yhb_number] = TableMax("yh_group", "yhb_number", "101", "yhb_number != '' order by yhb_number asc"); // 그룹 코드

$fields = eregi_replace("\r|\n|\t", "", "yhb_number
, yhb_name
, yhb_skin
, yhb_language
, yhb_open_group
, yhb_join_icon
, yhb_icon
, yhb_join_return_url
, yhb_level
, yhb_header_url
, yhb_header
, yhb_footer_url
, yhb_footer
, yhb_join_point
, yhb_login_point
, yhb_board_point
, yhb_memo_point
, yhb_point_limit
, yhb_remailaddress
, yhb_remail_style
, yhb_board_remail
, yhb_use_header_html
, yhb_use_footer_html
, yhb_use_nomail
, yhb_use_agreement
, yhb_use_join
, yhb_reg_date
, yhb_use_nickname
, yhb_use_kook_no
, yhb_use_sexy
, yhb_use_birth
, yhb_use_job
, yhb_use_hobby
, yhb_use_homepage
, yhb_use_email
, yhb_use_msn
, yhb_use_email_s
, yhb_use_profile
, yhb_use_add
, yhb_use_photo
, yhb_use_home_address
, yhb_use_home_tel
, yhb_use_handphone
, yhb_use_fax
, yhb_use_add_address
, yhb_use_school
, yhb_use_school_name
, yhb_use_school_address
, yhb_use_school_tel
, yhb_use_school_class
, yhb_use_school_year
, yhb_use_school_section
, yhb_use_school_number
, yhb_use_office
, yhb_use_office_address
, yhb_use_office_name
, yhb_use_office_level
, yhb_use_office_position
, yhb_use_office_tel
, yhb_use_office_no
, yhb_use_office_owner
, yhb_use_office_charge
, yhb_use_office_charge_tel
, yhb_use_kind_style
, yhb_use_header_url
, yhb_use_header
, yhb_use_footer_url
, yhb_use_footer
");

$values = eregi_replace("\r|\n|\t", "", "'".$_POST[yhb_number]."'
, '".$_POST[yhb_name]."'
, '".$_POST[yhb_skin]."'
, '".$_POST[yhb_language]."'
, '".$_POST[yhb_open_group]."'
, '".$_POST[yhb_join_icon]."'
, '".$_POST[yhb_icon]."'
, '".$_POST[yhb_join_return_url]."'
, '".$_POST[yhb_level]."'
, '".$_POST[yhb_header_url]."'
, '".$_POST[yhb_header]."'
, '".$_POST[yhb_footer_url]."'
, '".$_POST[yhb_footer]."'
, '".$_POST[yhb_join_point]."'
, '".$_POST[yhb_login_point]."'
, '".$_POST[yhb_board_point]."'
, '".$_POST[yhb_memo_point]."'
, '".$_POST[yhb_point_limit]."'
, '".$_POST[yhb_remailaddress]."'
, '".$_POST[yhb_remail_style]."'
, '".$_POST[yhb_board_remail]."'
, '".$_POST[yhb_use_header_html]."'
, '".$_POST[yhb_use_footer_html]."'
, '".$_POST[yhb_use_nomail]."'
, '".$_POST[yhb_use_agreement]."'
, '".$_POST[yhb_use_join]."'
, '".$_POST[yhb_reg_date]."'
, '".$_POST[yhb_use_nickname]."'
, '".$_POST[yhb_use_kook_no]."'
, '".$_POST[yhb_use_sexy]."'
, '".$_POST[yhb_use_birth]."'
, '".$_POST[yhb_use_job]."'
, '".$_POST[yhb_use_hobby]."'
, '".$_POST[yhb_use_homepage]."'
, '".$_POST[yhb_use_email]."'
, '".$_POST[yhb_use_msn]."'
, '".$_POST[yhb_use_email_s]."'
, '".$_POST[yhb_use_profile]."'
, '".$_POST[yhb_use_add]."'
, '".$_POST[yhb_use_photo]."'
, '".$_POST[yhb_use_home_address]."'
, '".$_POST[yhb_use_home_tel]."'
, '".$_POST[yhb_use_handphone]."'
, '".$_POST[yhb_use_fax]."'
, '".$_POST[yhb_use_add_address]."'
, '".$_POST[yhb_use_school]."'
, '".$_POST[yhb_use_school_name]."'
, '".$_POST[yhb_use_school_address]."'
, '".$_POST[yhb_use_school_tel]."'
, '".$_POST[yhb_use_school_class]."'
, '".$_POST[yhb_use_school_year]."'
, '".$_POST[yhb_use_school_section]."'
, '".$_POST[yhb_use_school_number]."'
, '".$_POST[yhb_use_office]."'
, '".$_POST[yhb_use_office_address]."'
, '".$_POST[yhb_use_office_name]."'
, '".$_POST[yhb_use_office_level]."'
, '".$_POST[yhb_use_office_position]."'
, '".$_POST[yhb_use_office_tel]."'
, '".$_POST[yhb_use_office_no]."'
, '".$_POST[yhb_use_office_owner]."'
, '".$_POST[yhb_use_office_charge]."'
, '".$_POST[yhb_use_office_charge_tel]."'
, '".$_POST[yhb_use_kind_style]."'
, '".$_POST[yhb_use_header_url]."'
, '".$_POST[yhb_use_header]."'
, '".$_POST[yhb_use_footer_url]."'
, '".$_POST[yhb_use_footer]."'
");

$query = "insert into yh_group(".$fields.") values(".$values.")";

//echo $query;
if($_LhDb->Query($query)) {
	$load_page = "<p class=\"complete\" title=\"register\">그룹 등록이 완료 되었습니다.</p>";
	echo($load_page);
} else {
	echo("<p class=\"error\">그룹 등록 도중 실패하였습니다.(".$query.")</p>");
}

?>