<?php
$session_path = _lh_document_root._lh_yhyh_web."/session_tmp";

if(!is_dir($session_path)) {
	@mkdir($session_path, 0777);
	@chmod($session_path, 0777);
	}

@session_save_path($session_path);
@session_cache_limiter('nocache, must_revalidate');
@session_set_cookie_params(0,"/");

session_start();
chmod($session_path."/sess_".session_id(), 0777);


session_register("yh_ipaddress");
session_register("yh_cookie_query1");
session_register("yh_cookie_query2");
session_register("yh_user_id");
session_register("yh_user_pass");
session_register("yh_user_no");
session_register("yh_user_group");
session_register("yh_user_admin");
session_register("yh_user_name");
session_register("yh_user_level");
session_register("yh_user_mysign");
session_register("yh_board_pass");
session_register("yh_return_url_login");
session_register("yh_return_url_list");
session_register("yh_return_url_back");


$yh_ipaddress = $REMOTE_ADDR;
?>