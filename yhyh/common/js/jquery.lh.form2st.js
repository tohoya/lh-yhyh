// JavaScript Document
function FormCalendar($_target, $_division) {
	if(!$_division) $_division = ".";
	var lastArray = new Array(31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var monthArray = new Array("","01","02","03","04","05","06","07","08","09","10","11","12");
	var dayName = new Array('S','M','T','W','T','F','S');
	var dayNameKr = new Array('일','월','화','수','목','금','토');
	var nowYear, nowMonth;
	var today = new Date();
	
	var This = $($_target);
	var oParent = This.parents("[formid=" + This.attr("id") + "]");
	oParent.css("z-index", "1000");
	
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
		"src" : "/LHtmlEditers/image/lhForm_cssk_Cld_Nowdate.gif"
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
		, "top" : (oParent.outerHeight()) + "px"
		, "left" : "0px"
		, "z-index" : "1000"
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
			, "z-index" : "1001"
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
			, "z-index" : "1001"
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
				"src" : "/LHtmlEditers/image/lhForm_cssk_Cld_p_Year.gif"
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
				"src" : "/LHtmlEditers/image/lhForm_cssk_Cld_p_Month.gif"
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
				"src" : "/LHtmlEditers/image/lhForm_cssk_Cld_n_Month.gif"
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
				"src" : "/LHtmlEditers/image/lhForm_cssk_Cld_n_Year.gif"
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
		, "z-index" : "1001"
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
			lastDay = (y % 4) ? 29 : 28;
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
			, "z-index" : "1002"
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
					This.FormValue(y + $_division + m + $_division + d);
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
function FormDesignInit(target, skin) {
	//alert(target);
	/*
	$.each($.browser, function(i, val) {
		alert(i + " " + val);
		switch(i) {
			case "":
			break;
		}
	});*/
	/*******************************************/
	/********* DEFAULT FUNCTION & ETC **********/
	/*******************************************/
	var nowDate = new Date(), idCount = 1, i, count, parentCount = 1, fieldHeight = 26
	,fontColor = "#333333";
	
	if(!skin) skin = 1;
	switch(skin) {
		case 1:
			fieldHeight = 26;
			fontColor = "#333333";
		break;
	}
	
	var ImageLoader = function(obj) {
		var url, str, indexStart, indexEnd, viewObjectLh = $("<div/>").appendTo("body");
		viewObjectLh.css({
			"width" : "1px"
			, "height" : "1px"
			, "overflow" : "hidden"
		});
		
		count = obj.length;
		
		for(i = 0; i < count; i++) {
			url = "/LHtmlEditers/image/" + obj[i];
			$("<img/>")
			.attr("src", url)
			.css({
				"position" : "absolute"
				, "top" : "-10000"
				, "left" : "-10000"
			})
			.appendTo(viewObjectLh);
		}
		viewObjectLh.remove();
	};

	var html5Check = function() {
		switch(true) {
			case ($.browser.mozilla):
				return (parseFloat($.browser.version) >= 3.7);
			break;
			case ($.browser.msie):
				return (parseFloat($.browser.version) >= 9);
			break;
			case ($.browser.safari):
				return (parseFloat($.browser.version) >= 4);
			break;
			case ($.browser.opera):
				return (parseFloat($.browser.version) >= 11);
			break;
		}
		return false;
	};
	
	var This = $(target);
	if(This.length == 0) {
		This = $(window);
	}
	
	var allSelect = "input:text, input:password, input:file, input:checkbox, input:radio, :submit, :button, :reset, textarea, select";
	
	var formTextField = $("input:text, input:password", This)
	, formFile = $("input:file", This)
	, formCheckbox = $("input:checkbox", This)
	, formRadio = $("input:radio", This)
	, formButton = $(":submit, :button, :reset", This)
	, formTextarea = $("textarea", This)
	, formSelect = $("select", This);
	
	var isParentSet = function(type, oParent, This) {
		var count  = oParent.children().length;
		
		if(!oParent.attr("formParent")) oParent.attr("formParent", "parent_" + (nowDate.getTime() + parentCount));
		parentCount++;
		
		switch(type) {
			case "text":
				var oP = $("<span/>")
				.attr("formid", This.attr("id"));
				oP.parent()
				.css({
					"line-height" : "1em"
					, "position" : "relative"
				});
				oP.appendTo(oParent);
				This.after(oP);
				oP.css("width", "auto").append(This);
				return oP;
			break;
		}
		return oParent;
	};
	
	
	/*******************************************/
	/********** INPUT(text, password) **********/
	/*******************************************/
	formTextField.each(function(i) {
		idCount++;
		var oInput = $(this).get(0);
		
		$(this).css({
			"border" : "0px solid #FFFFFF"
			, "background" : "url(/LHtmlEditers/image/lhForm_Opacity_Bg.png)"
			, "font-family" : "Dotum"
			, "font-size" : "9pt"
			, "text-align" : "left"
			, "margin" : "0px"
			, "line-height" : "16px"
			, "height" : "16px"
			,	"outline" : "transparent solid 0px"
		});
		
		if(!$(this).attr("id")) $(this).attr("id", $(this).attr("type") + "_" + (nowDate.getTime() + idCount));
		
		var oParent = isParentSet("text", $(this).parent(), $(this));
		
		$("<span/>")
		.css({
			"display" : "block"
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : "0px"
			, "float" : "left"
			, "width" : "7px"
			, "height" : fieldHeight + "px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISL.png) no-repeat"
		})
		.appendTo(oParent);
		
		var oSpan = $("<span/>")
		.css({
			"display" : "block"
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : "7px"
			, "float" : "left"
			, "font-size" : "9pt"
			, "text-align" : "left"
			, "width" : ($(this).outerWidth() - 5) + "px"
			, "height" : (fieldHeight - 5) + "px"
			, "padding" : "5px 2px 0px 2px"
			, "font-size" : "0px"
			, "line-height" : "1em"
			,	"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISB.png) repeat-x"
		})
		.appendTo(oParent);
		
		var placeHolderSpan = null;
		if(!html5Check()) {
			placeHolderSpan = $("<span/>")
			.attr("disabled", "disabled")
			.css({
				"display" : "block"
				, "position" : "absolute"
				, "left" : "0px"
				, "color" : "#AAAAAA"
				, "line-height" : "1em"
				, "font-size" : "9pt"
				, "width" : ($(this).outerWidth() - 3) + "px"
				, "height" : (fieldHeight - 8) + "px"
				, "overflow" : "hidden"
				, "padding" : "3px 2px 0px 1px"
			}).html($(this).attr("placeHolder") ? $(this).attr("placeHolder") : "");

			oSpan.append(placeHolderSpan);
		}
		
		if(oInput.value.length > 0 && placeHolderSpan != null) placeHolderSpan.hide();
		
		var inputSpan = $("<span/>")
		.css({
			"display" : "block"
			, "position" : "absolute"
			, "left" : "0px"
		})
		.append($(this).clone().css({
			"display" : "block"
			, "visibility" : "visible"
		}));
		
		oSpan.append(inputSpan);
		
		$("> input", inputSpan)
		.unbind("focus", function() { })
		.unbind("blur", function() { })
		.blur(function() {
			$(this).css("color", "");
			if($(this).get(0).value.length == 0 && placeHolderSpan != null) placeHolderSpan.show();
			var cSpan = inputSpan.parent();
			cSpan.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISB.png)"
			});
			cSpan.prev()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISL.png)"
			});
			cSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DIR.png)"
			});
		})
		.focus(function() {
			$(this).css("color", "#0066CC");
			if(placeHolderSpan != null) placeHolderSpan.hide();
			var cSpan = inputSpan.parent();
			cSpan.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISB_Over.png)"
			});
			cSpan.prev()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISL_Over.png)"
			});
			cSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DIR_Over.png)"
			});
		});
		
		$("<span/>")
		.css({
			"display" : "block"
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : (oSpan.outerWidth() + 7) + "px"
			, "float" : "left"
			, "width" : "7px"
			, "height" : fieldHeight + "px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DIR.png) no-repeat"
		})
		.appendTo(oParent);
		oParent.css("width", (oSpan.outerWidth() + 14) + "px");
		oParent.css("display", ($(this).css("display") == "none") ? $(this).css("display") : "block");
		oParent.css("visibility", $(this).css("visibility"));
		if(oParent.parent().children().length == 1) {
			switch(oParent.parent().css("text-align")) {
				case "center":
					oParent.css("margin", "0px auto");
				break;
				case "right":
					oParent.css("margin", "0px 0px 0px auto");
				break;
			}
		}
		
		oSpan.click(function() {
			$(oInput).focus();
		});
		
		if($(this).get(0).disabled) {
			$(this).FormStatus("disabled", true);
		}
		
		$(this).remove();
	});
	
	
	/*******************************************/
	/**************** TEXTAREA *****************/
	/*******************************************/
	formTextarea.each(function(i) {
		idCount++;
		var tmpSpan;
		var oTextarea = $(this).get(0);
		
		$(this).css({
			"border" : "0px solid #FFFFFF"
			, "display" : "block"
			, "background" : "url(/LHtmlEditers/image/lhForm_Opacity_Bg.png)"
			, "font-family" : "Dotum"
			, "font-size" : "9pt"
			, "text-align" : "left"
			, "margin" : "0px"
			, "line-height" : "14px"
			, "overflow" : "auto"
			, "resize" : "none"
			,	"outline" : "transparent solid 0px"
		});
		
		if(!$(this).attr("id")) $(this).attr("id", "textarea" + "_" + (nowDate.getTime() + idCount));
		
		var oParent = isParentSet("textarea", $(this).parent(), $(this));
		
		tmpSpan = $("<span/>")
		.appendTo(oParent)
		.css({
			"display" : "block"
			, "clear" : "both"
			, "padding" : "0px"
			, "font-size" : "0px"
			, "line-height" : "1em"
		});
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "7px"
			, "height" : "7px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTL.png) no-repeat"
		})
		.appendTo(tmpSpan);
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : $(this).outerWidth() + "px"
			, "height" : "7px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTT.png) repeat-x"
		})
		.appendTo(tmpSpan);
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "7px"
			, "height" : "7px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTR.png) no-repeat"
		})
		.appendTo(tmpSpan);
		
		tmpSpan = $("<span/>")
		.appendTo(oParent)
		.css({
			"display" : "block"
			, "position" : "relative"
			, "clear" : "both"
			, "padding" : "0px"
			, "white-space" : "nowrap"
			, "text-align" : "left"
			, "width" : ($(this).outerWidth() + 14) + "px"
		});
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "7px"
			, "min-height" : ($(this).outerHeight() + 2) + "px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTLL.png) repeat-y"
		})
		.appendTo(tmpSpan);
		
		var oSpan = $("<div/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "position" : "relative"
			, "min-width" : ($(this).outerWidth()) + "px"
			, "min-height" : ($(this).outerHeight() + 2) + "px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBG.png)"
			, "text-align" : "left"
			, "padding" : "0px"
			, "font-size" : "0px"
			, "line-height" : "1em"
		})
		.appendTo(tmpSpan);
		
		var placeHolderSpan = null;
		if(!html5Check()) {
			placeHolderSpan = $("<span/>")
			.attr("disabled", "disabled")
			.css({
				"display" : "block"
				, "position" : "absolute"
				, "top" : "0px"
				, "left" : "0px"
				, "font-size" : "9pt"
				, "width" : ($(this).outerWidth()) + "px"
				, "height" : ($(this).outerHeight()) + "px"
				, "padding" : "0px 0px"
				, "line-height" : "1.1em"
				, "white-space" : "normal"
				, "overflow" : "hidden"
			}).html($(this).attr("placeHolder") ? $(this).attr("placeHolder") : "");

			oSpan.append(placeHolderSpan);
		}
		var inputSpan = $("<span/>")
		.css({
			"display" : "block"
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : "0px"
		})
		.append($(this).clone());
		
		if(oTextarea.value.length > 0 && placeHolderSpan != null) placeHolderSpan.hide();
		
		oSpan.append(inputSpan);

		$("> textarea", inputSpan)
		.unbind("focus", function() { })
		.unbind("blur", function() { })
		.blur(function() {
			$(this).css("color", "");
			if($(this).get(0).value.length == 0 && placeHolderSpan != null) placeHolderSpan.show();
			var cSpan = inputSpan.parent();
			cSpan.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBG.png)"
			});
			cSpan.prev()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTLL.png)"
			});
			cSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTRR.png)"
			});
			
			var topSpan = $("> span:eq(0)", cSpan.parent().prev());
			topSpan
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTL.png)"
			});
			topSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTT.png)"
			});
			topSpan.next().next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTR.png)"
			});
			
			var bottomSpan = $("> span:eq(0)", cSpan.parent().next());
			bottomSpan
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBL.png)"
			});
			bottomSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBB.png)"
			});
			bottomSpan.next().next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBR.png)"
			});
		})
		.focus(function() {
			$(this).css("color", "#0066CC");
			if(placeHolderSpan != null) placeHolderSpan.hide();
			var cSpan = inputSpan.parent();
			cSpan.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBG_Over.png)"
			});
			cSpan.prev()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTLL_Over.png)"
			});
			cSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTRR_Over.png)"
			});
			
			var topSpan = $("> span:eq(0)", cSpan.parent().prev());
			topSpan
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTL_Over.png)"
			});
			topSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTT_Over.png)"
			});
			topSpan.next().next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTTR_Over.png)"
			});
			
			var bottomSpan = $("> span:eq(0)", cSpan.parent().next());
			bottomSpan
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBL_Over.png)"
			});
			bottomSpan.next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBB_Over.png)"
			});
			bottomSpan.next().next()
			.css({
				"background-image" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBR_Over.png)"
			});
		});

		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "7px"
			, "min-height" : ($(this).outerHeight() + 2) + "px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTRR.png) repeat-y"
		})
		.appendTo(tmpSpan);
		
		tmpSpan = $("<span/>")
		.appendTo(oParent)
		.css({
			"display" : "block"
			, "clear" : "both"
			, "padding" : "0px"
		});
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "7px"
			, "height" : "7px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBL.png) no-repeat"
		})
		.appendTo(tmpSpan);
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : $(this).outerWidth() + "px"
			, "height" : "7px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBB.png) repeat-x"
		})
		.appendTo(tmpSpan);
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "7px"
			, "height" : "7px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DTBR.png) no-repeat"
		})
		.appendTo(tmpSpan);
		
		oParent.css("display", $(this).css("display"));
		oParent.css("visibility", $(this).css("visibility"));
		oParent.css("width", ($(this).outerWidth() + 14) + "px");
		if(oParent.parent().children().length == 1) {
			switch(oParent.parent().css("text-align")) {
				case "center":
					oParent.css("margin", "0px auto");
				break;
				case "right":
					oParent.css("margin", "0px 0px 0px auto");
				break;
			}
		}
		
		if($(this).get(0).disabled) {
			$(this).FormStatus("disabled", true);
		}
		
		$(this).remove();
	});
	
	
	/*******************************************/
	/************* SELECT FUNCTION *************/
	/*******************************************/
	var SelectEffect = function(oSelect, type) {
		var cSpan = oSelect.prev().prev().prev();
		if(!type) {
			type = (oSelect.data("focus") != "true") ? "out" : "over";
		}
		switch(type) {
			case "over":
				cSpan
				.css({
					"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISL_Over.png) no-repeat"
				});
				cSpan.next()
				.css({
					"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISB_Over.png) repeat-x"
				});
				cSpan.next().next()
				.css({
					"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DSB_Over.png) no-repeat"
				});
			break;
			case "out":
				cSpan
				.css({
					"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISL.png) no-repeat"
				});
				cSpan.next()
				.css({
					"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISB.png) repeat-x"
				});
				cSpan.next().next()
				.css({
					"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DSB.png) no-repeat"
				});
			break;
		}
	};
	
	
	/*******************************************/
	/***************** SELECT ******************/
	/*******************************************/
	formSelect.each(function(i) {
		idCount++;
		var oInput;
		var oSelect = $(this).get(0);
		var count = oSelect.length;
		var index = oSelect.selectedIndex;
		var j;
	
		var oDisplay = new Array();
		$(this).parents(":hidden").each(function(i) {
			if($(this).css("display") == "none") {
				$(this).css("display", "block");
				oDisplay.push($(this));
			}
		});
		
		if(!$(this).attr("id")) $(this).attr("id", "select" + "_" + (nowDate.getTime() + idCount));
		
		var oParent = isParentSet("select", $(this).parent(), $(this));
		
		oParent.css("display", $(this).css("display"));
		oParent.css("visibility", $(this).css("visibility"));
		
		$(this).css({
			"display" : "block"
			, "height" : fieldHeight + "px"
			, "position" : "absolute"
			, "top" : ($.browser.msie) ? "2px" : ""
			, "bottom" : "0px"
			, "left" : "0px"
			, "filter" : "alpha(opacity=0)"
			, "opacity" : "0"
			, "cursor" : "pointer"
			, "font-size" : "12px"
			, "z-index" : "0"
		})
		.unbind("keyup", function() { })
		.unbind("change", function() { })
		.unbind("focus", function() { })
		.unbind("blur", function() { })
		.unbind("click", function() { })
		.keyup(function(e) {
			if(e.keyCode == 32 || e.keyCode == 13 || e.keyCode == 27) {
				if(e.keyCode == 32) {
					//SelectClose(oSelect, (oSelect.css("display") == "none") ? "click" : "close");
				} else {
					//SelectClose(oSelect, "close");
				}
			}
		})
		.change(function(e) {
			var index = oSelect.selectedIndex;
			$(oSelect).prev().prev().html(oSelect.options[index].text);
		})
		.blur(function() {
			if(oParent.data("focus") != "true") {
				$(this).data("focus", "false");
				SelectEffect($(oSelect), "");
			} else {
				$(this).get(0).focus();
			}
		})
		.focus(function() {
			$(this).data("focus", "true");
			if(oParent.data("focus") != "true") {
				SelectEffect($(oSelect), "");
			}
			oParent.data("focus", "false");
		})
		.click(function() {
			if(oParent.data("focus") != "true") {
				SelectEffect($(oSelect), "");
			}
		})
		.find("> option").css("font-size", "9pt");

		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "7px"
			, "height" : fieldHeight + "px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISL.png) no-repeat"
			, "position" : "absolute"
		})
		.appendTo(oParent);
		
		var oSpan = $("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "font-family" : "Dotum"
			, "font-size" : "9pt"
			, "font-weight" : "normal"
			, "text-align" : "left"
			, "width" : ($(this).outerWidth() - 16 > 0) ? ($(this).outerWidth() - 16) + "px" : "inherit"
			, "min-width" : "15px"
			, "height" : (fieldHeight - 7) + "px"
			, "color" : fontColor
			, "margin" : "0px"
			, "padding" : "7px 2px 0px 2px"
			, "line-height" : "1em"
			, "white-space" : "nowrap"
			,	"background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DISB.png) repeat-x"
			, "position" : "absolute"
			//, "border" : "1px solid #FF0000"
			, "left" : "7px"
		})
		.appendTo(oParent)
		.html(oSelect.options[index].text);
		
		$("<span/>")
		.css({
			"display" : "block"
			, "float" : "left"
			, "width" : "16px"
			, "height" : fieldHeight + "px"
			, "padding" : "0px"
			, "background" : "url(/LHtmlEditers/image/lhForm" + skin + "_DSB.png) no-repeat"
			, "position" : "absolute"
			, "left" : (oSpan.outerWidth() + 7) + "px"
		})
		.appendTo(oParent);
		
		oParent
		.css({
			"cursor" : "pointer"
		});
		oParent.append(this);
		
		var w = 0;
		$("> span", oParent).each(function(i) {
			w += $(this).width();
		});
	
		count = oDisplay.length;
		for(i = 0; i < count; i++) {
			oDisplay[i].css("display", "none");
		};
		
		oParent.css("display", "block");
		
		$(this).css("width", (w + 5) + "px");
		oParent.css("width", (w + 5) + "px");
		if(oParent.parent().children().length == 1) {
			switch(oParent.parent().css("text-align")) {
				case "center":
					oParent.css("margin", "0px auto");
				break;
				case "right":
					oParent.css("margin", "0px 0px 0px auto");
				break;
			}
		}
		
		if($(this).get(0).disabled) {
			$(this).FormStatus("disabled", true);
		}
		
	});
	
	$("[formParent]", This).each(function(i) {
		$("> *:not([formid], [formParent])", this).each(function(i) {
			if($(this).css("float") != "left" && $(this).css("clear") == "none") {
				switch($(this).get(0).tagName.toLowerCase()) {
					case "br":
						$(this).css({
							"clear" : "both"
						});
					break;
					default:
						$(this).css({
							"float" : "left"
							, "display" : "block"
							, "vertical-align" : "middle"
						});
						var maths = Math.ceil((fieldHeight - $(this).height()) / 2);
						var padding = "0px";
						//if() {
							//padding = (fieldHeight > $(this).height()) ? maths + "px 10px " + maths + "px 0px" : "0px 10px 0px 0px";
						//} else {
							padding = (fieldHeight > $(this).height()) ? maths + "px 5px" : "4px 5px";
						//}
						$(this).css("padding", padding);
				}
			}
		});
	});
	
	
	
}

$.fn.FormDesignInit = function(skin) {
	FormDesignInit(this, skin);
};
