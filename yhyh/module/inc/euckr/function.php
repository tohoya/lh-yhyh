<?php

function Encoding_Text($str, $enc_change) {
	$enc = @mb_detect_encoding($str, "EUC-KR, UTF-8, shift_iis, CN-GB");
	if(!$enc_change) return $str;
	
	switch($enc) {
		case $enc_change:
			return $str;
		break;
		default:
			return iconv($enc, $enc_change, $str);
	}
}

function Header_Init($incoding) {
	switch($incoding) {
		case "euckr/":
			header("Content-Type: text/html; charset=euc-kr");
		break;
		default:
			header("Content-Type: text/html; charset=utf-8");
	}
}

// 게시물 번호에 따른 게시판 아이디 구하기
function Board_Row_Id_Get($id = "") {
	global $_LhDb;
	
	if(!$id) return "";
	$query = "select yhb_board_name from yh_board where yhb_number = '".$id."'";
	$bb = $_LhDb->Fetch_Object_Query($query);
	if(!$bb->yhb_board_name) return "";
	return $bb->yhb_board_name;
}

// 구분자 나눠서 인덱스별 출력 함수
function Get_Split($str, $idx = 0, $division = "-") {
	$item = split($division, $str);
	return $item[$idx];
}

// 구분자 나눠서 키워드와 비교하여 true 혹은 false를 출력해주는 함수
function Split_Check($str, $key, $division = ",") {
	$item = split($division, $str);
	$count = sizeof($item);
	for($i = 0; $i < $count; $i++) {
		if($item[$i] == $key) return true;
	}
	return false;
}

// 코드 맥스값 구하기
function TableMax($_tabne_name, $_field, $_start_number = 10000001, $_where = "") {
	global $_LhDb;
	$_start_number = $_start_number + 0;
	$query = "select max(".$_field.") as count from ".$_tabne_name;
	if($_where) $query .= " where ".$_where;
	//return $query;
	$object = $_LhDb->Fetch_Object_Query($query);
	return ($object->count >= $_start_number) ? ($object->count + 1) : $_start_number;
}

// 이메일 체크
function isEmail($email = "") {
	return (eregi("([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)",$email)) ? $email : "";
}

// Url 체크
function isLink($link) {
	if(eregi("^http://([a-z0-9\_\-\./~@?=&amp;-\#{5,}]+)", $link)) return $link;
	else if(eregi("^mms://([a-z0-9\_\-\./~@?=&amp;-\#{5,}]+)", $link)) return $link;
	else if(eregi("^ftp://([a-z0-9\_\-\./~@?=&amp;-\#{5,}]+)", $link)) return $link;
	else if(eregi("([a-z0-9\_\-\./~@?=&amp;-\#{5,}]+)", $link)) return $link;
	else return '';
}
	
// URL, Mail을 자동으로 체크하여 링크만듬
function Link_url($content) {
	// URL 치환
	$http_pattern = "/([^\"\'\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/";
	$content = preg_replace($http_pattern,"\\1<a href=\\2://\\3 target=_blank>\\2://\\3</a>", " ".$content);

	// 메일 치환
	$email_pattern = "/([ \n]+)([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)/";
	$content = preg_replace($email_pattern,"\\1<a href=mailto:\\2@\\3>\\2@\\3</a>", " ".$content);
	return $content;
}

// 파일 확장자 빼내기
function FIle_ext($files) {
	return substr(strrchr($files,"."),1);
}

// 이메일 서비스 함수
function EmailService($_form, $_to, $_title, $_content, $_fileForm = "/include/html/email_form.html") {
	global $_SERVER;
	
	//header("Content-Type: text/html; charset=UTF-8");
	
	$reg_date = time();

	if(!$_form->name) $_form->name = "한국부식방식학회 운영자";
	if(!$_form->email) $_form->email = "hoya@tdesign.co.kr";
	
	$count = sizeof($_to);
	
	if($count > 0) {
		$to_data = iconv("utf-8", "euc-kr", $_to[0]->name)."<".iconv("utf-8", "euc-kr", $_to[0]->email).">\r\n";
		for($i = 1; $i < $count; $i++) {
			$to_data .= ",".iconv("utf-8", "euc-kr", $_to[$i]->name)."<".iconv("utf-8", "euc-kr", $_to[$i]->email).">";
		}
	}
	
	$add = "content-Type: text/html; charset=euc-kr\n";
	$add .= "From: ".iconv("utf-8", "euc-kr", $_form->name)." <".iconv("utf-8", "euc-kr", $_form->email).">\r\n";
	$time_date = date("Y년m월d일 H:i", $reg_date);
	//$content = nl2br($_content);
	$title = $_title;

	// 이메일 폼이 있으면...
	$formFile = $_SERVER['DOCUMENT_ROOT']."/".$_fileForm;
	$text_mail = "";
	if(file_exists($formFile)) {
		$emailext = fopen($formFile,"r");
		$emailfilesize = filesize($formFile);
		$text_mail = fread($emailext,$emailfilesize);
		$text_mail = eregi_replace("__email_title__", $title, $text_mail);
		for($i = 1; $i <= sizeof($_content); $i++) {
			$text_mail = eregi_replace("__email_content_".$i."__", $_content[$i], $text_mail);
		}
		fclose($emailext);
	} else {
		for($i = 1; $i <= sizeof($_content); $i++) {
			$text_mail .= "<br>".$_content[$i];
		}
	}

	$body = $text_mail."\r\n\r\n";

	// 메일 전송하기
	if(@mail($to_data, iconv("utf-8", "euc-kr", $title) , iconv("utf-8", "euc-kr", $body) , $add )) {
		return "true";
	} else {
		return "false";
	}
}

?>