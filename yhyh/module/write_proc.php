<?php

$_POST[yhb_reg_date] = ($_POST[yhb_year] && $_POST[yhb_month] && $_POST[yhb_day]) ? mktime(date("H"), date("i"), date("s"), $_POST[yhb_year], $_POST[yhb_month], $_POST[yhb_day]) : time();

// 변수값이 있으면 쿠키에 저장
if($_POST[yhb_title]) setcookie("yh_board_title", stripslashes($_POST[yhb_title]), time() + 2592000, "/");
if($_POST[yhb_name]) setcookie("yh_board_name", stripslashes($_POST[yhb_name]), time()+2592000, "/");
if($_POST[yhb_email]) setcookie("yh_board_email", isEmail($_POST[yhb_email]), time()+2592000, "/");
if($_POST[yhb_homepage]) setcookie("yh_board_home", isLink($_POST[yhb_homepage]), time()+2592000, "/");
$_POST["_id"] = $_config->yhb_name; // board skin 스타일로 인해 추가된 내용임

// 게시판 기본 정보 치환 작업
$_POST[yhb_no] = TableMax("yh_board", "yhb_no", 10000001, "yhb_board_name = '".$_POST["_id"]."'"); // 게시판 별 카운팅

$reg_start_time = mktime(0,0,0, date("m", $_POST[yhb_reg_date]), 1, date("Y", $_POST[yhb_reg_date])); // 카운티항 게시물 시작시간
$reg_end_time = mktime(23,59,59, date("m", $_POST[yhb_reg_date]), date("t", $_POST[yhb_reg_date]), date("Y", $_POST[yhb_reg_date])); // 카운티항 게시물 마감시간

switch(strtolower($_POST["_writeMode"])) { // 글 번호
	case "modify":
		$_POST[yhb_number] = $_POST["_no"];
	break;
	default:
		$_POST[yhb_number] = TableMax("yh_board", "yhb_number", date("ym", $_POST[yhb_reg_date])."001", "LEFT(yhb_number, 4) = '".date("ym", $_POST[yhb_reg_date])."'"); // 글 번호
}

$_POST[yhb_id] = $_member->yhb_id;
$_POST[yhb_pass] = $_member->yhb_pass;
$_POST[yhb_mysign] = $_member->yhb_mysign;

$_POST[yhb_group_no] = $_config->yhb_group_no; // 그룹 번호
$_POST[yhb_board_name] = $_POST["_id"]; // 보드 이름
$_POST[yhb_hit] = $_POST[yhb_vote] = $_POST[yhb_prev_no] = $_POST[yhb_next_no] = 0; // 조회수, 추천수 초기 값 설정

$_ext = split("/",$_config->yhb_ext); // 사용할 확장자
if(!$_POST[yhb_category]) $_POST[yhb_category] = "1"; // 카테고리가 없으면 1로 저장함
if($_POST[yhb_email]) $_POST[yhb_email] = isEmail($_POST[yhb_email]); // 이메일 체크
if($_POST[yhb_homepage]) $_POST[yhb_homepage] = isLink($_POST[yhb_homepage]); // 홈페이지 url 체크
$_POST[yhb_fileurl] = "http://".$SERVER_NAME.dirname($PHP_SELF)."/yhboard/files"; // 처음 등록된 경로
$_POST[yhb_ip] = $_SERVER['REMOTE_ADDR'];

// 글 기본 공개로 설정 되어 있으면서 값이 없으면 기본 값으로 변경
if(!$_group->yhb_use_agreement && !$_config->yhb_use_agreement) {
	if(!$_POST[yhb_check]) $_POST[yhb_check] = "1";
}

// 추가된 파일 정리
$arr = array();
$arr = split(",", $_POST[file_add_LHtmlEditers]);
$count = sizeof($arr);
$update = "board_no = '".$_POST[yhb_number]."', group_no = '".$_config->yhb_group_no."'";
$f_count = 0;
for($i = 0; $i < $count; $i++) {
	if(trim($arr[$i])) {
		$update = "board_no = '".$_POST[yhb_number]."', group_no = '".$_config->yhb_group_no."', f_order = '".$f_count."'";
		$update .= " where seq = '".trim($arr[$i])."'";
		$query = "update yh_file set ".$update;
		$_LhDb->Query($query);
		$f_count++;
	}
}

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
		$_LhDb->Query($query);
	}
}

if($_POST["_no"]) {
	$query = "select yhb_number_up, yhb_number, yhb_number_order, yhb_number_rows, yhb_reg_date from yh_board where yhb_number = '".$_POST["_no"]."'";
	$_bb = $_LhDb->Fetch_Object_Query($query);
	
	switch(strtolower($_POST["_writeMode"])) {
		case "reply":
			$count_style = 6;
			$_POST[yhb_number_up] = $_bb->yhb_number_up;
			$_POST[yhb_number_father] = $_bb->yhb_number;
			$_POST[yhb_number_order] = $_bb->yhb_number_order + 1;

			$query = "select yhb_number_rows, yhb_number from yh_board where yhb_number_rows > '".$_bb->yhb_number_rows."' AND yhb_number_up = '".$_bb->yhb_number_up."' order by yhb_number_rows";
			$result = $_LhDb->Query($query);
			while($f = $_LhDb->Fetch_Object($result)) {
				$f->yhb_number_rows++;
				$query = "update yh_board set yhb_number_rows = '".$f->yhb_number_rows."' where yhb_number = '".$f->yhb_number."'";
				$_LhDb->Query($query);
			}
			$_POST[yhb_number_rows] = $_bb->yhb_number_rows + 1;
			
			if($_config->yhb_use_text_reply_auto) {
				// 리플시 첨가할 상단내용이 있으면서 사용하기로 되어있을때
				if($_config->yhb_use_text_reply_tp && $_config->yhb_text_reply) {
					$text_reply = ($_config->yhb_use_text_reply_html) ? $_config->yhb_text_reply : nl2br($_config->yhb_text_reply);
					$_POST[yhb_content] = $text_reply."<br><br>".$_POST[yhb_content];
				}
		
				// 리플시 첨가할 하단내용이 있으면서 사용하기로 되어있을때
				if($_config->yhb_use_text_reply_bt && $_config->yhb_text_reply_bt) {
					$text_reply = ($_config->yhb_use_text_write_html) ? $_config->yhb_text_reply_bt : nl2br($_config->yhb_text_reply_bt);
					$_POST[yhb_content] .= "<br><br>".$text_reply;
				}
			}
			
		break;
		case "modify":
			$_POST[yhb_reg_date] = ($_POST[yhb_year] && $_POST[yhb_month] && $_POST[yhb_day]) ? mktime(date("H"), date("i"), date("s"), $_POST[yhb_year], $_POST[yhb_month], $_POST[yhb_day]) : $_bb->yhb_reg_date;
			
			$modify = "yhb_title = '".$_POST[yhb_title]."'
			, yhb_name = '".$_POST[yhb_name]."'
			, yhb_email = '".$_POST[yhb_email]."'
			, yhb_homepage = '".$_POST[yhb_homepage]."'
			, yhb_content = '".$_POST[yhb_content]."'
			, yhb_category = '".$_POST[yhb_category]."'
			, yhb_reg_date = '".$_POST[yhb_reg_date]."'
			, yhb_link_url1 = '".$_POST[yhb_link_url1]."'
			, yhb_link_url2 = '".$_POST[yhb_link_url2]."'
			, yhb_reply_mail = '".$_POST[yhb_reply_mail]."'
			, yhb_secret = '".$_POST[yhb_secret]."'
			, yhb_secret_pass = '".$_LhDb->Base64("encode", $_POST[yhb_secret_pass])."'
			, yhb_check = '".$_POST[yhb_check]."'
			, yhb_notice = '".$_POST[yhb_notice]."'
			, yhb_html = '".$_POST[yhb_html]."'
			, yhb_ip = '".$_POST[yhb_ip]."'
			, yhb_align = '".$_POST[yhb_align]."'
			, yhb_view_member = '".$_POST[yhb_view_member]."'
			, yhb_word1 = '".$_POST[yhb_word1]."'
			, yhb_word2 = '".$_POST[yhb_word2]."'
			, yhb_word3 = '".$_POST[yhb_word3]."'
			, yhb_word4 = '".$_POST[yhb_word4]."'
			, yhb_text1 = '".$_POST[yhb_text1]."'
			, yhb_text2 = '".$_POST[yhb_text2]."'
			, yhb_text3 = '".$_POST[yhb_text3]."'
			, yhb_text4 = '".$_POST[yhb_text4]."'
			";
			
			$query = "update yh_board set ".$modify." where yhb_number = '".$_POST["_no"]."'";
			if($_LhDb->Query($query)) {
				echo("<p title=\"modify\">글 수정이 완료 되었습니다.</p>");
			} else {
				echo("<p title=\"error\">저장에 실패하였습니다.(".$query.")</p>");
			}
			exit();
		break;
		
	}
}

if(strtolower($_POST["_writeMode"]) != "reply") {
	$_POST[yhb_number_up] = $_POST[yhb_number];
	$_POST[yhb_number_father] = "0";
	$_POST[yhb_number_rows] = "0";
	$_POST[yhb_number_order] = "0";
}

// 답글일때 답메일이 있을경우 메일 발송
if(strtolower($_POST["_writeMode"]) == "reply" && $_bb->yhb_reply_mail == 1) {
	send_mail($_bb->yhb_number, "save");
}

$fields = "yhb_number
, yhb_no
, yhb_group_no
, yhb_board_name
, yhb_title
, yhb_name
, yhb_email
, yhb_homepage
, yhb_content
, yhb_hit
, yhb_vote
, yhb_category
, yhb_reg_date
, yhb_link_url1
, yhb_link_url2
, yhb_reply_mail
, yhb_secret
, yhb_check
, yhb_notice
, yhb_html
, yhb_number_father
, yhb_number_up
, yhb_number_order
, yhb_number_rows
, yhb_prev_no
, yhb_next_no
, yhb_mysign
, yhb_id
, yhb_pass
, yhb_board_pass
, yhb_secret_pass
, yhb_member_no
, yhb_ip
, yhb_align
, yhb_view_member
, yhb_word1
, yhb_word2
, yhb_word3
, yhb_word4
, yhb_text1
, yhb_text2
, yhb_text3
, yhb_text4
, yhb_fileurl
";
if(!$_POST[yhb_category]) $_POST[yhb_category] = 10000001;

$values = "'".$_POST[yhb_number]."'
, '".$_POST[yhb_no]."'
, '".$_POST[yhb_group_no]."'
, '".$_POST[yhb_board_name]."'
, '".$_POST[yhb_title]."'
, '".$_POST[yhb_name]."'
, '".$_POST[yhb_email]."'
, '".$_POST[yhb_homepage]."'
, '".$_POST[yhb_content]."'
, '".$_POST[yhb_hit]."'
, '".$_POST[yhb_vote]."'
, '".$_POST[yhb_category]."'
, '".$_POST[yhb_reg_date]."'
, '".$_POST[yhb_link_url1]."'
, '".$_POST[yhb_link_url2]."'
, '".$_POST[yhb_reply_mail]."'
, '".$_POST[yhb_secret]."'
, '".$_POST[yhb_check]."'
, '".$_POST[yhb_notice]."'
, '".$_POST[yhb_html]."'
, '".$_POST[yhb_number_father]."'
, '".$_POST[yhb_number_up]."'
, '".$_POST[yhb_number_order]."'
, '".$_POST[yhb_number_rows]."'
, '".$_POST[yhb_prev_no]."'
, '".$_POST[yhb_next_no]."'
, '".$_POST[yhb_mysign]."'
, '".$_POST[yhb_id]."'
, '".$_POST[yhb_pass]."'
, '".$_LhDb->Base64("encode", $_POST[yhb_board_pass])."'
, '".$_LhDb->Base64("encode", $_POST[yhb_secret_pass])."'
, '".$_POST[yhb_member_no]."'
, '".$_POST[yhb_ip]."'
, '".$_POST[yhb_align]."'
, '".$_POST[yhb_view_member]."'
, '".$_POST[yhb_word1]."'
, '".$_POST[yhb_word2]."'
, '".$_POST[yhb_word3]."'
, '".$_POST[yhb_word4]."'
, '".$_POST[yhb_text1]."'
, '".$_POST[yhb_text2]."'
, '".$_POST[yhb_text3]."'
, '".$_POST[yhb_text4]."'
, '".$_POST[yhb_fileurl]."'
";

$query = "insert into yh_board(".$fields.") values(".$values.")";

if($_LhDb->Query($query)) {
	
	// 리턴메일이
	if($_group->yhb_remail_style != 1 || ($_group->yhb_remail_style == 5 && $_group->yhb_remailaddress)) {
		if($_group->yhb_board_remail) {/*send_mail("", "remail");*/ }
		else {
			if($_config->yhb_use_remail) {
				if($_config->yhb_remail_style < 5 || ($_config->yhb_remail_style == 5 && $_config->yhb_remailaddress)) send_mail("", "remail");
			}
		}
	}
	$load_page = "<p title=\"";
	$load_page .= strtolower($_POST["_writeMode"]) == "reply" ? "reply" : "write";
	$load_page .= ($_config->yhb_use_view == 1) ? " class=\"".$_POST[yhb_number]."\"" : "";
	$load_page .= "\">글 등록이 완료 되었습니다.";
	$load_page .= "</p>";
	echo($load_page);
} else {
	echo("<p title=\"error\">저장에 실패하였습니다.(".$query.")</p>");
}

?>