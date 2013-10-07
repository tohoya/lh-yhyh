<?php
/**
 * DPK(Default Programe Kit) : ÇÁ·Î±×·¥ Á¦ÀÛ Å°Æ®
 * ¹öÀü				: v1.0
 * Á¦ÀÛÀÏ			: 2012. 08. 29
 * ¾÷µ¥ÀÌÆ®ÀÏ	: 2012. 08. 29
 * Á¦ÀÛÀÚ			: lainToHoya
 * ÀÌ¸ÞÀÏ			: redhoya@gmail.com
**/
 
class Lh_Default_Class {
	// DB Á¢¼Ó °ü·Ã º¯¼ö
	var $dbCon;
	var $sqlType = "mysql";
	var $host = "locallost";
	var $user = "root";
	var $pass = "admin13579";
	
	// ÆäÀÌÁö ÀÎµ¦½ÌÀ» À§ÇÑ º¯¼ö
	var $page = 1;
	var $page_total = 1;
	var $php_self = "";
	var $rows_total = 0;
	var $link = "";
	
	var $status = "";
	
	// Default Function
	function Lh_Default_Class($_type, $_host, $_user, $_pass, $_db_name) {
		global $PHP_SELF;
		$this->php_self = $PHP_SELF;
		$this->status = $this->Db_Conn($_type, $_host, $_user, $_pass, $_db_name);
	}
	
	#########################################
	####### ÆäÀÌÁö ÀÎµ¦½ÌÀ» À§ÇÑ ÇÔ¼ö #######
	#########################################
	
	// Page Indexing Start
	function Page_Index($_query = "", $_order = "", $_page = 1, $_rows_limit = 10) {
		global $PHP_SELF, $_SERVER, $_p_pattern, $_start_time, $_REQUEST;
		//(&)*_admin=([A-Za-z0-9_°¡-ÆR\x20\/\-\.!,])*
		$pageStr = "^[&]|(&)*_page=".$_p_pattern."|(&)*_module=".$_p_pattern; ///|&_no=[0-9]+";
		$this->link = "".eregi_replace("^&", "", eregi_replace($pageStr, "", $_SERVER['QUERY_STRING']));
		
		if(!$_page) $_page = 1;
		$_query = eregi_replace("\r|\n|\t", "", $_query);
		$_total_query = eregi_replace("^select ".$_p_pattern." from", "select yhb_reg_date from", $_query);
		//$_total_result = $this->Fetch_Object_Query($_total_query);
		$this->rows_total = $this->Query_Row_Num($_total_query);
		$this->page_total = (ceil($this->rows_total / $_rows_limit) == 0) ? 1:ceil($this->rows_total / $_rows_limit);
		$this->page = ($_page > $this->page_total) ? $this->page_total : $_page;
		
		$start = ($this->page - 1) * $_rows_limit;
		$query = $_query." ".$_order." limit ".$start.",".$_rows_limit;
		if($_REQUEST["_speed"]) echo("\n<!-- timmer index ready(".$_total_query.") : ".(time()-$_start_time)."sec -->");
		return $this->Query($query);
	}
	
	// ÀüÃ¼ ±Û¼ö¸¦ Ãâ·ÂÇÑ´Ù.
	function Total_Row_Count() {
		return $this->rows_total;
	}
	
	// ÆäÀÌÂ¡ Ãâ·Â
	function Paging($_link = "", $_page_limit = 5, $_target = "self") {
		global $PHP_SELF, $_SERVER;
		if(!$_link) {
			$_link = eregi_replace("^&", "", $this->link);
		}
		
		if($_link) $_link = "&".$_link;
		
		$prev = ($this->page - 1 >= 1) ? $this->page - 1 : $this->page;
		$next = ($this->page + 1 <= $this->page_total) ? $this->page + 1 : $this->page;
		
		if($this->page_total > $_page_limit) {
			if($this->page_total < $this->page + ceil($_page_limit / 2)) {
				$start = $this->page_total - $_page_limit + 1;
				$end = $this->page_total;
			} else {
				if($this->page - floor($_page_limit / 2) >= 1) {
					$start = $this->page - floor($_page_limit / 2);
					$end = $this->page + ceil($_page_limit / 2) - 1;
				} else {
					$start = 1;
					$end = $_page_limit;
				}
			}
		} else {
				$start = 1;
				$end = $this->page_total;
		}
		$prev_prev = ($this->page - $_page_limit >= 1) ? $this->page - $_page_limit : 1;
		$next_next = ($this->page + $_page_limit <= $this->page_total) ? $this->page + $_page_limit : $this->page_total;
		
		$html_out = "<div>";
		
		$html_out .= "<span>";
		$html_out .= ($this->page > 1 || true) ? "<a href=\"".$this->php_self."?_page=".$prev."".$_link."\" title=\"Go Page ".$prev."\" target=\"".$this->page_target."\">Prev</a>" : "<span>Prev</span>";
		$html_out .= "</span>";
		
		if($this->page > ceil($_page_limit * 0.5)) {
			$html_out .= "<span><a href=\"".$this->php_self."?_page=1".$_link."\" title=\"Go Page 1\" target=\"".$this->page_target."\">1</a></span>";
			$html_out .= "<span><a href=\"".$this->php_self."?_page=".$prev_prev."".$_link."\" title=\"Go Page ".$prev_prev."\" target=\"".$this->page_target."\">...</a></span>";
		}
		for($i = $start; $i <= $end; $i++) {
			$html_out .= ($this->page == $i) ? "<span><span>".$i."</span></span>" : "<span><a href=\"".$this->php_self."?_page=".$i."".$_link."\" title=\"Go Page ".$i."\" target=\"".$this->page_target."\">".$i."</a></span>";
		}
		if($this->page_total - $this->page > floor($_page_limit * 0.5)) {
			$html_out .= "<span><a href=\"".$this->php_self."?_page=".$next_next."".$_link."\" title=\"Go Page ".$next_next."\" target=\"".$this->page_target."\">...</a></span>";
			$html_out .= "<span><a href=\"".$this->php_self."?_page=".$this->page_total."".$_link."\" title=\"Go Page ".$this->page_total."\" target=\"".$this->page_target."\">".$this->page_total."</a></span>";
		}
		
		$html_out .= "<span>";
		$html_out .= ($this->page < $this->page_total || true) ? "<a href=\"".$this->php_self."?_page=".$next."".$_link."\" title=\"Go Page ".$next."\" target=\"".$this->page_target."\">Next</a>" : "<span>Next</span>";
		$html_out .= "</span>";
		
		$html_out .= "</div>";
		
		return $html_out;
	}
	
	#########################################
	##### SQL Á¢¼Ó ¹× ÀÔ/Ãâ·Â °ü·Ã ÇÔ¼ö #####
	#########################################
	
	// DB Connect
	function Db_Conn($_type = "mysql", $_host = "localhost", $_user = "root", $_pass = "", $_db_name = "") {
		$this->sqlType = $_type;
		$this->host = $_host;
		$this->user = $_user;
		$this->pass = $_pass;
		$this->db_name = $_db_name;
		
		switch($this->sqlType) {
			case "mssql":
				$this->dbCon = @mssql_connect($_host, $_user, $_pass);
				if(!$this->dbCon) return "db_no_connect";
				if(@mssql_select_db($_db_name, $this->dbCon)) return "db_no_find";
			break;
			default:
				$this->dbCon = @mysql_connect($_host, $_user, $_pass);
				if(!$this->dbCon) return "db_no_connect";
				if(@mysql_select_db($_db_name, $this->dbCon)) return "db_no_find";
		}
		return "";
	}
	
	// Query ·Ñ Result °ª Ãâ·Â
	function Query($_query) {
		switch($this->sqlType) {
			case "mssql":
				return $_query ? @mssql_query($_query) : "";
			break;
			default:
				return $_query ? @mysql_query($_query) : "";
		}
	}
	
	// Query¸¦ ÀÌ¿ëÇÏ¿© ¹Ù·Î ÁÙ¼ö¸¦ »Ì¾Æ ³½´Ù.
	function Query_Row_Num($_query) {
		return $_query ? $this->Result_Row_Num($this->Query($_query)) : 0;
	}
	
	// ResultµÈ °ªÀ» ÀÌ¿ëÇÏ¿© ¹Ù·Î ÁÙ¼ö¸¦ »Ì¾Æ ³½´Ù.
	function Result_Row_Num($_result) {
		switch($this->sqlType) {
			case "mssql":
				return $_result ? @mssql_num_rows($_result) : 0;
			break;
			default:
				return $_result ? @mysql_num_rows($_result) : 0;
		}
	}
	
	// Result °ªÀ¸·Î Array Çü½ÄÀÇ Select °ª Ãâ·Â
	function Fetch_Array($_result) {
		switch($this->sqlType) {
			case "mssql":
				return $_result ? @mssql_fetch_array($_result) : "";
			break;
			default:
				return $_result ? @mysql_fetch_array($_result) : "";
		}
	}
	
	// Result °ªÀ¸·Î Object Çü½ÄÀÇ Select °ª Ãâ·Â
	function Fetch_Object($_result) {
		switch($this->sqlType) {
			case "mssql":
				return $_result ? @mssql_fetch_object($_result) : "";
			break;
			default:
				return $_result ? @mysql_fetch_object($_result) : "";
		}
	}
	
	// Äõ¸®·Î Array Çü½ÄÀ¸·Î Select °ª Ãâ·Â
	function Fetch_Array_Query($_query) {
		return $this->Fetch_Array($this->Query($_query));
	}
	
	// Äõ¸®·Î Object Çü½ÄÀ¸·Î Select °ª Ãâ·Â
	function Fetch_Object_Query($_query) {
		return $this->Fetch_Object($this->Query($_query));
	}
	
	#########################################
	##### ÀÏ¹ÝÀûÀÎ ÇÔ¼ö Á¤¸® ################
	#########################################
	
	// ÀÌ¸ÞÀÏ ¼­ºñ½º ÇÔ¼ö
	function EmailService($_form, $_send_to, $_title, $_content, $_fileForm = "/include/html/email_form.html") {
		global $_SERVER;
		
		if($_send_to->to) {
			$_to = $_send_to->to;
			$_cc = $_send_to->cc;
			$_bcc = $_send_to->bcc;
		} else {
			$_to = $_send_to;
			$_cc = "";
			$_bcc = "";
		}
		
		//header("Content-Type: text/html; charset=UTF-8");
		
		$reg_date = time();
	
		if(!$_form->name) $_form->name = "°ü¸®ÀÚ";
		if(!$_form->email) $_form->email = "webmaster@".$_SERVER['HTTP_HOST'];
		
		$count = sizeof($_to);
		
		if($count > 0) {
			$to_data = $_to[0]->name."<".$_to[0]->email.">\r\n";
			for($i = 1; $i < $count; $i++) {
				$to_data .= ",".$_to[$i]->name."<".$_to[$i]->email.">";
			}
		}
		
		$add = "content-Type: text/html; charset=euc-kr\n";
		$add .= "From: ".$_form->name." <".$_form->email.">\r\n";
		
		if($_cc) {
			if(count($_cc) > 1) {
				$add .= "Cc: ".$_cc[0]->name." <".$_cc[0]->email.">";
				for($i = 1; $i < count($_cc);$i++) {
					$_e = $_cc[$i];
					$add .= ",".$_e->name." <".$_e->email.">";
				}
				$add .= "\r\n";
			} else {
				$add .= "Cc: ".$_cc->name." <".$_cc->email.">\r\n";
			}
		}
		if($_bcc) {
			if(count($_bcc) > 1) {
				$add .= "Bcc: ".$_bcc[0]->name." <".$_bcc[0]->email.">";
				for($i = 1; $i < count($_bcc);$i++) {
					$_e = $_bcc[$i];
					$add .= ",".$_e->name." <".$_e->email.">";
				}
				$add .= "\r\n";
			} else {
				$add .= "Bcc: ".$_bcc->name." <".$_bcc->email.">\r\n";
			}
		}
		$time_date = date("Y³âm¿ùdÀÏ H:i", $reg_date);
		//$content = nl2br($_content);
		$title = $_title;
	
		// ÀÌ¸ÞÀÏ ÆûÀÌ ÀÖÀ¸¸é...
		$formFile = $_SERVER['DOCUMENT_ROOT']."/".$_fileForm;
		//return file_exists($formFile);
		$text_mail = "";
		if(file_exists($formFile)) {
			$emailext = fopen($formFile,"r");
			$emailfilesize = filesize($formFile);
			$text_mail = fread($emailext,$emailfilesize);
			$text_mail = eregi_replace("__email_title__", $title, $text_mail);
			if(sizeof($_content) == 1) {
				$text_mail = eregi_replace("__email_content_1__", $_content, $text_mail);
			} else {
				for($i = 1; $i <= sizeof($_content); $i++) {
					$text_mail = eregi_replace("__email_content_".$i."__", $_content[$i], $text_mail);
				}
			}
			fclose($emailext);
		} else {
			$text_mail = $title;
			if(sizeof($_content) == 1) {
				$text_mail .= "<br>".$_content;
			} else {
				for($i = 1; $i <= sizeof($_content); $i++) {
					$text_mail .= "<br>".$_content[$i];
				}
			}
		}
	
		$body = $text_mail."\r\n\r\n";
		//return $add;
	
		// ¸ÞÀÏ Àü¼ÛÇÏ±â
		if(@mail($to_data, $title, $body, $add )) {
			return "true".$add;
		} else {
			return "false";
		}
	}
		
	// ÅØ½ºÆ® Æ¯Á¤¸ð¾çÀ¸·Î ¸¸µé¾î ¹ÝÈ¯ÇÏ´Â ÇÔ¼ö
	function Text_Replace($str, $start_index = 0, $length = "", $division = "*") {
		$len = strlen($str);
		if(!$length) $length = $len;
		$out = "";
		$j = $start_index + $length;
		for($i = 0; $i < $len; $i++) {
			if($i >= $start_index && $i < $j) {
				$out .= "*";
			} else {
				$out .= substr($str, $i, 1);
			}
		}
		return $out;
	}
	
	// ÆÄÀÏ È®ÀåÀÚ ÃßÃâ
	function File_Ext($_file) {
		return $this->Split_Export($_file, ".");
	}
	
	// ±¸ºÐÀÚ·Î ÃßÃâÇÏ±â
	function Split_Export($_str, $division = ".") {
		return substr(strrchr($_str, $division), 1);
	}
	
	// ¸¶Áö¸· µð·ºÅä¸®¸í ÃßÃâ
	function Dir_End($_dir) {
		return substr(strrchr($_dir,"/"),1);
	}

	// ±¸ºÐÀÚ ³ª´²¼­ ÀÎµ¦½ºº° Ãâ·Â ÇÔ¼ö
	function Get_Split($str, $idx = 0, $division = "-") {
		$item = split($division, $str);
		return $item[$idx];
	}
	
	// ±¸ºÐÀÚ ³ª´²¼­ Å°¿öµå¿Í ºñ±³ÇÏ¿© true È¤Àº false¸¦ Ãâ·ÂÇØÁÖ´Â ÇÔ¼ö
	function Split_Check($str, $key, $division = ",") {
		$item = split($division, $str);
		$count = sizeof($item);
		for($i = 0; $i < $count; $i++) {
			if($item[$i] == $key) return true;
		}
		return false;
	}
	
	//base 64

	function Base64($code, $str) {
		switch($code) {
			case "encode":
				return base64_encode($str);
			break;
			case "decode":
				return base64_decode($str);
			break;
		}
	}
	
	// ¹è¿­ Áß °ªÀÌ °°Àº°Ô ÀÖ´ÂÁö È®ÀÎ ÈÄ true È¤Àº false·Î ¹ÝÈ¯
	function Array_Check($arr, $key, $type = "boolean") {
		$count = sizeof($arr);
		for($i = 0; $i < $count; $i++) {
			if($arr[$i] == $key) {
				switch($type) {
					case "string":
						return $arr[$i];
					break;
					default:
						return true;
				}
			}
		}
		switch($type) {
			case "string":
				return "";
			break;
			default:
				return false;
		}
	}
	
	#########################################
	##### YHBoard °ü·Ã ÇÔ¼ö #################
	#########################################
	
	function Out_Board($id = "", $id = "", $class = "", $limit = 5, $ct = "", $field = "") {
		global $_REQUEST, $_SERVER, $_p_pattern, $_rewrite_mod, $PHP_SELF;
		
		//echo $_config->yhb_start_url."/".$id;
		
		$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern;
		$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
		if($query_string) $query_string = "&".$query_string;
		
		$_field = $field ? $field : "
			yhb_number
			, yhb_title
			, yhb_date
			, yhb_board_name
			, yhb_group_no
		";
		
		$query_set = $this->Get_List_Query($id, $ct, $field);
		$query = $query_set->query;
		$order = $query_set->order." limit 0, ".$limit;
		
		?><ul<?=$id ? " id=\"".$id."\"" : ""?><?=$class ? " class=\"".$class."\"" : ""?>><?
		$result = $this->Query($query.$order);
		while ($bb = $this->Fetch_Object($result)) {
			$_config = $this->Get_Board($bb->yhb_board_name);
			$_group = $this->Get_Group($bb->yhb_group_no);
			?><li><a href="<?=($_rewrite_mod) ? "/yh/".$bb->yhb_board_name."/".$bb->yhb_number : $_config->yhb_start_url."?".$query_string."&_no=".$bb->yhb_number?>"><?=$bb->yhb_title?></a></li><?
		}
		?></ul><?
		//echo $query.$order;
	}
	
	function Out_Board_Get($id = "", $limit = 5, $ct = "", $field = "") {
		global $_REQUEST, $_SERVER, $_p_pattern, $_rewrite_mod, $PHP_SELF;
		
		//echo $_config->yhb_start_url."/".$id;
		
		$p = "^[&]|(&)*_no=".$_p_pattern."|(&)*_module=".$_p_pattern;
		$query_string = eregi_replace("^&", "", eregi_replace($p, "", $_SERVER['QUERY_STRING']));
		if($query_string) $query_string = "&".$query_string;
		
		$_field = $field ? $field : "
			yhb_number
			, yhb_title
			, yhb_date
			, yhb_board_name
			, yhb_group_no
		";
		
		$query_set = $this->Get_List_Query($id, $ct, $field);
		$query = $query_set->query;
		$order = $query_set->order." limit 0, ".$limit;
		
		$result = $this->Query($query.$order);
		$_bb = array();
		while ($bb = $this->Fetch_Object($result)) {
			$_config = $this->Get_Board($bb->yhb_board_name);
			$_group = $this->Get_Group($bb->yhb_group_no);
			array_push($_bb, $bb);
		}
		return $_bb;
		//echo $query.$order;
	}
	
	function Get_List_Query($id = "", $ct = "", $field = "", $table = "yh_board", $member = "", $_where = "") {
		global $_REQUEST, $_SERVER, $_member, $_config;
		
		if($member) $_member = $member; // $member°ªÀÌ ÀÖÀ¸¸é Àû¿ë
		if($id) $_REQUEST["_id"] = $id; // $id °ªÀÌ ÀÖÀ¸¸é Àû¿ë
		if($ct) $_REQUEST["_ct"] = $ct; // $ct °ªÀÌ ÀÖÀ¸¸é Àû¿ë
		
		if($_REQUEST["_no"]) $_result = $this->Fetch_Object_Query("select yhb_number_up from yh_board where yhb_number = '".$_REQUEST["_no"]."'");
		
		$id_arr = split(",", $_REQUEST["_id"]);
		$id_count = sizeof($id_arr);
		$where = " where yhb_board_name != ''";
		$_ids = "(";
		if($id_count == 1) {
			$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
			$_group = $this->Get_Group($_config->yhb_group_no);
		}
		
		// list °Ô½Ã¹° °¡Á®¿À±â
		for($i = 0; $i < $id_count; $i++) {
			$_ids .= $i == 0 ? "yhb_board_name = '".trim($id_arr[$i])."'" : " OR yhb_board_name = '".trim($id_arr[$i])."'";
		}
		$_ids .= ")";
		
		$where .= " AND ".$_ids;
		// °ü¸®ÀÚ 1:1 °Ô½ÃÆÇ¿¡ ´ëÇÑ Á¶°Ç
		if($_group->yhb_use_agreement == 1 || $_config->yhb_use_agreement == 1) {
			$where .= " AND (yhb_check = 1 OR ";
			$where .= ($_member->yhb_number) ? "yhb_id = '".$_member->yhb_id."' AND yhb_pass='".$_member->yhb_pass."'))" : "yhb_ip = '".$_SERVER['REMOTE_ADDR']."')";
		}
		
		// Ä«Å×°í¸®°ªÀÌ ÀÖÀ»¶§
		if($_REQUEST["_ct"]) {
			$old_ct = $_REQUEST["_ct"] - 10000000;
			// OR yhb_category = '".$old_ct."'
			$where .= " AND ((yhb_category = '".$_REQUEST["_ct"]."' OR yhb_category = '') OR yhb_notice = '1')";
		}
	
		// °ü¸®ÀÚ ±Û Ç¥½Ã¿©ºÎ
		if($_config->yhb_use_notice_admin == 1) $where .= " OR yhb_board_name = 'admin'";
	
		// 1:1°Ô½ÃÆÇÀÏ°æ¿ì
		if($_config->yhb_use_member == 1) $where .= " AND (yhb_view_member = '".$_member->yhb_mysign."' OR yhb_mysign = '".$_member->yhb_mysign."' OR yhb_view_member = '')";
		
		// ´ä±Ûµé¸¸ Á¤·ÄÇÒ¶§
		if($_REQUEST["_no"] && $_config->yhb_use_reply_list == 1 && $_config->yhb_use_list != 1) $where .= " AND yhb_number_up = '".$_result->yhb_number_up."'";
	
		// °Ë»ö¾î·Î °Ë»öÇÏ±â
		if($_REQUEST["_search"]) {
			$where .= "AND (";
			
			$sArr = split("/", $_REQUEST["_search_t"]);
			$sCount = sizeof($sArr);
			for($si = 0; $si < $sCount; $si++) {
				$where .= $si > 0 ? " OR" : "";
				$where .= $sArr[$si]." like '%".$_REQUEST["search"]."%'";
			}
			$where .= ")";
		}
		
		if($_where) {
			$where .= $_where;
		}
		
		//$where .= " AND (yhb_number = board_no && board_no = '')";
	
		$s_field = $field ? $field : "
		yhb_number
		, yhb_number_rows
		, yhb_board_name
		, yhb_group_no
		, yhb_title
		, yhb_name
		, yhb_html
		, yhb_content
		, yhb_secret
		, yhb_notice
		, yhb_reg_date
		, yhb_hit
		, yhb_ip
		, yhb_vote
		";
		
		$out->query = "select ".$s_field." from ".$table.$where;
		$out->order = " ORDER BY yhb_notice DESC, yhb_number_up DESC, yhb_number_rows ASC";
		
		return $out;
	}

	function Image_Resize($url, $wsize = 110, $hsize, $type = "auto") {
		if(!file_exists($url)) {
			return false;
		}
		$file_info = @GetImageSize($url);
		$info->width = $file_info[0];
		$info->height = $file_info[1];
		
		if($type != "width" && $type != "height") {
			if($info->width > $info->height) {
				$type = "width";
			} else if($info->height > $info->width) {
				$type = "height";
			}
		}
		
		switch($type) {
			case "width":
				$size = $wsize;
				if($info->width > $size) {
					$out->width = $size;
					$scale = $info->width / $size;
					$out->height = ceil($info->height / $scale);
				} else {
					$out->width = $info->width;
					$out->height = $info->height;
				}
			break;
			case "height":
				$size = ($hsize) ? $hsize : $wsize;
				if($info->height > $size) {
					$out->height = $size;
					$scale = $info->height / $size;
					$out->width = ceil($info->width / $scale);
				} else {
					$out->height = $info->height;
					$out->width = $info->width;
				}
			break;
			default:
		}
		return $out;
	}

	// ÄÚµå ¸Æ½º°ª ±¸ÇÏ±â
	function TableMax($_tabne_name, $_field, $_start_number = 10000001, $_where = "") {
		global $_LhDb;
		$_start_number = $_start_number + 0;
		$query = "select max(".$_field.") as count from ".$_tabne_name;
		if($_where) $query .= " where ".$_where;
		$object = $_LhDb->Fetch_Object_Query($query);
		return ($object->count >= $_start_number) ? $object->count + 1 : $_start_number;
	}
	
	function Old_File_Converter($bb) {
		global $_SERVER;
		
		if($bb) {
		
			$file_names = split("\*", $bb->yhb_file);
			$count = sizeof($file_names);
			
			if($count > 0 && trim($bb->yhb_file)) {
				
				/**
				 * ³»¿ë : ÀÌÀü ¹öÀü ÆÄÀÏ ·Îµå°¡ ÇÊ¿äÇÒ ¶§ »ç¿ë
				 * ÀÛ¼ºÀÚ : Áø¿µÈ£(reghoya@gmail.com)
				 * ÀÛ¼ºÀÏ : 2013. 01. 11
				 */
				$file_sizes = split("\*", $bb->yhb_sizes);
				$file_downs = split("\*", $bb->yhb_download);
				$field = "seq
					, group_no
					, board_no
					, board_name
					, f_name
					, s_name
					, f_size
					, f_ext
					, f_download
					, f_order
				";
				for($j = 0; $j < $count; $j++) {
					$f_name = $file_names[$j];
					$f_size = $file_sizes[$j];
					
					$file_url = "/yhboard/files/files".$bb->yhb_reg_date."".$i.".".$this->File_Ext($f_name);
					$f_save = "files".$bb->yhb_reg_date.($j).".".$this->File_ext($f_name);
					$url = _lh_yhyh_web."/upload/".$f_save;
					
					if(file_exists($_SERVER['DOCUMENT_ROOT'].$file_url)) {
						rename($_SERVER['DOCUMENT_ROOT'].$file_url, $_SERVER['DOCUMENT_ROOT'].$url);
					}
					
					if(trim($f_name)) {
						$_idx = $this->TableMax("yh_file", "seq", "1", "");
						
						$values = "'".$_idx."'
							, '".$bb->yhb_group_no."'
							, '".$bb->yhb_number."'
							, '".$bb->yhb_board_name."'
							, '".$f_name."'
							, '".$f_save."'
							, '".$f_size."'
							, '".$this->File_ext($f_name)."'
							, '".$file_downs[$j]."'
							, '".($j + 1)."'
						";
						
						$query = "insert into yh_file(".$field.") values(".$values.")";
						$this->Query($query);
					}
				}
				$query = "update yh_board set yhb_file = '', yhb_sizes = '', yhb_download = '' where yhb_number = '".$bb->yhb_number."'";
				$this->Query($query);
				
			}
		}
	}
	
	function Return_Url_Set($url, $type = "list") {
		global $_SESSION, $_SERVER;
		
		//if(eregi("_module=logout|_module=login", $url)) return;
		
		$_SESSION["yh_return_url_".$type] = $url;
	}
	
	function Return_Url_Get($type = "list") {
		global $_SESSION, $_SERVER;
		return ($_SESSION["yh_return_url_".$type]) ? $_SESSION["yh_return_url_".$type] : "/";
	}
	
	function Get_Admin($member = "") {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		$_member = ($member) ? $member : $this->Get_Member();
		//if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		//if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		switch($_member->yhb_admin) {
			case "1":
				return "g";
			break;
			case "2":
				return "s";
			break;
			default:
				$arr = split(",", $_member->yhb_board_name);
				$count = sizeof($arr);
				for($i = 0; $i < $count; $i++) {
					if(trim($arr[$i]) != "" && trim($arr[$i]) == $_config->yhb_name) {
						return "b";
					}
				}
				
		}
		return "";
	}
	
	function Grant_List() {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		$out = "";
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$_member->yhb_level) $_member->yhb_level = 10;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		if(!$this->Get_Admin()) {
			if($_config->yhb_grant_list < 10 && !$_member->yhb_number) {
				$out->message = "È¸¿ø¿¡°Ô¸¸ ¿­¶÷ÇÒ ¼ö ÀÖ´Â ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.<br>È¸¿øÀÏ °æ¿ì ·Î±×ÀÎ ÈÄ ÀÌ¿ëÇØÁÖ½Ê½Ã¿À.";
				$out->type = "message";
				$out->button->login = true;
				$out->button->back = true;
				
				return $out;
			}
			
			if($_config->yhb_grant_list < $_member->yhb_level) {
				$out->message = "¸ñ·ÏÀ» ¿­¶÷ÇÏ½Ç ±ÇÇÑÀÌ ¾ø½À´Ï´Ù.";
				$out->type = "message";
				$out->button->back = true;
				
				return $out;
			}
		}
		
		return "";
	}
	
	function Grant_View($bb = "") {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		$out = "";
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$_member->yhb_level) $_member->yhb_level = 10;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		if(!$this->Get_Admin()) {
			if($_config->yhb_grant_view < 10 && !$_member->yhb_number) {
				$out->message = "È¸¿ø¿¡°Ô¸¸ ±ÛÀ» º¼¼ö ÀÖ´Â ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.<br>È¸¿øÀÏ °æ¿ì ·Î±×ÀÎ ÈÄ ÀÌ¿ëÇØÁÖ½Ê½Ã¿À.";
				$out->type = "message_login";
				$out->button->login = true;
				$out->button->list = true;
				
				return $out;
			}
			
			if($_config->yhb_grant_view < $_member->yhb_level) {
				$out->message = "±ÛÀ» º¼¼ö ÀÖ´Â ±ÇÇÑÀÌ ¾ø½À´Ï´Ù.";
				$out->type = "message";
				$out->button->list = true;
				
				return $out;
			}
		}
		
		return "";
	}
	
	function Grant_Write($bb = "") {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		$out = "";
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$_member->yhb_level) $_member->yhb_level = 10;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		if(!$this->Get_Admin()) {
			if($_config->yhb_admin_write == 1 && !$_member->yhb_number) {
				$out->message = "±Û µî·ÏÀº °ü¸®ÀÚ¿¡°Ô¸¸ ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.<br>¿î¿µÀÚÀÏ °æ¿ì ·Î±×ÀÎ ÈÄ ÀÌ¿ëÇØÁÖ½Ê½Ã¿À.";
				$out->type = "message_login";
				$out->button->login = true;
				$out->button->back = true;
				
				return $out;
			}

			if($_config->yhb_admin_write == 1 && $_member->yhb_number) {
				$out->message = "±Û µî·ÏÀº °ü¸®ÀÚ¿¡°Ô¸¸ ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.";
				$out->type = "message_login";
				$out->button->back = true;
				
				return $out;
			}

			if($_config->yhb_grant_write < 10 && !$_member->yhb_number) {
				$out->message = "È¸¿ø¿¡°Ô¸¸ ±Û µî·Ï ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.<br>È¸¿øÀÏ °æ¿ì ·Î±×ÀÎ ÈÄ ÀÌ¿ëÇØÁÖ½Ê½Ã¿À.";
				$out->type = "message_login";
				$out->button->login = true;
				$out->button->back = true;
				
				return $out;
			}
			
			if($_config->yhb_grant_write < $_member->yhb_level) {
				$out->message = "È¸¿ø¿¡°Ô´Â ±Û µî·Ï ±ÇÇÑÀÌ ¾ø½À´Ï´Ù.";
				$out->type = "message";
				$out->button->list = true;
				
				return $out;
			}
		}
		
		return "";
	}
	
	function Grant_Reply($bb = "") {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		$out = "";
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$_member->yhb_level) $_member->yhb_level = 10;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		if(!$this->Get_Admin()) {
			if($_config->yhb_admin_reply == 1 && !$_member->yhb_number) {
				$out->message = "´äº¯ ±Û µî·ÏÀº °ü¸®ÀÚ¿¡°Ô¸¸ ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.<br>¿î¿µÀÚÀÏ °æ¿ì ·Î±×ÀÎ ÈÄ ÀÌ¿ëÇØÁÖ½Ê½Ã¿À.";
				$out->type = "message_login";
				$out->button->login = true;
				$out->button->back = true;
				
				return $out;
			}

			if($_config->yhb_admin_reply == 1 && $_member->yhb_number) {
				$out->message = "´äº¯ ±Û µî·ÏÀº °ü¸®ÀÚ¿¡°Ô¸¸ ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.";
				$out->type = "message_login";
				$out->button->back = true;
				
				return $out;
			}

			if($_config->yhb_grant_reply < 10 && !$_member->yhb_number) {
				$out->message = "È¸¿ø¿¡°Ô¸¸ ´äº¯ ±Û µî·Ï ±ÇÇÑÀÌ ÀÖ½À´Ï´Ù.<br>È¸¿øÀÏ °æ¿ì ·Î±×ÀÎ ÈÄ ÀÌ¿ëÇØÁÖ½Ê½Ã¿À.";
				$out->type = "message_login";
				$out->button->login = true;
				$out->button->back = true;
				
				return $out;
			}
			
			if($_config->yhb_grant_reply < $_member->yhb_level) {
				$out->message = "È¸¿ø¿¡°Ô´Â ´äº¯ ±Û µî·Ï ±ÇÇÑÀÌ ¾ø½À´Ï´Ù.";
				$out->type = "message";
				$out->button->list = true;
				
				return $out;
			}
		}
		
		return "";
	}
	
	function Grant_Modify($bb) {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		$out = "";
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$_member->yhb_level) $_member->yhb_level = 10;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		if($this->Get_Admin()) {
			return "";
		}
		switch($this->Row_Member($bb)) {
			case "m":
				return "";
			break;
			case "b":
				$out->message = "´Ù¸¥ È¸¿øÀÇ ±ÛÀº ¼öÁ¤ÇÏ½Ç ¼ö ¾ø½À´Ï´Ù.";
				$out->type = "alert";
				
				return $out;
			break;
			default:
				$out->type = "pass_check";
				
				return $out;
		}
	
		return "";
	}
	
	function Grant_Delete($bb) {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		$out = "";
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$_member->yhb_level) $_member->yhb_level = 10;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		if($this->Get_Admin()) {
			return "";
		}
		switch($this->Row_Member($bb)) {
			case "m":
				return "";
			break;
			case "b":
				$out->message = "´Ù¸¥ È¸¿øÀÇ ±ÛÀº »èÁ¦ÇÏ½Ç ¼ö ¾ø½À´Ï´Ù.";
				$out->type = "alert";
				
				return $out;
			break;
			default:
				$out->type = "pass_check";
				
				return $out;
		}
	
		return "";
	}
	
	function Grant_Secret($bb) {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF, $_config;
		
		if(!$bb->yhb_secret) return "";
		
		if($_SESSION["yh_board_pass"]) {
			$query = "select yhb_number_up from yh_board where yhb_number = '".$bb->yhb_number."'";
			$bb = $this->Fetch_Object_Query($query);
			$query = "select yhb_number from yh_board where yhb_number_up = '".$bb->yhb_number_up."' AND yhb_board_pass = '".$_SESSION["yh_board_pass"]."'";
			
			if($this->Query_Row_Num($query) > 0) return "";
		}
		$out = "";
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$_member->yhb_level) $_member->yhb_level = 10;
		
		$_config = ($_config->yhb_number) ? $_config : $this->Get_Board($_REQUEST["_id"]);
		
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_config->yhb_group_no;
		
		if($this->Get_Admin()) {
			return "";
		}
		switch($this->Row_Member($bb)) {
			case "m":
				return "";
			break;
			case "b":
				return "";
			break;
			default:
				$out->message = "ºñ¹Ð±ÛÀÔ´Ï´Ù. ºñ¹Ð¹øÈ£¸¦ ÀÔ·ÂÇÏ¼¼¿ä.";
				return $out;
		}
	
		return "";
	}
	
	function Row_Member($bb) {
		global $_SESSION, $_SERVER, $_REQUEST, $PHP_SELF;
		
		$_member = $this->Get_Member();
		if(!$_REQUEST["_group"]) $_REQUEST["_group"] = $_member->yhb_group_no;
		
		if(!$bb->yhb_id) return "";
		
		if($_member->yhb_id == $bb->yhb_id && $_member->yhb_pass == $bb->yhb_pass) {
			return "m";
		} else {
			if($bb->yhb_id && $bb->yhb_pass) {
				return "b";
			} else {
				return "";
			}
		}
	}
	
	function Login_Check($id = "", $pass = "") {
		global $_SESSION;
		//$query = "select * from yh_member where yhb_id != '' AND yhb_id = '".$id."' AND yhb_pass != '' AND yhb_pass = '".$pass."'";
		$query_base64 = "select * from yh_member where yhb_id != '' AND yhb_id = '".$id."' AND yhb_pass != '' AND yhb_pass = '".$this->Base64("encode", $pass)."'";
		
		
		if($this->Query_Row_Num($query_base64) == 0) {
			return false;
		} else {
			$_member = $this->Fetch_Object_Query($query_base64);
			$this->Login($_member);
			return $_member;
		}
	}
	
	function Login($_MMmember)	{
		global $_SESSION, $_DB;
		$_SESSION[yh_user_id] = trim($_MMmember->yhb_id);
		$_SESSION[yh_user_pass] = trim($_MMmember->yhb_pass);
		$_SESSION[yh_user_no] = trim($_MMmember->yhb_number);
		$_SESSION[yh_user_group] = trim($_MMmember->yhb_group_no);
		$_SESSION[yh_user_admin] = trim($_MMmember->yhb_admin);
		$_SESSION[yh_user_name] = trim($_MMmember->yhb_name);
		$_SESSION[yh_user_level] = trim($_MMmember->yhb_level);
		$_SESSION[yh_user_mysign] = trim($_MMmember->yhb_mysign);
	}
	
	function Logout() {
		global $_SESSION;
	
		$_SESSION[yh_user_id] = "";
		$_SESSION[yh_user_pass] = "";
		$_SESSION[yh_user_no] = "";
		$_SESSION[yh_user_group] = "";
		$_SESSION[yh_user_admin] = "";
		$_SESSION[yh_user_level] = "";
		$_SESSION[yh_user_mysign] = "";
		session_destroy();
	}
	
	function Get_Member($no = "", $auto = true) {
		global $_SESSION, $_email_type;
		
		$_member = "";
		
		if($no) {
			$query = "select * from yh_member where yhb_number = '".$no."'";
			$_member = $this->Fetch_Object_Query($query);
		} else {
			if($auto) {
				$query = "select * from yh_member where yhb_id != '' AND yhb_id = '".$_SESSION[yh_user_id]."' AND yhb_pass != '' AND yhb_pass = '".$_SESSION[yh_user_pass]."'";
				$_member = $this->Fetch_Object_Query($query);
			}
		}
		if($_member->yhb_home_tel) {
			$arr = split("-", $_member->yhb_home_tel);
			if($arr[0] || $arr[1] || $arr[2]) {
				$_member->yhb_home_tel_f = $arr[0];
				$_member->yhb_home_tel_m = $arr[1];
				$_member->yhb_home_tel_l = $arr[2];
			}
		}
		if($_member->yhb_handphone) {
			$arr = array();
			$arr = split("-", $_member->yhb_handphone);
			if($arr[0] || $arr[1] || $arr[2]) {
				$_member->yhb_handphone_f = $arr[0];
				$_member->yhb_handphone_m = $arr[1];
				$_member->yhb_handphone_l = $arr[2];
			}
		}
		if($_member->yhb_fax) {
			$arr = array();
			$arr = split("-", $_member->yhb_fax);
			if($arr[0] || $arr[1] || $arr[2]) {
				$_member->yhb_fax_f = $arr[0];
				$_member->yhb_fax_m = $arr[1];
				$_member->yhb_fax_l = $arr[2];
			}
		}
		if($_member->yhb_office_tel) {
			$arr = array();
			$arr = split("-", $_member->yhb_office_tel);
			if($arr[0] || $arr[1] || $arr[2]) {
				$_member->yhb_office_tel_f = $arr[0];
				$_member->yhb_office_tel_m = $arr[1];
				$_member->yhb_office_tel_l = $arr[2];
			}
		}
		if($_member->yhb_school_tel) {
			$arr = array();
			$arr = split("-", $_member->yhb_school_tel);
			if($arr[0] || $arr[1] || $arr[2]) {
				$_member->yhb_school_tel_f = $arr[0];
				$_member->yhb_school_tel_m = $arr[1];
				$_member->yhb_school_tel_l = $arr[2];
			}
		}
		if($_member->yhb_office_charge_tel) {
			$arr = array();
			$arr = split("-", $_member->yhb_office_charge_tel);
			if($arr[0] || $arr[1] || $arr[2]) {
				$_member->yhb_office_charge_tel_f = $arr[0];
				$_member->yhb_office_charge_tel_m = $arr[1];
				$_member->yhb_office_charge_tel_l = $arr[2];
			}
		}
		if($_member->yhb_kook_no) {
			$arr = array();
			$arr = split("-", $_member->yhb_kook_no);
			if($arr[0] || $arr[1]) {
				$_member->yhb_kook_no_f = $arr[0];
				$_member->yhb_kook_no_l = $arr[1];
			}
		}
		if($_member->yhb_email) {
			$arr = array();
			$arr = split("@", $_member->yhb_email);
			if($arr[0] || $arr[1]) {
				$_member->yhb_email_f = $arr[0];
				if($this->Array_Check($_email_type, $arr[1], "boolean")) {
					$_member->yhb_email_m = "";
					$_member->yhb_email_l = $arr[1];
				} else {
					$_member->yhb_email_m = $arr[1];
					$_member->yhb_email_l = "direct";
				}
			}
		}
		if($_member->yhb_office_no) {
			$arr = array();
			$arr = split("-", $_member->yhb_office_no);
			if($arr[0] || $arr[1] || $arr[2]) {
				$_member->yhb_office_no_f = $arr[0];
				$_member->yhb_office_no_m = $arr[1];
				$_member->yhb_office_no_l = $arr[2];
			}
		}
		if($_member->yhb_home_post) {
			$arr = array();
			$arr = split("-", $_member->yhb_home_post);
			if($arr[0] || $arr[1]) {
				$_member->yhb_home_post_f = $arr[0];
				$_member->yhb_home_post_l = $arr[1];
			}
		}
		if($_member->yhb_office_post) {
			$arr = array();
			$arr = split("-", $_member->yhb_office_post);
			if($arr[0] || $arr[1]) {
				$_member->yhb_office_post_f = $arr[0];
				$_member->yhb_office_post_l = $arr[1];
			}
		}
		if($_member->yhb_school_post) {
			$arr = array();
			$arr = split("-", $_member->yhb_school_post);
			if($arr[0] || $arr[1]) {
				$_member->yhb_school_post_f = $arr[0];
				$_member->yhb_school_post_l = $arr[1];
			}
		}
		$query = "select yhb_name from yh_group where yhb_number = '".$_member->yhb_group_no."'";
		$_group = $this->Fetch_Object_Query($query);
		$_member->yhb_group_name = $_group->yhb_name;
		return $_member;
	}
	
	function Get_Group($no = 1) {
		$query = "select * from yh_group where yhb_number = '".$no."'";
		$_group = $this->Fetch_Object_Query($query);
		
		$_group->yhb_point_limit_1 = $this->Get_Split($_group->yhb_point_limit, 0, "\*");
		$_group->yhb_point_limit_2 = $this->Get_Split($_group->yhb_point_limit, 1, "\*");
		$_group->yhb_point_limit_3 = $this->Get_Split($_group->yhb_point_limit, 2, "\*");
		$_group->yhb_point_limit_4 = $this->Get_Split($_group->yhb_point_limit, 3, "\*");
		$_group->yhb_point_limit_5 = $this->Get_Split($_group->yhb_point_limit, 4, "\*");
		$_group->yhb_point_limit_6 = $this->Get_Split($_group->yhb_point_limit, 5, "\*");
		$_group->yhb_point_limit_7 = $this->Get_Split($_group->yhb_point_limit, 6, "\*");
		$_group->yhb_point_limit_8 = $this->Get_Split($_group->yhb_point_limit, 7, "\*");
		$_group->yhb_point_limit_9 = $this->Get_Split($_group->yhb_point_limit, 8, "\*");
		return $_group;
	}
	
	function Get_Board_Id($page_name = "") {
		global $PHP_SELF;
		$_page_name = $page_name ? $page_name : $PHP_SELF;
		$query = "select yhb_name from yh_config_board where yhb_start_url = '".$_page_name."'";
		$_config = $this->Fetch_Object_Query($query);
		return $_config->yhb_name;
	}
	
	function Get_Board($id = "") {
		$query_check = "select yhb_number from yh_config_board where yhb_name = '".$id."'";
		//echo "<br>Get_Board";
		if($this->Query_Row_Num($query_check) == 0) {
			$query = "SELECT * FROM yh_board_skin AS bs, yh_config_board AS bc WHERE board_id = '".$id."' AND target_id = yhb_name GROUP BY target_id";
			$_config = $this->Fetch_Object_Query($query);
			$_config->yhb_skin = $_config->board_skin;
			$_config->yhb_page = $_config->page;
			$_config->yhb_rows = $_config->rows;
		} else {
			$query = "select * from yh_config_board where yhb_name = '".$id."'";
			$_config = $this->Fetch_Object_Query($query);
		}
		return $_config;
	}
	
	function Refresh($_time, $_page_url) {
		if($this->dbCon) @mysql_close($this->dbCon);
		?>
		<!DOCTYPE HTML>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta http-equiv='refresh' content="<?=($_time) ? $_time : 0?>;url=<?=$_page_url?>">
		<title>ÆäÀÌÁö ÀüÈ¯</title>
		</head>
		<body>
		</body>
		</html>
		<?
		exit();
	}
}

?>