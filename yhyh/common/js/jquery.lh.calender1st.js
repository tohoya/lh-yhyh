// JavaScript Document
function FormMiniCalender($_target, $_division) {
	if(!$_division) $_division = ".";
	var lastArray = new Array(31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var monthArray = new Array("","01","02","03","04","05","06","07","08","09","10","11","12");
	var dayName = new Array('S','M','T','W','T','F','S');
	var dayNameKr = new Array('일','월','화','수','목','금','토');
	var nowYear, nowMonth;
	var today = new Date();
	
	var This = $($_target);
	var oParent = $("body");
	
	$("#popCalendar").remove();
	
	var oBase = $("<div/>").attr("id", "popCalendar");
	
	var PopRemove = function(e) {
		oParent.css("z-index", "");
		//return;
		oBase.remove();
	};
	
	var SideOutFunc = function(e) {
		//alert(oParent.css("z-index"));
		//$("<span/>").html("1::" + oParent.css("z-index")).appendTo(oParent);
		This.blur(PopRemove);
	};
	
	var SideOverFunc = function(e) {
		//alert(oParent.css("z-index"));
		//$("<span/>").html("2::" + oParent.css("z-index")).appendTo(oParent);
		This.unbind("blur", PopRemove);
		This.blur(CalendarOut);
	};
	
	var CalendarOut = function(e) {
		//alert(1);
		//$("<span/>").html("3::" + oParent.css("z-index")).appendTo(oParent);
		oBase.unbind("mouseleave", SideOutFunc);
		oBase.mouseleave(function() {
			oParent.css("z-index", "");
			//return;
			$(this).remove();
		});
	};
	var dateValue = This.get(0).value;
	var nowDate = new Date();
	var nowYear = nowDate.getFullYear();
	var nowMonth = nowDate.getMonth();
	var nowDay = nowDate.getDate();
	
	var choiceDate = (dateValue) ? new Date(dateValue.Get_Split(0, $_division), Number(dateValue.Get_Split(1, $_division)) - 1, dateValue.Get_Split(2, $_division)) : new Date();
	var choiceYear = Number(choiceDate.getFullYear());
	var choiceMonth = Number(choiceDate.getMonth());
	var choiceMonthText = (choiceMonth + 1) > 9 ? choiceMonth + 1 : "0" + (choiceMonth + 1);
	//alert(choiceMonthText);
	var todayButton = $("<img/>")
	.css({
		"display" : "block"
		, "float" : "left"
		, "margin" : "0px 1px"
		, "cursor" : "pointer"
	})
	.attr({
		"src" : "/yhyh/common/image/pop_calendar_Nowdate.gif"
		, "alt" : "오늘로 이동"
	})
	.click(function() {
		CalendarInit(today.getFullYear(), today.getMonth(), "+");
	});
	
	oBase.css({
		"display" : "block"
		, "width" : "147px"
		, "height" : "auto"
		, "position" : "absolute"
		, "top" : (This.offset().top + This.outerHeight()) + "px"
		, "left" : (This.offset().left) + "px"
		, "z-index" : "11000"
		, "background" : "#DBDBDB"
		, "border" : "1px solid #AAAAAA"
		, "padding" : "3px"
		, "overflow" : "hidden"
	})
	.append(
		$("<div/>")
		.attr("id", "calendarTitleView")
		.css({
			"display" : "block"
			, "position" : "relative"
			, "z-index" : "11001"
			, "clear" : "both"
			, "padding" : "3px 3px 3px"
			, "font-size" : "11px"
			, "font-weight" : "bolder"
			, "line-height" : "1em"
			, "text-align" : "center"
			, "border" : "1px solid #F4F4F4"
			, "border-right-color" : "#999999"
			, "border-bottom-color" : "#999999"
		})
		.html(choiceYear + "년 " + choiceMonthText + "월")
	)
	.append(
		$("<div/>")
		.css({
			"display" : "block"
			, "position" : "relative"
			, "z-index" : "11001"
			, "clear" : "both"
			, "padding" : "2px 0px 2px"
		})
		.append(
			$("<img/>")
			.css({
				"display" : "block"
				, "float" : "left"
				, "margin" : "0px 1px"
				, "cursor" : "pointer"
			})
			.attr({
				"src" : "/yhyh/common/image/pop_calendar_p_Year.gif"
				, "alt" : "이전 년도"
			})
			.click(function() {
				choiceYear--;
				CalendarInit(choiceYear, choiceMonth, "-");
			})
		)
		.append(
			$("<img/>")
			.css({
				"display" : "block"
				, "float" : "left"
				, "margin" : "0px 1px"
				, "cursor" : "pointer"
			})
			.attr({
				"src" : "/yhyh/common/image/pop_calendar_p_Month.gif"
				, "alt" : "이전 달"
			})
			.click(function() {
				if(choiceMonth - 1 >= 0) {
					choiceMonth--;
				} else {
					choiceYear--;
					choiceMonth = 11;
				}
				CalendarInit(choiceYear, choiceMonth, "-");
			})
		)
		.append(todayButton)
		.append(
			$("<img/>")
			.css({
				"display" : "block"
				, "float" : "left"
				, "margin" : "0px 1px"
				, "cursor" : "pointer"
			})
			.attr({
				"src" : "/yhyh/common/image/pop_calendar_n_Month.gif"
				, "alt" : "다음 달"
			})
			.click(function() {
				if(choiceMonth + 1 < 12) {
					choiceMonth++;
				} else {
					choiceYear++;
					choiceMonth = 0;
				}
				CalendarInit(choiceYear, choiceMonth, "+");
			})
		)
		.append(
			$("<img/>")
			.css({
				"display" : "block"
				, "float" : "left"
				, "margin" : "0px 1px"
				, "cursor" : "pointer"
			})
			.attr({
				"src" : "/yhyh/common/image/pop_calendar_n_Year.gif"
				, "alt" : "다음 년도"
			})
			.click(function() {
				choiceYear++;
				CalendarInit(choiceYear, choiceMonth, "+");
			})
		)
	);
	
	var oLayout = $("<div/>")
	.attr("id", "calendarLayout")
	.css({
		"display" : "block"
		, "clear" : "both"
		, "width" : (oBase.outerWidth() - 8) + "px"
		, "position" : "relative"
		, "z-index" : "11001"
		, "overflow" : "hidden"
	})
	.appendTo(oBase);
	
	var cellWidth = Math.floor((oBase.outerWidth() - 8) / 7 - 3);
		
	var weekTable = $("<table/>")
	.attr({
		"cellpadding" : "0"
		, "cellspacing" : "0"
		, "border" : "0"
	})
	.css({
		"border-collapse" : "separate" //"collapse"//"separate"
		, "border-spacing" : "0px"
		, "border" : "0px"
	}).appendTo(oLayout);
	
	var oThead = $("<thead/>").append("<tr/>").appendTo(weekTable);
	for(i = 0; i < 7; i++) {
		oThead.find("> tr").append(
			$("<th/>")
			.css({
				"padding" : "5px 2px 2px"
				, "font-size" : "11px"
				, "text-align" : "center"
				, "width" : cellWidth + "px"
			})
			.html(dayNameKr[i])
		);
	}

	var CalendarInit = function(y, m, type) {
		//alert(y + ":" + m);
		choiceYear = y;
		choiceMonth = m;
		var mText = (m + 1 > 9) ? m + 1 : "0" + (m + 1);
		
		$("#calendarTitleView").html(y + "년 " + mText + "월");
		
		var nowDate = new Date(y, m, 1);
		var startWeek = nowDate.getDay();
		var lastDay = lastArray[m];
		if(m == 1) {
			lastDay = (y % 4) ? 28 : 29;
		}
		$("> div", oLayout).each(function() {
			$(this).animate({
				left : (type == "+") ? oBase.outerWidth() * -1 : oBase.outerWidth()
			}, 500, "easeInOutCubic", function() {
				$(this).remove();
			});
		});
		var left = (type == "-") ? (oBase.outerWidth() * -1) + "px" : (oBase.outerWidth()) + "px";
		var oDiv = $("<div/>")
		.css({
			"display" : "block"
			, "width" : (oBase.outerWidth() - 8) + "px"
			, "position" : "absolute"
			, "left" : (type) ? left : "0px"
			, "top" : "17px"
			, "background" : "#F4F4F4"
			, "z-index" : "11002"
		}).appendTo(oLayout);
		
		var oTable = $("<table/>")
		.attr({
			"cellpadding" : "0"
			, "cellspacing" : "0"
			, "border" : "0"
		})
		.css({
			"border-collapse" : "separate" //"collapse"//"separate"
			, "border-spacing" : "0px"
			, "border" : "0px"
		}).appendTo(oDiv);
		
		var oTbody = $("<tbody/>").appendTo(oTable);
		var oTr;// = $("<tr/>").appendTo(oTbody);
		var d = 1;
		for(i = 1 - startWeek; i <= lastDay; i++) {
			var nowDateSync = (nowYear == y && nowMonth == m && nowDay == i);
			if((d - 1) % 7 == 0) {
				oTr = $("<tr/>").appendTo(oTbody);
			}
			//$("<div/>").html((d - 1) % 7).appendTo("body");
			var day = (i > 0) ? i : "&nbsp;";
			var oSpan = $("<span/>").css({
				"display" : "block"
				, "width" : (cellWidth) + "px"
				, "padding" : "4px 0px"
				, "text-align" : "center"
				, "font-size" : "11px"
				, "cursor" : (i > 0) ? "pointer" : "default"
				, "border-top" : (nowDateSync) ? "1px solid #254063" : "1px solid #f0f0f1"
				, "border-right" : (nowDateSync) ? "0px solid #f0f0f1" : "1px solid #f0f0f1"
				, "border-left" : (nowDateSync) ? "1px solid #254063" : "0px solid #254063"
				, "text-decoration" : "none"
			})
			.html(day);
			
			var oTd = $("<td/>")
			.css({
				"border" : "1px solid #a5a8b1"
				, "background" : (nowDateSync) ? "#7389a5" : "#dad9dd"
				, "color" : (nowDateSync) ? "#FFFFFF" : "#333333"
			})
			.append(oSpan).appendTo(oTr);
			
			if(i > 0) {
				oTd.mouseenter( { nowDateSync : nowDateSync }, function(e) {
					var nowDateSync = e.data.nowDateSync;
					$(this).css({
						"border-color" : "#293649"
						, "background" : "#2d8be7"
						, "color" : "#FFFFFF"
					})
					.find("> span").css({
						"border-top-color" : "#9bc8f4"
						, "border-right" : "1px solid #9bc8f4"
						, "border-left" : "0px solid #254063"
					});
					
				})
				.mouseleave( { nowDateSync : nowDateSync }, function(e) {
					var nowDateSync = e.data.nowDateSync;
					$(this).css({
						"border-color" : "#a5a8b1"
						, "background" : (nowDateSync) ? "#7389a5" : "#dad9dd"
						, "color" : (nowDateSync) ? "#FFFFFF" : "#333333"
					})
					.find("> span").css({
						"border-top-color" : (nowDateSync) ? "#254063" : "#f0f0f1"
						, "border-right" : (nowDateSync) ? "0px solid #f0f0f1" : "1px solid #f0f0f1"
						, "border-left" : (nowDateSync) ? "1px solid #254063" : "0px solid #254063"
					});
				})
				.click( { y : y, m : m + 1, d : day }, function(e) {
					var y = e.data.y;
					var m = (e.data.m > 9) ? e.data.m : "0" + e.data.m;
					var d = (e.data.d > 9) ? e.data.d : "0" + e.data.d;
					This.val(y + $_division + m + $_division + d);
					oBase.remove();
				});
			}
			d++;
		}
		
		oDiv.stop().animate({
			left : 0
		}, 500, "easeInOutCubic");
		oLayout.stop().animate({
			height : oDiv.outerHeight() + (weekTable.outerHeight())
		}, 500, "easeInOutCubic");
	};
	
	oBase.mouseenter(SideOverFunc)
	.mouseleave(SideOutFunc);
	
	oParent.append(oBase);
	This.blur(PopRemove);
	
	CalendarInit(choiceYear, choiceMonth, "");
}
