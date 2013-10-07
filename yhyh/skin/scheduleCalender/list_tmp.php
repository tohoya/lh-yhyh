<?php
if($_solo_mode) { ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>게시판 리스트</title>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.min.js"></script>
</head>

<body>
<? } ?>
<link href="<?=_lh_yhyh_web?>/common/css/default.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/skin/scheduleCalender/css/default.css" rel="stylesheet" type="text/css">
<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/common.js"></script>
<script>
var _last_day_arr = new Array(31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var _month_arr = new Array("","01","02","03","04","05","06","07","08","09","10","11","12");
var _day_name = new Array('S','M','T','W','T','F','S');
var _day_name_kr = new Array('일','월','화','수','목','금','토');
var _day_width = 0;
var _day_height = 130;

$(window).load(function() {
	ListRowsEllipsis_title(".ellipsis_title");
	
	Schedule_Init("<?=$_REQUEST["_year"] ? $_REQUEST["_year"] : date("Y")?>", "<?=$_REQUEST["_month"] ? $_REQUEST["_month"] : date("n")?>");
	
	$(window).resize(Schedule_Resize);
	Schedule_Resize();
});

function Schedule_Resize() {
	var _schedule = $("#schedule_layout");
	var _container = $(".day_container", _schedule);
	var _calendar_header = $(".calendar_header > thead > tr");
	_day_width = $("> th:first", _calendar_header).outerWidth()
	var d_left = $("> th:first", _calendar_header).position().left;
	
	$("> div", _container).each(function(i) {
		var col = Number($(this).data("col"));
		var t_header = $("> th:eq(" + col + ")", _calendar_header);
		var w = t_header.outerWidth();
		var left = t_header.position().left;
		$(this).stop().css({
			width : w + "px"
			, left : (left - d_left) + "px"
		})
		$("> a", this).css({
			"width" : ($(this).innerWidth() - 10) + "px"
			, "height" : ($(this).innerHeight() - 10) + "px"
		});
		$("> p", this).css({
			"width" : ($(this).innerWidth() - 30) + "px"
		})
	});
}

function Schedule_Init(year, month) {
	var c_date = new Date(year, month), month_n, month_p, year_n, year_p, total_day = { prev : 0 , today : 0 , next : 0 };
	
	// 이전달 데이터
	if(month - 1 > 0) {
		month_p = month - 1;
		year_p = year;
	} else {
		month_p = 12;
		year_p = year - 1;
	}
	
	// 다음달 데이터
	if(month + 1 <= 12) {
		month_n = month + 1;
		year_n = year;
	} else {
		month_n = 1;
		year_n = year + 1;
	}
	
	total_day.next = month_n == 2 ? (year_n % 4) ? 29 : 28 : _last_day_arr[month_n - 1];
	total_day.prev = month_p == 2 ? (year_p % 4) ? 29 : 28 : _last_day_arr[month_p - 1];
	total_day.today = month == 2 ? (year % 4) ? 29 : 28 : _last_day_arr[month - 1];
	
	var _schedule = $("#schedule_layout");
	if(_schedule.length == 0) {
		alert("아이디가 schedule_layout 된 태그가 존재하지 않습니다.");
		return;
	}
	
	var _container = $(".day_container", _schedule);
	
	var _calendar_header = $(".calendar_header > thead > tr");
	
	if(_day_width == 0) _day_width = $("> th:first", _calendar_header).outerWidth()
	var d_left = $("> th:first", _calendar_header).position().left;
	
	$("> div", _container).each(function(i) {
		
	});
	
	var week_first = (new Date(year, month - 1, 1)).getDay();
	var week_last = (new Date(year, month - 1, total_day.today)).getDay();
	
	var Day_Init = function(y, m, day) {
		var d = day;
		if(day <= 0) {
			y = year_p;
			m = month_p;
			d = total_day.prev - day - 1;
		} else if(day > total_day.today) {
			y = year_n;
			m = month_n;
			d = day - total_day.today;
		}
		
		var d_date = new Date(y, m - 1, d);
		var row = (day <= 0) ? 0 : Math.floor((d - d_date.getDay()) / 7) + 1;
		
		if(day > total_day.today) {
			row = Math.floor((day - week_last) / 7);
		}
		
		var col = d_date.getDay();
		var t_header = $("> th:eq(" + col + ")", _calendar_header);
		var w = t_header.outerWidth();
		var left = t_header.position().left - d_left;
		var day_type = "normal";
		switch(col) {
			case 0:
				day_type = "sun";
			break;
			case 6:
				day_type = "sat";
			break;
		}
		
		var _day = $("<div/>")
		.data({
			"row" : row
			, "col" : col
			, "week_class" : day_type
			, "year" : y
			, "month" : m
			, "day" : d
		})
		.css({
			"width" : w + "px"
			, "top" : ((_day_height) * row) + "px"
			, "left" : left + "px"
		})
		.appendTo(_container);
		
		if(year == y && month == m) {
			_day.addClass("day_div");
		} else {
			_day.addClass("no_day_div");
		}
		
		
		var _day_p = $("<p/>")
		.addClass(day_type)
		.css({
			"width" : (_day.innerWidth() - 30) + "px"
		})
		.html(d > 9 ? d : "0" + d)
		.appendTo(_day);
		
		var _day_a = $("<a/>")
		.css({
			"width" : (_day.innerWidth() - 10) + "px"
			, "height" : (_day.innerHeight() - 10) + "px"
		})
		.attr("href", "#")
		.append(
			$("<p/>").html(d > 9 ? d : "0" + d)
		)
		.appendTo(_day);
		
		if(year == y && month == m) {
			_day_a.hover(function() {
				var parent = $(this).parent();
				var day_type = parent.data("week_class");
				parent.find("> p").addClass(day_type + "_over");
			}, function() {
				var parent = $(this).parent();
				var day_type = parent.data("week_class");
				parent.find("> p").removeClass(day_type + "_over");
			})
		}
	};
	
	for(var d = 1 - week_first; d <= total_day.today + (6 - week_last); d++) {
		Day_Init(year, month, d);
	}
	
	$("> div", _container).each(function(i) {
	});
}

function Grant_No_View(msg) {
	
	alert(msg);
}
</script>
<div class="yhyh_list">
	<div class="yhyh_list_header">
		<p class="numberFont">Article : <?=number_format($_total_rows)?></p>
		<div class="list_header_right">
			<? if($_member->yhb_number) { ?>
				<a href="<?=$_logout_link?>" class="a_button numberFont">Logout</a>
			<a href="<?=$_register_link?>&_auto=true" class="a_button numberFont">Info</a>
			<? } else { ?>
			<a href="<?=$_login_link?>&_returnType=return" class="a_button numberFont">Login</a>
			<a href="<?=$_register_link?>&_returnType=return" class="a_button numberFont">Join</a>
			<? } ?>
			<a href="<?=$_admin_link?>&_returnType=admin" class="a_button numberFont">Admin</a>
		</div>
	</div>
	<table class="calendar_header">
		<colgroup>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
		</colgroup>
		<thead>
			<tr>
				<th>일요일</th>
				<th>월요일</th>
				<th>화요일</th>
				<th>수요일</th>
				<th>목요일</th>
				<th>금요일</th>
				<th class="last">토요일</th>
			</tr>
		</thead>
	</table>
	<div id="schedule_layout">
		<div class="day_container">
			<!--div class="day_div">
				<p>25</p>
				<ul>
					<li>이런 문제가 있을까요?1</li>
					<li>이런 문제가 있을까요?2</li>
				</ul>
			</div-->
		</div>
	</div>
	<table>
		<colgroup>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
			<col width="auto"/>
		</colgroup>
		<tfoot>
			<tr>
				<td colspan="10" class="footer">
					<span class="yhyh_button_footer_left"><a href="<?=$_write_link?>" class="a_button">선택항목삭제</a></span>
					<span class="yhyh_button_footer_right"><a href="<?=$_write_link?>" class="a_button">글쓰기</a></span>
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<!--<a href="" class="a_button">다시보기</a>-->
</div>
<? if($_solo_mode) { ?>
</body>
</html>
<? } ?>