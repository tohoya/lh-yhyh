// JavaScript Document

/* 팝업창 함수들 시작
	사용법 : Popup_Init_Show("main_popup_121214", "/include/html/popup01.html", "경축 ICC 2014 유치 성공", 100, 100);
*/
function Popup_Cookie_Set(name, value, expiredays) {
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; domain=" + document.domain + "; expires=" + todayDate.toGMTString() + ";"
	//alert(todayDate);
	//alert(document.cookie);
}

function Popup_Init_Win(name, url, title, top, left, width, height, hidden_day, start, end) {
	//alert(Popup_Gookie_Get(name));
	if(!hidden_day) hidden_day = 1;
	if(!title) title = "새로운 소식";
	if(!top) top = 0;
	if(!left) left = 0;
	if ((Popup_Gookie_Get(name) != "Y")) {
		window.open(url + "&cookie_name=" + name, name, "left=" + left + ", top=" + top + ", width=" + width + ", height=" + height + ",toolbar=no, menubar=no, scrollbars=yes, resizabled=yes");
	}
}

function Popup_Init_Show(name, url, title, left, top, width, height, hidden_day, start, end) {
	//alert(Popup_Gookie_Get(name));
	if(!hidden_day) hidden_day = 1;
	if(!title) title = "새로운 소식";
	if(!top) top = 0;
	if(!left) left = 0;
	
	if ((Popup_Gookie_Get(name) != "Y")) {
		var oPopup = $("<div/>");
		oPopup
		.css({
			"display" : "block"
			, "height" : (height) ? (height + 60) + "px" : ""
			, "background-color" : "#000000"
			, "border" : "2px solid #000000"
			, "-webkit-box-shadow" : "0 10px 10px #000"
			, "-moz-box-shadow" : "0 10px 10px #000"
			, "box-shadow" : "0 10px 10px #000"
			, "color" : "#FFF"
			, "position" : "absolute"
			, "left" : (left) + "px"
			, "top" : (top - 100) + "px"
			, "z-index" : "99999999999"
			, "opacity" : "0"
			, "filter" : "alpha(opacity=0)"
		})
		.append(
			$("<h2/>")
			.css({
				"display" : "block"
				, "padding" : "2px 5px 2px 15px"
				, "background-color" : "#000000"
				, "color" : "#FFF"
				, "text-align" : "center"
				, "z-index" : "99999999999"
			})
			.append(
				$("<p/>")
				.css({
					"float" : "left"
					, "display" : "block"
					, "padding" : "2px 0 0 0"
					, "z-index" : "99999999999"
				})
				.html(title)
			)
			.append(
				$("<input/>")
				.attr({
					"type" : "button"
				})
				.css({
					"float" : "right"
					, "display" : "block"
					, "width" : "26px"
					, "height" : "22px"
					, "padding" : "2px 7px"
					, "text-decoration" : "none"
					, "color" : "#FFF"
					, "background-color" : "#666"
					, "border" : "1px solid #888"
					, "cursor" : "pointer"
					, "z-index" : "99999999999"
				})
				.val("X")
				.click(function() {
					oPopup.remove();
				})
			)
		)
		.append(
			$("<div/>")
			.css({
				"display" : "block"
			})
			.load(url, function(data) {
				var oImg = $(this).find("img");
				var ImageLoader = function(oImg) {
					var width = (oImg.outerWidth() > 0) ? oImg.outerWidth() : "";
					oPopup.css({
						"width" : (width) + "px"
					});
				};
				if(!width) {
					if($.browser.msie) {
						width = (oImg.outerWidth() > 0) ? oImg.outerWidth() : "";
						oPopup.css({
							"width" : (width) + "px"
						});
					} else {
						oImg.load(function() {
							ImageLoader($(this));
						});
					}
				}
				//alert(width);
			})
		)
		.append(
			$("<div/>")
			.css({
				"display" : "block"
				, "clear" : "both"
				, "padding" : "3px 15px"
				, "background-color" : "#000000"
				, "color" : "#FFF"
				, "text-align" : "right"
				, "z-index" : "99999999999"
			})
			.append(
				$("<input/>")
				.attr({
					"type" : "checkbox"
					, "id" : "cb_" + name
				})
				.change(function() {
					Popup_Cookie_Set(name, "Y", hidden_day);
					oPopup.remove();
				})
			)
			.append(
				$("<label/>")
				.attr({
					"for" : "cb_" + name
				})
				.html("&nbsp;하루동안 창닫기")
			)
		)
		.prependTo("body")
		.animate({
			opacity : 1
			, top : top
		}, {
			duration : 700
			, specialEasing : {
				opacity : "easeOutCubic"
				, top : "easeOutBounce"
			}
		});
	}
}
function Popup_Gookie_Get(name) {
	var prefix = name + "=";
	var cookieStartIndex = document.cookie.indexOf(prefix);
	if (cookieStartIndex == -1)
		return null;
	var cookieEndIndex = document.cookie.indexOf(";", cookieStartIndex + prefix.length);
	if (cookieEndIndex == -1)
		cookieEndIndex = document.cookie.length;
	return unescape(document.cookie.substring(cookieStartIndex + prefix.length, cookieEndIndex));
}
/* 팝업창 함수 끝 */
