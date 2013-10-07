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
<? }
if(!$_REQUEST["_year"]) $_REQUEST["_year"] = date("Y");
if(!$_REQUEST["_month"]) $_REQUEST["_month"] = date("n");
?>
<link href="<?=_lh_yhyh_web?>/common/css/default.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/skin/scheduleCalender/css/default.css" rel="stylesheet" type="text/css">
<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.calender1st.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/common.js"></script>
<script>
var _last_day_arr = new Array(31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var _month_arr = new Array("","01","02","03","04","05","06","07","08","09","10","11","12");
var _day_name = new Array('S','M','T','W','T','F','S');
var _day_name_kr = new Array('일','월','화','수','목','금','토');
var _day_width = 0;
var _day_height = 130;
var _year = Number("<?=$_REQUEST["_year"]?>");
var _month = Number("<?=$_REQUEST["_month"]?>");

$(window).load(function() {
	ListRowsEllipsis_title(".ellipsis_title");
	
	Schedule_Init(_year, _month);
	
	$(window).resize(Schedule_Resize);
	Schedule_Resize();

	var sc = $("#schedule_layout").get(0);
	
	if(sc.attachEvent) {
		sc.attachEvent("onmousewheel", function(e) {
			Schedule_Move(e.wheelDelta > 0 ? "prev" : "next");
		}, false);
	} else {
		sc.addEventListener("DOMMouseScroll", function(e) {
			Schedule_Move(e.wheelDelta > 0 ? "prev" : "next");
		}, false);
		sc.onmousewheel = function(e) {
			Schedule_Move(e.wheelDelta > 0 ? "prev" : "next");
		}
	}
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
		var position_left = parseInt($("> a", this).css("left"));
		var position_top = parseInt($("> a", this).css("top"));
		
		$("> a", this).css({
			"width" : ($(this).innerWidth() - (position_left * 2)) + "px"
			, "height" : ($(this).innerHeight() - (position_top * 2)) + "px"
		});
		$("> p", this).css({
			"width" : ($(this).innerWidth() - 30) + "px"
		})
	});
}

function Schedule_Move(type) {
	var date_get = Date_Find_Get(_year, _month);
	switch(type) {
		case "prev":
			// 이전달 데이터
			m = date_get.m_p;
			y = date_get.y_p;
		break;
		case "next":
			// 다음달 데이터
			m = date_get.m_n;
			y = date_get.y_n;
		break;
	}
	Schedule_Init(y, m);
}

function Date_Find_Get(y, m) {
	var out = { y_p : y, m_p : m, y_n : y, m_n : m };
	if(m - 1 > 0) {
		out.m_p = m - 1;
		out.y_p = y;
	} else {
		out.m_p = 12;
		out.y_p = y - 1;
	}
	
	// 다음달 데이터
	if(m + 1 <= 12) {
		out.m_n = m + 1;
		out.y_n = y;
	} else {
		out.m_n = 1;
		out.y_n = y + 1;
	}
	return out;
}
function Schedule_Init(year, month) {
	var c_date = new Date(year, month), month_n, month_p, year_n, year_p, total_day = { prev : 0 , today : 0 , next : 0 };
	var c_type = (c_date.getTime() > (new Date(_year, _month)).getTime()) ? -1 : 1;
	if(c_date.getTime() == (new Date(_year, _month)).getTime()) {
		c_type = 0;
	}
	//alert(c_date.getTime() + " : " + (new Date(_year, _month)).getTime() + ":" + _month + "/" + month);
	var _div_p, _div_t, _div_n;
	var div_m_top = 520, disabled_opacity = 0.5;
	var slide_duration = 500;
	var slide_ease = "easeInOutCubic";
	
	var _month_text = $(".calendar_month");
	var _year_text = $(".calendar_year");
	
	$("> p", _month_text).each(function(i) {
		$(this).stop().animate({
			top : $(this).height() * c_type
			, opacity : 0.3
		}, slide_duration, slide_ease, function() {
			$(this).remove();
		});
	});
	$("<p/>").html(month)
	.css("top", c_type > 0 ? "-45px" : "45px")
	.fadeTo(0, 0.3)
	.appendTo(_month_text)
	.stop().animate({
		top : 0
		, opacity : 1
	}, slide_duration, slide_ease, function() {
	});
	
	if(_year != year) {
		$("> p", _year_text).each(function(i) {
			$(this).stop().animate({
				top : $(this).height() * c_type
				, opacity : 0.3
			}, slide_duration, slide_ease, function() {
				$(this).remove();
			});
		});
		$("<p/>").html(year)
		.css("top", c_type > 0 ? "-45px" : "45px")
		.fadeTo(0, 0.3)
		.appendTo(_year_text)
		.stop().animate({
			top : 0
			, opacity : 1
		}, slide_duration, slide_ease, function() {
		});
	}
	
	_month = month = Number(month);
	_year = year = Number(year);
	
	var date_get = Date_Find_Get(year, month);
	month_p = date_get.m_p;
	year_p = date_get.y_p;
	month_n = date_get.m_n;
	year_n = date_get.y_n;
	
	
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
	
	var week_first = (new Date(year, month - 1, 1)).getDay();
	var week_last = (new Date(year, month - 1, total_day.today)).getDay();
	//alert(week_first);
	var Day_Init = function(y, m, d, target) {
		var last_day = m == 2 ? (y % 4) ? 28 : 29 : _last_day_arr[m - 1];
		var now_week_first = (new Date(y, m - 1, 1)).getDay();
		var now_week_last = (new Date(y, m - 1, last_day)).getDay();
		var date_get = Date_Find_Get(Number(y), Number(m));
		var d_date = new Date(y, m - 1, d);
		var row = Math.floor((d + now_week_first - 1) / 7);
		
		target.data({
			"week_count" : row
			, "week_first" : now_week_first
			, "week_last" : now_week_last
			, "year" : y
			, "month" : m
			, "year_p" : date_get.y_p
			, "month_p" : date_get.m_p
			, "year_n" : date_get.y_n
			, "month_n" : date_get.m_n
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
		.attr("id", "calendar_day_" + y + "_" + m + "_" + d)
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
		.data({
			"row" : row
			, "col" : col
			, "week_class" : day_type
			, "year" : y
			, "month" : m
			, "day" : d
		})
		.hover(function() {
			var parent = $(this).parent();
			var day_type = parent.data("week_class");
			//parent.find("> p").addClass(day_type + "_over");
		}, function() {
			var parent = $(this).parent();
			var day_type = parent.data("week_class");
			//parent.find("> p").removeClass(day_type + "_over");
		})
		.click(function() {
			View_Pop_Link($(this));
		})
		.attr("href", "javascript:;")
		.append(
			$("<p/>").html(d > 9 ? d : "0" + d)
		)
		
		var position_left = $("> a", this).css("left") ? parseInt($("> a", this).css("left")) : 2;
		var position_top = $("> a", this).css("top") ? parseInt($("> a", this).css("top")) : 2;
		
		_day_a
		.css({
			"width" : (_day.innerWidth() - (position_left * 2)) + "px"
			, "height" : (_day.innerHeight() - (position_top * 2)) + "px"
		})
		.appendTo(_day);
		Schedule_Load_Get_List(y, m, d);
	};
	
	var div_top = 0;
	
	_div_p = $("> #calendar_" + year_p + "_" + month_p, _container);
	_div_t = $("> #calendar_" + year + "_" + month, _container);
	_div_n = $("> #calendar_" + year_n + "_" + month_n, _container);
	
	if(_div_t.length == 0) {
		div_top = 0;
		_div_t = $("<div/>").attr("id", "calendar_" + year + "_" + month).appendTo(_container).css({"top": div_top + "px", "z-index" : "2"});
		$("<h3/>").css({
			"display" : "none"
			, "visibility" : "hidden"
		})
		.html(year + "년 " + month + "월 달력")
		.appendTo(_div_t);
		for(var d = 1; d <= total_day.today; d++) {
			Day_Init(year, month, d, _div_t);
		}
	}
	
	if(_div_p.length == 0) {
		_div_p = $("<div/>").attr("id", "calendar_" + year_p + "_" + month_p);
		$("<h3/>").css({
			"display" : "none"
			, "visibility" : "hidden"
		})
		.html(year_p + "년 " + month_p + "월 달력")
		.appendTo(_div_p);
		for(var d = 1; d <= total_day.prev; d++) {
			Day_Init(year_p, month_p, d, _div_p);
		}
		div_top = Number(_div_p.data("week_count")) * _day_height * -1 + _div_t.position().top;
		if(Number(_div_p.data("week_last")) == 6) {
			div_top -= _day_height;
		}
		_div_p
		.appendTo(_container)
		.css({
			"top": div_top + "px"
			, "z-index" : "1"
		})
	}
	
	if(_div_n.length == 0) {
		_div_n = $("<div/>").attr("id", "calendar_" + year_n + "_" + month_n);
		$("<h3/>").css({
			"display" : "none"
			, "visibility" : "hidden"
		})
		.html(year_n + "년 " + month_n + "월 달력")
		.appendTo(_div_n);
		for(var d = 1; d <= total_day.next; d++) {
			Day_Init(year_n, month_n, d, _div_n);
		}
		div_top = Number(_div_t.data("week_count")) * _day_height + _div_t.position().top;
		if(Number(_div_t.data("week_last")) == 6) {
			div_top += _day_height;
		}
		_div_n
		.appendTo(_container)
		.css({
			"top": div_top + "px"
			, "z-index" : "1"
		})
	}
	
	_div_p.height(Number(_div_p.data("week_count")) * _day_height).css("z-index", "1").click(function() { Schedule_Init(year_p, month_p) });
	_div_t.height(0).css("z-index", "2").unbind("click");
	_div_n.height(Number(_div_n.data("week_count")) * _day_height).css("z-index", "1").click(function() { Schedule_Init(year_n, month_n) });

	var prev_time = (new Date(year_p, month_p)).getTime();
	var next_time = (new Date(year_n, month_n)).getTime();
	//alert(c_type + " : " + _div_t.position().top);
	$("> div", _container).each(function(i) {
		var py = Number($(this).data("year_p"));
		var pm = Number($(this).data("month_p"));
		var y = Number($(this).data("year"));
		var m = Number($(this).data("month"));
		var ny = Number($(this).data("year_n"));
		var nm = Number($(this).data("month_n"));
		var time_get = (new Date(y, m)).getTime();
		
		div_top = (_div_t.position().top * -1) + $(this).position().top;
		if(c_type > 0) {
			if(Number(_div_t.data("week_first")) == 0) {
				//div_top += _day_height;
			}
		}
		
		
		$("> div > a", this).each(function() {
			$(this).unbind("click");
		});
		if(y == year && m == month) {
			$("> div > a", this).click(function(e) {
				View_Pop_Link(this);
			});
		}
		//alert(div_top);
		$(this).stop().animate({
			top : div_top
			, opacity : (y == year && m == month) ? 1 : disabled_opacity
		}, slide_duration, slide_ease, function() {
			if(prev_time > time_get || next_time < time_get) {
				$(this).remove();
			}
		});
	});
	
	$("#schedule_layout").stop().animate({
		height : Number(_div_t.data("week_count") + 1) * _day_height
	}, slide_duration, slide_ease, function() {
		Schedule_Resize();
	});
	
}

function View_Pop_Link(div) {
	Schedule_Pop_Show("show"
	, "<?=_lh_yhyh_web?>/index.php?_skin=view_pop&_id=<?=$_REQUEST["_id"]?>&_year=" + $(div).data("year") + "&_month=" + $(div).data("month") + "&_day=" + $(div).data("day")
	, { duration : 200, ease : "easeOutCubic", type : "show", scrollTop_response : true, completeFunction : function() {
			Schedule_Get_List($(div).data("year"), $(div).data("month"), $(div).data("day"));
		}
		}
	);
}
function Schedule_Pop_Show(type, url, option) {
	if(!url) {
		if(!type) {
			String("close").LayoutPopClose();
		} else {
			String(type).LayoutPopClose();
		}
		return;
	}
	url.LayoutPop(option);
}
function Write_Pop_Action(url) {
	if(!url) url = "<?=_lh_yhyh_web?>/index.php?_skin=write_pop&_id=<?=$_REQUEST["_id"]?>&_year=<?=$_REQUEST["_year"]?>&_month=<?=$_REQUEST["_month"]?>&_day=<?=$_REQUEST["_day"]?>&_direct=true";
	Schedule_Pop_Show("show"
	, url
	, { duration : 200, ease : "easeOutCubic", type : "show", completeFunction : Write_Pop_Load_Complete, scrollTop_response : true }
	);
}
function Write_Pop_Load_Complete() {
	Pop_Design_form_Init(".FormDesignNormal");
}

function Schedule_Load_Get_List(y, m, d) {
	y = Number(y);
	m = Number(m);
	d = Number(d);
	var url = "<?=_lh_yhyh_web?>/"
	var params = {
		"_skin" : "view_pop_list_proc"
		, "_id" : "<?=$_REQUEST["_id"]?>"
		, "_year" : y
		, "_month" : m
		, "_day" : d
	};
	$.post(url, params, function(data) {
		var list = $("> list", $.parseXML(data));
		var _schedule_day = $("#calendar_day_" + y + "_" + m + "_" + d + " > a");
		_schedule_day.empty();
		if(d == 1) {
			//alert(list.length);
		}
		var _Ul = $("<ul/>").appendTo(_schedule_day);
		$("> row", list).each(function(i) {
			if(d == 1) {
				//alert($(this).attr("yhb_title"));
			}
			var s_date = new Date(Number($(this).attr("yhb_start_time")) * 1000);
			var d_date = new Date(Number($(this).attr("yhb_end_time")) * 1000);
			var _Li = $("<li/>").html("&bull;&nbsp;" + $(this).attr("yhb_title")).appendTo(_Ul);
		});
	});
}


function Schedule_Get_List(y, m, d) {
	y = Number(y);
	m = Number(m);
	d = Number(d);
	var url = "<?=_lh_yhyh_web?>/"
	var params = {
		"_skin" : "view_pop_list_proc"
		, "_id" : "<?=$_REQUEST["_id"]?>"
		, "_year" : y
		, "_month" : m
		, "_day" : d
	};
	$.post(url, params, function(data) {
		//alert(data);
		var write_url = "<?=_lh_yhyh_web?>/index.php?_skin=write_pop&_id=<?=$_REQUEST["_id"]?>";
		var list = $("> list", $.parseXML(data));
		var _schedule_list = $("#schedule_view_list");
		_schedule_list.empty();
		$("> row", list).each(function(i) {
			var s_date = new Date(Number($(this).attr("yhb_start_time")) * 1000);
			var d_date = new Date(Number($(this).attr("yhb_end_time")) * 1000);
			var yhb_number = Number($(this).attr("yhb_number"));
			var yhb_content = $(this).text();
			var _Li = $("<li/>").appendTo(_schedule_list);
			var _Ul = $("<ul/>").appendTo(_Li);
			
			var s_moon = s_date.getMonth() + 1;
			var d_moon = d_date.getMonth() + 1;
			if(s_moon < 10) s_moon = "0" + s_moon;
			if(d_moon < 10) d_moon = "0" + d_moon;
			
			var s_day = s_date.getDate() > 9 ? s_date.getDate() : "0" + s_date.getDate();
			var d_day = d_date.getDate() > 9 ? d_date.getDate() : "0" + d_date.getDate();
			
			var s_hour = s_date.getHours() > 9 ? s_date.getHours() : "0" + s_date.getHours();
			var d_hour = d_date.getHours() > 9 ? d_date.getHours() : "0" + d_date.getHours();
			
			var s_minute = s_date.getMinutes() > 9 ? s_date.getMinutes() : "0" + s_date.getMinutes();
			var d_minute = d_date.getMinutes() > 9 ? d_date.getMinutes() : "0" + d_date.getMinutes();
			
			var _Li_date = $("<li/>").addClass("view_pop_list_date").appendTo(_Ul);
			var _Li_title = $("<li/>").addClass("view_pop_list_title").appendTo(_Ul);
			var _Li_content = $("<li/>").addClass("view_pop_list_content").html(yhb_content).appendTo(_Ul);
			
			$("<a/>")
			.hover(function() {
				$(this).addClass("a_button");
			}, function() {
				$(this).removeClass("a_button");
			})
			.click(function() {
				Write_Pop_Action(write_url + "&_year=" + s_date.getFullYear() + "&_month=" + s_moon + "&_day=" + s_day);
			})
			.attr({
				"title" : s_date.getFullYear() + "년 " + s_moon + "월 " + s_day + "일자로 신규 일정등록하기"
				, "href" : "javascript:;"
			})
			.html(s_date.getFullYear() + "." + s_moon + "." + s_day + " " + s_hour + ":" + s_minute + "").appendTo(_Li_date);
			$("<span/>").html("~").appendTo(_Li_date);
			$("<a/>")
			.hover(function() {
				$(this).addClass("a_button");
			}, function() {
				$(this).removeClass("a_button");
			})
			.click(function() {
				Write_Pop_Action(write_url + "&_year=" + d_date.getFullYear() + "&_month=" + d_moon + "&_day=" + d_day);
			})
			.attr({
				"title" : d_date.getFullYear() + "년 " + d_moon + "월 " + d_day + "일자로 신규 일정등록하기"
				, "href" : "javascript:;"
			})
			.html(d_date.getFullYear() + "." + d_moon + "." + d_day + " " + d_hour + ":" + d_minute + "").appendTo(_Li_date);
			
			_Li_title
			.hover(function() {
				$(this).addClass("a_button");
			}, function() {
				$(this).removeClass("a_button");
			})

			
			$("<span/>")
			.attr("title", "이 일정의 상세정보를 보실 수 있습니다.")
			.click(function() {
				$("> li", _schedule_list).each(function(i) {
					var index = _Li_content.parents("li").index();
					var _content = $(" > ul > .view_pop_list_content", this);
					if(index == i) {
						_content.slideToggle(500, "easeOutCubic");
					} else {
						_content.slideUp(500, "easeOutCubic");
					}
				});
			})
			.html($(this).attr("yhb_title")).appendTo(_Li_title)
			<? if($_LhDb->Get_Admin()) { ?>
			$("<a/>")
			.click(function() {
				if(Rows_Delete_Check) {
					Rows_Delete_Check(yhb_number);
				}
			})
			.attr("title", "이 일정을 삭제합니다.")
			.html("delete")
			.appendTo(_Li_title);

			$("<a/>")
			.click(function() {
				Write_Pop_Action(write_url + "&_writeMode=modify&_no=" + yhb_number + "&_year=" + y + "&_month=" + m + "&_day=" + d);
			})
			.attr("title", "이 일정을 수정합니다.")
			.html("edit")
			.appendTo(_Li_title);
			<? } ?>
		});
	});
}

function Grant_No_View(msg) {
	alert(msg);
}
</script>
<div class="yhyh_list">
	<div class="yhyh_list_header">
		<p class="numberFont">Schedules : <?=number_format($_total_rows)?></p>
		<div class="list_header_center">
			<div>
				<a href="javascript:Schedule_Move('prev');">&lt; PREV</a>
				<span class="calendar_year"><p><?=$_REQUEST[_year]?></p></span>
				<span class="calendar_month"><p><?=$_REQUEST[_month]?></p></span>
				<a href="javascript:Schedule_Move('next');">NEXT &gt;</a>
			</div>
		</div>
		<div class="list_header_right">
			<? if($_LhDb->Get_Admin()) { ?>
			<a href="javascript:;" onClick="Write_Pop_Action();" class="a_button numberFont">Schedule</a>
			<a href="<?=$_logout_link?>" class="a_button numberFont">Logout</a>
			<a href="<?=$_register_link?>&_auto=true" class="a_button numberFont">Info</a>
			<? } else { ?>
			<a href="<?=$_login_link?>&_returnType=return" class="a_button numberFont">Admin</a>
			<? } ?>
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
				<? if($_LhDb->Get_Admin()) { ?>
					<span class="yhyh_button_footer_right"><a href="javascript:;" onClick="Write_Pop_Action();" class="a_button">Schedule</a></span>
				<? } ?>
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