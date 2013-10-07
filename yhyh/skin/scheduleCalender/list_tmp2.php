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
var _year = Number("<?=$_REQUEST["_year"] ? $_REQUEST["_year"] : date("Y")?>");
var _month = Number("<?=$_REQUEST["_month"] ? $_REQUEST["_month"] : date("n")?>");

$(window).load(function() {
	ListRowsEllipsis_title(".ellipsis_title");
	
	Schedule_Init(_year, _month);
	
	$(window).resize(Schedule_Resize);
	Schedule_Resize();
});

function Schedule_Resize() {
	var _schedule = $("#schedule_layout");
	var _container = $(".day_container", _schedule);
	var _calendar_header = $(".calendar_header > thead > tr");
	_day_width = $("> th:first", _calendar_header).outerWidth()
	var d_left = $("> th:first", _calendar_header).position().left;
	
	$("> div > div", _container).each(function(i) {
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

function Schedule_Move(type) {
	switch(type) {
		case "prev":
			// 이전달 데이터
			if(_month - 1 > 0) {
				m = _month - 1;
				y = _year;
			} else {
				m = 12;
				y = _year - 1;
			}
		break;
		case "next":
			// 다음달 데이터
			if(_month + 1 <= 12) {
				m = _month + 1;
				y = _year;
			} else {
				m = 1;
				y = _year + 1;
			}
		break;
	}
	Schedule_Init(y, m);
}
function Schedule_Init(year, month) {
	var c_date = new Date(year, month), month_n, month_p, year_n, year_p, total_day = { prev : 0 , today : 0 , next : 0 };
	var c_type = (c_date.getTime() >= (new Date(_year, _month)).getTime()) ? -1 : 1;
	//alert(c_date.getTime() + " : " + (new Date(_year, _month)).getTime() + ":" + _month + "/" + month);
	var _div_p, _div_t, _div_n;
	var div_m_top = 520, disabled_opacity = 0.5;
	var slide_duration = 500;
	var slide_ease = "easeInOutCubic";
	
	_month = month = Number(month);
	_year = year = Number(year);
	
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
	
	
	total_day.next = month_n == 2 ? (year_n % 4) ? 28 : 29 : _last_day_arr[month_n - 1];
	total_day.prev = month_p == 2 ? (year_p % 4) ? 28 : 29 : _last_day_arr[month_p - 1];
	total_day.today = month == 2 ? (year % 4) ? 28 : 29 : _last_day_arr[month - 1];
	
	var _schedule = $("#schedule_layout");
	if(_schedule.length == 0) {
		alert("아이디가 schedule_layout 된 태그가 존재하지 않습니다.");
		return;
	}
	
	var _container = $(".day_container", _schedule);
	
	var _calendar_header = $(".calendar_header > thead > tr");
	
	if(_day_width == 0) _day_width = $("> th:first", _calendar_header).outerWidth()
	var d_left = $("> th:first", _calendar_header).position().left;
	//alert(c_type);
	if(_container.children().length > 2) {
		$("> div", _container).each(function(i) {
			var move_top = 0;
			if(c_type < 0) {
				move_top = (Number($(this).data("week_count")) * _day_height * c_type) + $(this).position().top;
				if(i == _container.children().length - 2) {
					if(Number($(this).data("week_last")) == 6) {
						move_top -= _day_height;
					}
					_div_p = $(this);
				} else if(i == _container.children().length - 1) {
					_div_t = $(this);
					div_m_top = (Number($(this).data("week_count")) * _day_height) + $(this).position().top;
					move_top = 0				
				}
			} else {
				var prev_mc = $("> div:eq(" + (0) + ")", _container);
				if(i == 0) {
					_div_t = $(this);
					div_m_top = $(this).position().top;
					move_top = 0			
				} else if(i == 1) {
					_div_n = $(this);
					if(prev_mc.length > 0) {
						move_top = (Number(prev_mc.data("week_count")) * _day_height * c_type);
					}
					if(Number(prev_mc.data("week_last")) == 6) {
						move_top += _day_height;
					}
				} else if(i > 1) {
					if(prev_mc.length > 0) {
						move_top = (Number(prev_mc.data("week_count")) * _day_height * c_type);
					}
					if(Number(prev_mc.data("week_last")) == 6) {
						move_top += _day_height;
					}
					move_top += $(this).position().top;
				}
			}
			
			var move_opacity = (i == _container.children().length - 1) ? 1 : disabled_opacity;
			if(c_type > 0) {
				move_opacity = (i == 0) ? 1 : disabled_opacity;
			}
			
			
			$(this).stop().animate({
				top : move_top
				, opacity : move_opacity
			}, slide_duration, slide_ease, function() {
				if(c_type < 0) {
					if(i < _container.children().length - 2) {
						$(this).remove();
					}
				} else {
					if(i > 1) {
						$(this).remove();
					}
				}
			});
		});
	}
	
	var week_first = (new Date(year, month - 1, 1)).getDay();
	var week_last = (new Date(year, month - 1, total_day.today)).getDay();
	//alert(week_first);
	var Day_Init = function(y, m, d, target) {
		var last_day = m == 2 ? (y % 4) ? 28 : 29 : _last_day_arr[m - 1];
		var now_week_first = (new Date(y, m - 1, 1)).getDay();
		var now_week_last = (new Date(y, m - 1, last_day)).getDay();
		
		var d_date = new Date(y, m - 1, d);
		var row = Math.floor((d + now_week_first - 1) / 7);
		
		target.data({
			"week_count" : row
			, "week_first" : now_week_first
			, "week_last" : now_week_last
		});
		
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
		.addClass("day_div")
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
			, "height" : _day_height + "px" 
			, "top" : ((_day_height) * row) + "px"
			, "left" : left + "px"
		})
		.appendTo(target);
		
		var _day_p = $("<p/>")
		.addClass(day_type)
		.css({
			"width" : (_day.innerWidth() - 30) + "px"
		})
		.html(d > 9 ? d : "0" + d)
		.appendTo(_day);
		
		var _day_a = $("<a/>")
		.attr("title", y + "년 " + m + "월 " + d + "일")
		.hover(function() {
			var parent = $(this).parent();
			var day_type = parent.data("week_class");
			parent.find("> p").addClass(day_type + "_over");
		}, function() {
			var parent = $(this).parent();
			var day_type = parent.data("week_class");
			parent.find("> p").removeClass(day_type + "_over");
		})
		.css({
			"width" : (_day.innerWidth() - 10) + "px"
			, "height" : (_day.innerHeight() - 10) + "px"
		})
		.attr("href", "javascript:;")
		.append(
			$("<p/>").html(d > 9 ? d : "0" + d)
		)
		.appendTo(_day);
	};
	if(c_type < 0) {
		if(!_div_p) {
			_div_p = $("<div/>").stop().fadeTo(0, disabled_opacity).appendTo(_container).css({"z-index" : "1"});
			for(var d = 1; d <= total_day.prev; d++) {
				Day_Init(year_p, month_p, d, _div_p);
			}
			var div_p_top = Number(_div_p.data("week_count")) * _day_height * -1;
			if(week_first == 0) {
				div_p_top -= _day_height;
			}
			_div_p.css({"top": (div_p_top) + "px"});
		} else {
			_div_p.css({"z-index" : "1"});
		}
		
		if(!_div_t) {
			_div_t = $("<div/>").appendTo(_container).css({"top": "0px", "z-index" : "2"});
			for(var d = 1; d <= total_day.today; d++) {
				Day_Init(year, month, d, _div_t);
			}
			div_m_top = Number(_div_t.data("week_count")) * _day_height;
		} else {
			_div_t.css({"z-index" : "2"});
		}
		
		if(week_last == 6) {
			div_m_top += _day_height;
		}
		_div_n = $("<div/>").stop().fadeTo(0, disabled_opacity).appendTo(_container).css({"top": div_m_top + "px", "z-index" : "1"});
		
		for(var d = 1; d <= total_day.next; d++) {
			Day_Init(year_n, month_n, d, _div_n);
		}
		
		var div_n_move = (Number(_div_t.data("week_count")) * _day_height);
		if(week_last == 6) {
			div_n_move += _day_height;
		}
	
		_div_n.stop().animate({
			top : div_n_move
		}, slide_duration, slide_ease, function() {
		});
	} else {
		_div_n.css({"z-index" : "1"});
		_div_t.css({"z-index" : "2"});
		_div_p = $("<div/>").stop().fadeTo(0, disabled_opacity).appendTo(_container).css({"z-index" : "1"});
		
		for(var d = 1; d <= total_day.next; d++) {
			Day_Init(year_p, month_p, d, _div_p);
		}
		div_m_top -= (Number(_div_p.data("week_count")) * _day_height)
		if(week_first > 0) {
			div_m_top -= _day_height;
		}
		
		_div_p.css({"top": div_m_top + "px"});
		
		var div_p_move = (Number(_div_t.data("week_count")) * _day_height) * -1;
		if(week_first > 0) {
			div_p_move -= _day_height;
		}
	
		_div_p.stop().animate({
			top : div_p_move
		}, slide_duration, slide_ease, function() {
		});
		
	}
	
	_div_p.height(Number(_div_p.data("week_count")) * _day_height).click(function() { Schedule_Init(year_p, month_p) });
	_div_t.height(0).unbind("click");
	_div_n.height(Number(_div_n.data("week_count")) * _day_height).click(function() { Schedule_Init(year_n, month_n) });
	
	$("#schedule_layout").stop().animate({
		height : Number(_div_t.data("week_count") + 1) * _day_height
	}, slide_duration, slide_ease, function() {
		Schedule_Resize();
	});
	
}

function Grant_No_View(msg) {
	
	alert(msg);
}
</script>
<div class="yhyh_list">
	<div class="yhyh_list_header">
		<p class="numberFont">Article : <?=number_format($_total_rows)?></p>
		<a href="javascript:Schedule_Move('prev');">PREV</a>
		<a href="javascript:Schedule_Move('next');">NEXT</a>
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