<?php
# path
$_lh_main_Root = $_SERVER['DOCUMENT_ROOT'];

// ��ũ ���
$_rewrite_mod = false;

// ��ȭ ��ȣ ���� ����
$_tel_type = array("02", "051", "053", "032", "062", "042", "052", "044", "031", "033", "043", "041", "063", "061", "054", "055", "064");
// �޴��� ��ȣ ���ڸ� ����
$_mobile_type = array("010", "011", "016", "017", "018", "019");

// ����ó ��ü
$_tel_type_all = array_merge($_mobile_type, $_tel_type);

// �˻����� ����
$_p_pattern = "(([A-Za-z0-9_��-�R\x20\/\.!,%])*(\-)*)*";

// ���� ���� ����
$_week_data_array = array("��", "��", "ȭ", "��", "��", "��", "��");
// �ð� ���� ������
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