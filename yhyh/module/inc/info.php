<?php
# path
$_lh_main_Root = $_SERVER['DOCUMENT_ROOT'];

// 링크 방식
$_rewrite_mod = false;

// 전화 번호 국번 정리
$_tel_type = array("02", "051", "053", "032", "062", "042", "052", "044", "031", "033", "043", "041", "063", "061", "054", "055", "064");
// 휴대폰 번호 앞자리 정리
$_mobile_type = array("010", "011", "016", "017", "018", "019");

// 연락처 전체
$_tel_type_all = array_merge($_mobile_type, $_tel_type);

// 검색삭제 패턴
$_p_pattern = "(([A-Za-z0-9_가-힣\x20\/\.!,%])*(\-)*)*";

// 요일 변수 정리
$_week_data_array = array("일", "월", "화", "수", "목", "금", "토");
// 시간 계산용 데이터
$_timeStemp = array("hour"=>3600, "day"=>86400, "year"=>31536000);

$_email_type = array("naver.com"
, "gmail.com"
, "nate.com"
, "hotmail.com"
, "daum.net"
, "hanmail.net"
, "msn.com"
);

?>