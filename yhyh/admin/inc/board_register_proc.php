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

$_ext = split(",",$_config->yhb_ext); // 사용할 확장자

$_POST[yhb_email] = isEmail($_POST[yhb_email]); // 이메일 체크


if($_POST["_id"]) {
	$query = "select * from yh_config_board where yhb_name = '".$_POST["_id"]."'";
	$_c_result = $_LhDb->Fetch_Object_Query($query);
	
	if(!$_POST[yhb_body_top])				$_POST[yhb_body_top]				= $_c_result->yhb_body_top;
	if(!$_POST[yhb_body_left])				$_POST[yhb_body_left]				= $_c_result->yhb_body_left;
	if(!$_POST[yhb_table_width])			$_POST[yhb_table_width]				= $_c_result->yhb_table_width;
	if(!$_POST[yhb_table_cell_width])		$_POST[yhb_table_cell_width]		= $_c_result->yhb_table_cell_width;
	if(!$_POST[yhb_subject_limit])			$_POST[yhb_subject_limit]			= $_c_result->yhb_subject_limit;
	if(!$_POST[yhb_filerows])				$_POST[yhb_filerows]				= $_c_result->yhb_filerows;
	if(!$_POST[yhb_iboard_width])			$_POST[yhb_iboard_width]			= $_c_result->yhb_iboard_width;
	if(!$_POST[yhb_iboard_subject_limit])	$_POST[yhb_iboard_subject_limit]	= $_c_result->yhb_iboard_subject_limit;
	if(!$_POST[yhb_iboard_rows])			$_POST[yhb_iboard_rows]				= $_c_result->yhb_iboard_rows;
	if(!$_POST[yhb_iboard_page])			$_POST[yhb_iboard_page]				= $_c_result->yhb_iboard_page;
	if(!$_POST[yhb_iboard_x])				$_POST[yhb_iboard_x]				= $_c_result->yhb_iboard_x;
	if(!$_POST[yhb_iboard_y])				$_POST[yhb_iboard_y]				= $_c_result->yhb_iboard_y;
	if(!$_POST[yhb_board_point])			$_POST[yhb_board_point]				= $_c_result->yhb_board_point;
	if(!$_POST[yhb_memo_point])				$_POST[yhb_memo_point]				= $_c_result->yhb_memo_point;
	if(!$_POST[yhb_photo_wlimit])			$_POST[yhb_photo_wlimit]			= $_c_result->yhb_photo_wlimit;
	if(!$_POST[yhb_grant_list])				$_POST[yhb_grant_list]				= $_c_result->yhb_grant_list;
	if(!$_POST[yhb_grant_write])			$_POST[yhb_grant_write]				= $_c_result->yhb_grant_write;
	if(!$_POST[yhb_grant_view])				$_POST[yhb_grant_view]				= $_c_result->yhb_grant_view;
	if(!$_POST[yhb_grant_memo])				$_POST[yhb_grant_memo]				= $_c_result->yhb_grant_memo;
	if(!$_POST[yhb_grant_reply])			$_POST[yhb_grant_reply]				= $_c_result->yhb_grant_reply;
	if(!$_POST[yhb_grant_notice])			$_POST[yhb_grant_notice]			= $_c_result->yhb_grant_notice;
	if(!$_POST[yhb_grant_delete])			$_POST[yhb_grant_delete]			= $_c_result->yhb_grant_delete;
	if(!$_POST[yhb_grant_secret])			$_POST[yhb_grant_secret]			= $_c_result->yhb_grant_secret;
	
	/*
		, yhb_point1 = '".$_POST[yhb_point1]."'
		, yhb_point2 = '".$_POST[yhb_point2]."'
		, yhb_style = '".$_POST[yhb_style]."'
		, yhb_img_title = '".$_POST[yhb_img_title]."'
		, yhb_img_back = '".$_POST[yhb_img_back]."'
		, yhb_img_title_align = '".$_POST[yhb_img_title_align]."'
		, yhb_img_back_align = '".$_POST[yhb_img_back_align]."'
		, yhb_img_back_align_x = '".$_POST[yhb_img_back_align_x]."'
		, yhb_img_back_align_y = '".$_POST[yhb_img_back_align_y]."'
		, yhb_body_top = '".$_POST[yhb_body_top]."'
		, yhb_body_left = '".$_POST[yhb_body_left]."'
		, yhb_table_align = '".$_POST[yhb_table_align]."'
		, yhb_table_width = '".$_POST[yhb_table_width]."'
		, yhb_table_cell_width = '".$_POST[yhb_table_cell_width]."'
		, yhb_filerows = '".$_POST[yhb_filerows]."'
		, yhb_board_sid = '".$_POST[yhb_board_sid]."'
		, yhb_file_top = '".$_POST[yhb_file_top]."'
		, yhb_file_bottom = '".$_POST[yhb_file_bottom]."'
		, yhb_header_url = '".$_POST[yhb_header_url]."'
		, yhb_footer_url = '".$_POST[yhb_footer_url]."'
		, yhb_header = '".$_POST[yhb_header]."'
		, yhb_footer = '".$_POST[yhb_footer]."'
		, yhb_text_write = '".$_POST[yhb_text_write]."'
		, yhb_text_reply = '".$_POST[yhb_text_reply]."'
		, yhb_text_mail = '".$_POST[yhb_text_mail]."'
		, yhb_text_write_bt = '".$_POST[yhb_text_write_bt]."'
		, yhb_text_reply_bt = '".$_POST[yhb_text_reply_bt]."'
		, yhb_text_mail_bt = '".$_POST[yhb_text_mail_bt]."'
		, yhb_color_body = '".$_POST[yhb_color_body]."'
		, yhb_color_table = '".$_POST[yhb_color_table]."'
		, yhb_color_cell = '".$_POST[yhb_color_cell]."'
		, yhb_iboard_skin = '".$_POST[yhb_iboard_skin]."'
		, yhb_iboard_color_text = '".$_POST[yhb_iboard_color_text]."'
		, yhb_iboard_color_link = '".$_POST[yhb_iboard_color_link]."'
		, yhb_iboard_color_content = '".$_POST[yhb_iboard_color_content]."'
		, yhb_iboard_align = '".$_POST[yhb_iboard_align]."'
		, yhb_iboard_width = '".$_POST[yhb_iboard_width]."'
		, yhb_iboard_subject_limit = '".$_POST[yhb_iboard_subject_limit]."'
		, yhb_iboard_rows = '".$_POST[yhb_iboard_rows]."'
		, yhb_iboard_page = '".$_POST[yhb_iboard_page]."'
		, yhb_iboard_img_back = '".$_POST[yhb_iboard_img_back]."'
		, yhb_iboard_color_body = '".$_POST[yhb_iboard_color_body]."'
		, yhb_iboard_x = '".$_POST[yhb_iboard_x]."'
		, yhb_iboard_y = '".$_POST[yhb_iboard_y]."'
		, yhb_iboard_target = '".$_POST[yhb_iboard_target]."'
		, yhb_style_css = '".$_POST[yhb_style_css]."'
		, yhb_script = '".$_POST[yhb_script]."'
		, yhb_board_point = '".$_POST[yhb_board_point]."'
		, yhb_memo_point = '".$_POST[yhb_memo_point]."'
		, yhb_remailaddress = '".$_POST[yhb_remailaddress]."'
		, yhb_remail_style = '".$_POST[yhb_remail_style]."'
		, yhb_photo_wlimit = '".$_POST[yhb_photo_wlimit]."'
		, yhb_use_nomail = '".$_POST[yhb_use_nomail]."'
		, yhb_use_header_html = '".$_POST[yhb_use_header_html]."'
		, yhb_use_footer_html = '".$_POST[yhb_use_footer_html]."'
		, yhb_use_text_write_auto = '".$_POST[yhb_use_text_write_auto]."'
		, yhb_use_text_write_html = '".$_POST[yhb_use_text_write_html]."'
		, yhb_use_text_write_tp = '".$_POST[yhb_use_text_write_tp]."'
		, yhb_use_text_write_bt = '".$_POST[yhb_use_text_write_bt]."'
		, yhb_use_text_reply_auto = '".$_POST[yhb_use_text_reply_auto]."'
		, yhb_use_text_reply_html = '".$_POST[yhb_use_text_reply_html]."'
		, yhb_use_text_reply_tp = '".$_POST[yhb_use_text_reply_tp]."'
		, yhb_use_text_reply_bt = '".$_POST[yhb_use_text_reply_bt]."'
		, yhb_use_text_mail_auto = '".$_POST[yhb_use_text_mail_auto]."'
		, yhb_use_text_mail_html = '".$_POST[yhb_use_text_mail_html]."'
		, yhb_use_text_mail_tp = '".$_POST[yhb_use_text_mail_tp]."'
		, yhb_use_text_mail_bt = '".$_POST[yhb_use_text_mail_bt]."'
		, yhb_use_img_title = '".$_POST[yhb_use_img_title]."'
		, yhb_use_img_back = '".$_POST[yhb_use_img_back]."'
		, yhb_use_alpha = '".$_POST[yhb_use_alpha]."'
		, yhb_use_download = '".$_POST[yhb_use_download]."'
		, yhb_use_remail = '".$_POST[yhb_use_remail]."'
		, yhb_use_notice_admin = '".$_POST[yhb_use_notice_admin]."'
		, yhb_use_login = '".$_POST[yhb_use_login]."'
		, yhb_use_ct_list = '".$_POST[yhb_use_ct_list]."'
		, yhb_use_reply_list = '".$_POST[yhb_use_reply_list]."'
		, yhb_use_homepage = '".$_POST[yhb_use_homepage]."'
		, yhb_use_email = '".$_POST[yhb_use_email]."'
		, yhb_use_link1 = '".$_POST[yhb_use_link1]."'
		, yhb_use_link2 = '".$_POST[yhb_use_link2]."'
		, yhb_use_iboard_img_back = '".$_POST[yhb_use_iboard_img_back]."'
		, yhb_use_iboard_alpha = '".$_POST[yhb_use_iboard_alpha]."'
		, yhb_use_iboard_icon = '".$_POST[yhb_use_iboard_icon]."'
		, yhb_use_iboard_page = '".$_POST[yhb_use_iboard_page]."'
		, yhb_use_iboard_line = '".$_POST[yhb_use_iboard_line]."'
		, yhb_use_pds = '".$_POST[yhb_use_pds]."'
		, yhb_use_cld = '".$_POST[yhb_use_cld]."'
		, yhb_use_file1 = '".$_POST[yhb_use_file1]."'
		, yhb_use_file2 = '".$_POST[yhb_use_file2]."'
	*/
	$modify =  eregi_replace("\r|\n|\t", "", "yhb_group_no = '".$_POST[yhb_group_no]."'
		, yhb_title_name = '".$_POST[yhb_title_name]."'
		, yhb_skin = '".$_POST[yhb_skin]."'
		, yhb_language = '".$_POST[yhb_language]."'
		, yhb_start_url = '".$_POST[yhb_start_url]."'
		, yhb_subject_limit = '".$_POST[yhb_subject_limit]."'
		, yhb_rows = '".$_POST[yhb_rows]."'
		, yhb_page = '".$_POST[yhb_page]."'
		, yhb_time = '".$_POST[yhb_time]."'
		, yhb_ext = '".$_POST[yhb_ext]."'
		, yhb_upload_size = '".$_POST[yhb_upload_size]."'
		, yhb_memo_title = '".$_POST[yhb_memo_title]."'
		, yhb_mtm_grade = '".$_POST[yhb_mtm_grade]."'
		, yhb_use_member = '".$_POST[yhb_use_member]."'
		, yhb_use_agreement = '".$_POST[yhb_use_agreement]."'
		, yhb_use_memoimg = '".$_POST[yhb_use_memoimg]."'
		, yhb_use_memosecret = '".$_POST[yhb_use_memosecret]."'
		, yhb_use_joo = '".$_POST[yhb_use_joo]."'
		, yhb_use_list = '".$_POST[yhb_use_list]."'
		, yhb_use_category = '".$_POST[yhb_use_category]."'
		, yhb_use_search = '".$_POST[yhb_use_search]."'
		, yhb_use_view = '".$_POST[yhb_use_view]."'
		, yhb_use_checkbox = '".$_POST[yhb_use_checkbox]."'
		, yhb_use_memo = '".$_POST[yhb_use_memo]."'
		, yhb_use_secret = '".$_POST[yhb_use_secret]."'
		, yhb_use_secret_write = '".$_POST[yhb_use_secret_write]."'
		, yhb_grant_list = '".$_POST[yhb_grant_list]."'
		, yhb_grant_write = '".$_POST[yhb_grant_write]."'
		, yhb_grant_view = '".$_POST[yhb_grant_view]."'
		, yhb_grant_memo = '".$_POST[yhb_grant_memo]."'
		, yhb_grant_reply = '".$_POST[yhb_grant_reply]."'
		, yhb_grant_notice = '".$_POST[yhb_grant_notice]."'
		, yhb_grant_delete = '".$_POST[yhb_grant_delete]."'
		, yhb_grant_secret = '".$_POST[yhb_grant_secret]."'
		, yhb_admin_write = '".$_POST[yhb_admin_write]."'
		, yhb_admin_delete = '".$_POST[yhb_admin_delete]."'
		, yhb_admin_memo = '".$_POST[yhb_admin_memo]."'
		, yhb_admin_reply = '".$_POST[yhb_admin_reply]."'
		, yhb_admin_notice = '".$_POST[yhb_admin_notice]."'
		, yhb_admin_secret = '".$_POST[yhb_admin_secret]."'
	");
	
	$query = "update yh_config_board set ".$modify." where yhb_name = '".$_POST["_id"]."'";
	if($_LhDb->Query($query)) {
		$c_query = "update yh_category set yhb_group_no = '".$_POST[yhb_group_no]."' where yhb_board_name = '".$_POST["_id"]."'";
		$_LhDb->Query($c_query);
		echo("<p class=\"complete\" title=\"modify\">게시판 정보 저장이 완료 되었습니다.</p>");
	} else {
		echo("<p class=\"error\">게시판 정보 저장에 실패하였습니다.(".$query.")</p>");
	}
	exit();
}

$reg_start_time = mktime(0,0,0, date("m", $_POST[yhb_reg_date]), 1, date("Y", $_POST[yhb_reg_date])); // 카운티할 회원 시작시간
$reg_end_time = mktime(23,59,59, date("m", $_POST[yhb_reg_date]), date("t", $_POST[yhb_reg_date]), date("Y", $_POST[yhb_reg_date])); // 카운티항 회원 마감시간
if(!$_POST["_group"]) $_POST["_group"] = TableMax("yh_group", "yhb_number", "101", "yhb_number != '' order by yhb_number asc"); // 그룹 코드
if(!$_POST[yhb_group_no]) $_POST[yhb_group_no] = $_POST["_group"];
$_POST[yhb_number] = TableMax("yh_config_board", "yhb_number", $_POST[yhb_group_no]."".date("ym", $_POST[yhb_reg_date])."001", "yhb_reg_date >= '".$reg_start_time."' AND yhb_reg_date <= '".$reg_end_time."'"); // 게시판회원 번호

if(!$_POST[yhb_body_top]) $_POST[yhb_body_top] = 0;
if(!$_POST[yhb_body_left]) $_POST[yhb_body_left] = 0;
if(!$_POST[yhb_table_width]) $_POST[yhb_table_width] = 100;
if(!$_POST[yhb_table_cell_width]) $_POST[yhb_table_cell_width] = 95;
if(!$_POST[yhb_subject_limit]) $_POST[yhb_subject_limit] = 40;
if(!$_POST[yhb_filerows]) $_POST[yhb_filerows] = 0;
if(!$_POST[yhb_iboard_width]) $_POST[yhb_iboard_width] = 100;
if(!$_POST[yhb_iboard_subject_limit]) $_POST[yhb_iboard_subject_limit] = 20;
if(!$_POST[yhb_iboard_rows]) $_POST[yhb_iboard_rows] = 5;
if(!$_POST[yhb_iboard_page]) $_POST[yhb_iboard_page] = 5;
if(!$_POST[yhb_iboard_x]) $_POST[yhb_iboard_x] = 0;
if(!$_POST[yhb_iboard_y]) $_POST[yhb_iboard_y] = 0;
if(!$_POST[yhb_board_point]) $_POST[yhb_board_point] = 0;
if(!$_POST[yhb_memo_point]) $_POST[yhb_memo_point] = 0;
if(!$_POST[yhb_photo_wlimit]) $_POST[yhb_photo_wlimit] = 550;
if(!$_POST[yhb_grant_list]) $_POST[yhb_grant_list] = 10;
if(!$_POST[yhb_grant_write]) $_POST[yhb_grant_write] = 10;
if(!$_POST[yhb_grant_view]) $_POST[yhb_grant_view] = 10;
if(!$_POST[yhb_grant_memo]) $_POST[yhb_grant_memo] = 10;
if(!$_POST[yhb_grant_reply]) $_POST[yhb_grant_reply] = 10;
if(!$_POST[yhb_grant_notice]) $_POST[yhb_grant_notice] = 10;
if(!$_POST[yhb_grant_delete]) $_POST[yhb_grant_delete] = 10;
if(!$_POST[yhb_grant_secret]) $_POST[yhb_grant_secret] = 10;

$fields = eregi_replace("\r|\n|\t", "", "yhb_number
, yhb_group_no
, yhb_name
, yhb_title_name
, yhb_skin
, yhb_style
, yhb_language
, yhb_img_title
, yhb_img_back
, yhb_img_title_align
, yhb_img_back_align
, yhb_img_back_align_x
, yhb_img_back_align_y
, yhb_start_url
, yhb_body_top
, yhb_body_left
, yhb_table_align
, yhb_table_width
, yhb_table_cell_width
, yhb_subject_limit
, yhb_rows
, yhb_page
, yhb_time
, yhb_ext
, yhb_filerows
, yhb_upload_size
, yhb_board_sid
, yhb_file_top
, yhb_file_bottom
, yhb_header_url
, yhb_footer_url
, yhb_header
, yhb_footer
, yhb_memo_title
, yhb_text_write
, yhb_text_reply
, yhb_text_mail
, yhb_text_write_bt
, yhb_text_reply_bt
, yhb_text_mail_bt
, yhb_color_body
, yhb_color_table
, yhb_color_cell
, yhb_iboard_skin
, yhb_iboard_color_text
, yhb_iboard_color_link
, yhb_iboard_color_content
, yhb_iboard_align
, yhb_iboard_width
, yhb_iboard_subject_limit
, yhb_iboard_rows
, yhb_iboard_page
, yhb_iboard_img_back
, yhb_iboard_color_body
, yhb_iboard_x
, yhb_iboard_y
, yhb_iboard_target
, yhb_style_css
, yhb_script
, yhb_board_point
, yhb_memo_point
, yhb_remailaddress
, yhb_remail_style
, yhb_photo_wlimit
, yhb_mtm_grade
, yhb_use_member
, yhb_use_nomail
, yhb_use_agreement
, yhb_use_header_html
, yhb_use_footer_html
, yhb_use_text_write_auto
, yhb_use_text_write_html
, yhb_use_text_write_tp
, yhb_use_text_write_bt
, yhb_use_text_reply_auto
, yhb_use_text_reply_html
, yhb_use_text_reply_tp
, yhb_use_text_reply_bt
, yhb_use_text_mail_auto
, yhb_use_text_mail_html
, yhb_use_text_mail_tp
, yhb_use_text_mail_bt
, yhb_use_memoimg
, yhb_use_memosecret
, yhb_use_download
, yhb_use_remail
, yhb_use_img_title
, yhb_use_img_back
, yhb_use_alpha
, yhb_use_joo
, yhb_use_notice_admin
, yhb_use_login
, yhb_use_list
, yhb_use_category
, yhb_use_ct_list
, yhb_use_reply_list
, yhb_use_homepage
, yhb_use_email
, yhb_use_search
, yhb_use_link1
, yhb_use_link2
, yhb_use_pds
, yhb_use_cld
, yhb_use_view
, yhb_use_file1
, yhb_use_file2
, yhb_use_checkbox
, yhb_use_memo
, yhb_use_secret
, yhb_use_secret_write
, yhb_use_iboard_img_back
, yhb_use_iboard_alpha
, yhb_use_iboard_icon
, yhb_use_iboard_page
, yhb_use_iboard_line
, yhb_grant_list
, yhb_grant_write
, yhb_grant_view
, yhb_grant_memo
, yhb_grant_reply
, yhb_grant_notice
, yhb_grant_delete
, yhb_grant_secret
, yhb_admin_write
, yhb_admin_delete
, yhb_admin_memo
, yhb_admin_reply
, yhb_admin_notice
, yhb_admin_secret
, yhb_reg_date
");

$values = eregi_replace("\r|\n|\t", "", "'".$_POST[yhb_number]."'
, '".$_POST[yhb_group_no]."'
, '".$_POST[yhb_name]."'
, '".$_POST[yhb_title_name]."'
, '".$_POST[yhb_skin]."'
, '".$_POST[yhb_style]."'
, '".$_POST[yhb_language]."'
, '".$_POST[yhb_img_title]."'
, '".$_POST[yhb_img_back]."'
, '".$_POST[yhb_img_title_align]."'
, '".$_POST[yhb_img_back_align]."'
, '".$_POST[yhb_img_back_align_x]."'
, '".$_POST[yhb_img_back_align_y]."'
, '".$_POST[yhb_start_url]."'
, '".$_POST[yhb_body_top]."'
, '".$_POST[yhb_body_left]."'
, '".$_POST[yhb_table_align]."'
, '".$_POST[yhb_table_width]."'
, '".$_POST[yhb_table_cell_width]."'
, '".$_POST[yhb_subject_limit]."'
, '".$_POST[yhb_rows]."'
, '".$_POST[yhb_page]."'
, '".$_POST[yhb_time]."'
, '".$_POST[yhb_ext]."'
, '".$_POST[yhb_filerows]."'
, '".$_POST[yhb_upload_size]."'
, '".$_POST[yhb_board_sid]."'
, '".$_POST[yhb_file_top]."'
, '".$_POST[yhb_file_bottom]."'
, '".$_POST[yhb_header_url]."'
, '".$_POST[yhb_footer_url]."'
, '".$_POST[yhb_header]."'
, '".$_POST[yhb_footer]."'
, '".$_POST[yhb_memo_title]."'
, '".$_POST[yhb_text_write]."'
, '".$_POST[yhb_text_reply]."'
, '".$_POST[yhb_text_mail]."'
, '".$_POST[yhb_text_write_bt]."'
, '".$_POST[yhb_text_reply_bt]."'
, '".$_POST[yhb_text_mail_bt]."'
, '".$_POST[yhb_color_body]."'
, '".$_POST[yhb_color_table]."'
, '".$_POST[yhb_color_cell]."'
, '".$_POST[yhb_iboard_skin]."'
, '".$_POST[yhb_iboard_color_text]."'
, '".$_POST[yhb_iboard_color_link]."'
, '".$_POST[yhb_iboard_color_content]."'
, '".$_POST[yhb_iboard_align]."'
, '".$_POST[yhb_iboard_width]."'
, '".$_POST[yhb_iboard_subject_limit]."'
, '".$_POST[yhb_iboard_rows]."'
, '".$_POST[yhb_iboard_page]."'
, '".$_POST[yhb_iboard_img_back]."'
, '".$_POST[yhb_iboard_color_body]."'
, '".$_POST[yhb_iboard_x]."'
, '".$_POST[yhb_iboard_y]."'
, '".$_POST[yhb_iboard_target]."'
, '".$_POST[yhb_style_css]."'
, '".$_POST[yhb_script]."'
, '".$_POST[yhb_board_point]."'
, '".$_POST[yhb_memo_point]."'
, '".$_POST[yhb_remailaddress]."'
, '".$_POST[yhb_remail_style]."'
, '".$_POST[yhb_photo_wlimit]."'
, '".$_POST[yhb_mtm_grade]."'
, '".$_POST[yhb_use_member]."'
, '".$_POST[yhb_use_nomail]."'
, '".$_POST[yhb_use_agreement]."'
, '".$_POST[yhb_use_header_html]."'
, '".$_POST[yhb_use_footer_html]."'
, '".$_POST[yhb_use_text_write_auto]."'
, '".$_POST[yhb_use_text_write_html]."'
, '".$_POST[yhb_use_text_write_tp]."'
, '".$_POST[yhb_use_text_write_bt]."'
, '".$_POST[yhb_use_text_reply_auto]."'
, '".$_POST[yhb_use_text_reply_html]."'
, '".$_POST[yhb_use_text_reply_tp]."'
, '".$_POST[yhb_use_text_reply_bt]."'
, '".$_POST[yhb_use_text_mail_auto]."'
, '".$_POST[yhb_use_text_mail_html]."'
, '".$_POST[yhb_use_text_mail_tp]."'
, '".$_POST[yhb_use_text_mail_bt]."'
, '".$_POST[yhb_use_memoimg]."'
, '".$_POST[yhb_use_memosecret]."'
, '".$_POST[yhb_use_download]."'
, '".$_POST[yhb_use_remail]."'
, '".$_POST[yhb_use_img_title]."'
, '".$_POST[yhb_use_img_back]."'
, '".$_POST[yhb_use_alpha]."'
, '".$_POST[yhb_use_joo]."'
, '".$_POST[yhb_use_notice_admin]."'
, '".$_POST[yhb_use_login]."'
, '".$_POST[yhb_use_list]."'
, '".$_POST[yhb_use_category]."'
, '".$_POST[yhb_use_ct_list]."'
, '".$_POST[yhb_use_reply_list]."'
, '".$_POST[yhb_use_homepage]."'
, '".$_POST[yhb_use_email]."'
, '".$_POST[yhb_use_search]."'
, '".$_POST[yhb_use_link1]."'
, '".$_POST[yhb_use_link2]."'
, '".$_POST[yhb_use_pds]."'
, '".$_POST[yhb_use_cld]."'
, '".$_POST[yhb_use_view]."'
, '".$_POST[yhb_use_file1]."'
, '".$_POST[yhb_use_file2]."'
, '".$_POST[yhb_use_checkbox]."'
, '".$_POST[yhb_use_memo]."'
, '".$_POST[yhb_use_secret]."'
, '".$_POST[yhb_use_secret_write]."'
, '".$_POST[yhb_use_iboard_img_back]."'
, '".$_POST[yhb_use_iboard_alpha]."'
, '".$_POST[yhb_use_iboard_icon]."'
, '".$_POST[yhb_use_iboard_page]."'
, '".$_POST[yhb_use_iboard_line]."'
, '".$_POST[yhb_grant_list]."'
, '".$_POST[yhb_grant_write]."'
, '".$_POST[yhb_grant_view]."'
, '".$_POST[yhb_grant_memo]."'
, '".$_POST[yhb_grant_reply]."'
, '".$_POST[yhb_grant_notice]."'
, '".$_POST[yhb_grant_delete]."'
, '".$_POST[yhb_grant_secret]."'
, '".$_POST[yhb_admin_write]."'
, '".$_POST[yhb_admin_delete]."'
, '".$_POST[yhb_admin_memo]."'
, '".$_POST[yhb_admin_reply]."'
, '".$_POST[yhb_admin_notice]."'
, '".$_POST[yhb_admin_secret]."'
, '".$_POST[yhb_reg_date]."'
");

$query = "insert into yh_config_board(".$fields.") values(".$values.")";

//echo $query;
if($_LhDb->Query($query)) {
	$c_query = "update yh_category set yhb_board_no = '".$_POST[yhb_number]."', yhb_board_name = '".$_POST[yhb_name]."', yhb_group_no = '".$_POST[yhb_group_no]."' where yhb_board_name = '".$_POST["_tmp_number"]."'";
	$_LhDb->Query($c_query);
	$load_page = "<p class=\"complete\" title=\"register\">게시판 등록이 완료 되었습니다.</p>";
	echo($load_page);
} else {
	echo("<p class=\"error\">게시판 등록 도중 실패하였습니다.(".$query.")</p>");
}

?>