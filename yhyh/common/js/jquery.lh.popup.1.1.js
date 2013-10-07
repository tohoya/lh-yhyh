String.prototype.lh_Block_Popup = function($_config) {
	var url = this
	// 일반 변수
	, duration = 500
	, ease = "easeOutCubic"
	, keep = true
	, action = ''
	, action_type = "none"
	, scroll_sync = true
	, window_width = 0
	, window_height = 0
	, _base_div
	, _viewer_div
	, _load_object
	
	// Function 변수
	, Base_Init
	, Load_Object
	, Viewer_Append
	, Image_Loader
	, Flash_Loader
	, Complete_Function
	, Remove
	, Close_Action
	, Reform_Object
	, Option_Init
	;
	
	if(!jQuery) {
		alert("jQuery가 설정되지 않았습니다.");
		return false;
	}
	
	_base_div = jQuery("body #_lh_popup_base");
	_viewer_div = jQuery("body #_lh_popup_view");
	
	// Popup Init
	Base_Init = function() {
		
		Remove();
		
		if(jQuery("body").length > 0 && jQuery("body").children().length > 0) {
			jQuery("body").children().css("position", "relative");
		}
		
		// Base Div Init
		
		_base_div = jQuery("<div/>");

		Option_Init();
		//alert(jQuery(window).width() + " : " + jQuery("body").width());
		var w_width = jQuery(window).width() > jQuery("body").width() ? jQuery(window).width() : jQuery("body").width();
		var w_height = jQuery(window).height() > jQuery("body").height() ? jQuery(window).height() : jQuery("body").height();
		
		if(window_width > 0) w_width = window_width;
		if(window_height > 0) w_height = window_height;
		
		_base_div.attr({
			"class" : "_lh_popup_object"
			, "id" : "_lh_popup_base"
		})
		.css({
			"display" : "block"
			, "width" : w_width
			, "height" : w_height
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : "0px"
			, "background" : "#FFF"
			, "z-index" : 2000
		})
		.stop()
		.fadeTo(0, 0)
		.animate({
			opacity : 0.8
		}, duration, function() {
			Load_Object();
		})
		.appendTo("body");
		
		// Viewer Div Init
		_viewer_div = jQuery("<div/>")
		.attr({
			"class" : "_lh_popup_object"
			, "id" : "_lh_popup_view"
		})
		.css({
			"display" : "block"
			, "width" : _base_div.width()
			, "height" : _base_div.height()
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : "0px"
			, "z-index" : 2001
		})
		.appendTo("body");
		
	};
	
	// 팝업창을 사라지게 한다.
	Remove = function() {
		jQuery("[class*=_lh_popup_object]").stop().animate({
			opacity : 0
		}, duration, ease, function() {
			_base_div.remove();
			_viewer_div.remove();
		});
	};
	
	// 스크롤 움직였을 때 다시 위치를 잡는다.
	Reform_Object = function($_target) {
		var w = $_target.find(":last-child").width();
		var h = $_target.find(":last-child").height();
		var Scroll_Target = function() {
			var t = jQuery(document).scrollTop();
			var mt = jQuery(window).height() > h ? t + (jQuery(window).height() - h) * 0.5 : t + 10;
			var margin_html = (!scroll_sync) ? "0px auto" : (mt) + "px auto 10px";
			$_target.css({
				width : w
				, height : h
				, margin : margin_html
			});
			
		};
		
		Scroll_Target();
	};
	
	// 오브젝트 그룻에 담기
	Viewer_Append = function($_target) {
		_viewer_div.css("overflow", "hidden");
		
		jQuery("> *", _viewer_div).each(function(i) {
			jQuery(this)
			.stop()
			.animate({
				left : (jQuery(window).width() > _base_div.width()) ? jQuery(window).width() * -1 : _base_div.width() * -1
				, opacity : 0
			}, duration, ease, function() {
				if(!keep || (keep && action_type != "next")) {
					jQuery(this).remove();
				}
			});
		});
		
		Reform_Object($_target);
		
		// 새로 로드한 오브젝트를 만든다.
		var _viewer = jQuery("<div/>")
		.css({
			"display" : "block"
			, "width" : _base_div.width()
			, "height" : _base_div.height()
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : jQuery(window).width() + "px"
		})
		.stop()
		.fadeTo(0, 0)
		.animate({
			left : 0
			, opacity : 1
		}, duration, ease, function() {
			if(Complete_Function) {
				if(Complete_Function && Complete_Function != null) {
					Complete_Function();
				}
			}
			_viewer_div.css("overflow", "");
		})
		.append($_target)
		.appendTo(_viewer_div);
		
		//$(document).scroll();
	};
	
	// 이미지 로더
	Image_Loader = function($_url, $_out_func) {
		var func = function(_url) {
			var oImg = jQuery("<img/>")
			.attr({
				"src" : _url
			})
			.css({
				"display" : "block"
				, "border" : "0"
				, "position" : "absolute"
				, "top" : "-99999999"
				, "left" : "-99999999"
			})
			.appendTo("body");
			
			if(oImg.width() > 0 && oImg.height() > 0) {
				var _a = jQuery("<a/>")
				.attr({
					"href" : oImg.attr("src")
					, "title" : "팝업창을 닫습니다."
				})
				.css({
					"width" : oImg.width()
					, "height" : oImg.height()
					, "display" : "block"
					, "background-color" : "#FFF"
					, "border" : "1px solid #CDCDCD"
					, "-webkit-box-shadow" : "0 1px 30px rgba(0, 0, 0, .3)"
					, "-moz-box-shadow" : "0 1px 30px rgba(0, 0, 0, .3)"
					, "box-shadow" : "0 1px 30px rgba(0, 0, 0, .3)"
				})
				.click(function(e) {
					String('').lh_Block_Popup({ action : 'close' });
					return false;
				})
				.append(
					oImg
					.css({
						"position" : ""
						, "top" : ""
						, "left" : ""
					})
				);
				//alert(_a.html());
				$_out_func(_a);
			} else {
				oImg.load(function() {
					oImg = jQuery(this);
					if(oImg.width() > 0 && oImg.height() > 0) {
						var _a = jQuery("<a/>")
						.attr({
							"href" : oImg.attr("src")
							, "title" : "팝업창을 닫습니다."
						})
						.css({
							"width" : oImg.width()
							, "height" : oImg.height()
							, "display" : "block"
							, "background-color" : "#FFF"
							, "-webkit-box-shadow" : "10 10px 10px rgba(0, 0, 0, .8)"
							, "-moz-box-shadow" : "10 10px 10px rgba(0, 0, 0, .8)"
							, "box-shadow" : "10 10px 10px rgba(0, 0, 0, .8)"
						})
						.click(function(e) {
							String('').lh_Block_Popup({ action : 'close' });
							return false;
						})
						.append(
							oImg
							.css({
								"position" : ""
								, "top" : ""
								, "left" : ""
							})
						);
						$_out_func(_a);
					} else {
						func(_url);
					}
				});
			}
		};
		func($_url);
	}
	
	// 플래시 로더
	Flash_Loader = function($_url, $_width, $_height) {
		var arr = $_url.toString().split("?"), url, flashVar, size_html = "", style = " style=\"";
		url = arr[0];
		flashVar = arr[1];
		
		if($_width) {
			size_html += " width=\"" + $_width + "\"";
			style += "width:" + $_width + ";";
		}
		if($_height) {
			size_html += " height=\"" + $_height + "\"";
			style += "height:" + $_height + ";";
		}
		
		style + "\"";
		
		var htmls = "<object" + size_html + style + " id=\"lh_flash_id_" + url + "\" name=\"lh_flash_name_" + url + "\" classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0\">";
		htmls += "<param name=\"movie\" value=\"" + url + "?" + flashVar + "\">";
		htmls += "<param name=\"allowScriptAccess\" value=\"sameDomain\">";
		htmls += "<param name=\"allowFullScreen\" value=\"false\">";
		htmls += "<param name=\"quality\" value=\"high\">";
		htmls += "<param name=\"WMode\" value=\"Transparent\">";
		htmls += "<param name=\"FlashVars\" value=\"" + flashVar + "\">";
		htmls += "<embed src=\"" + url + "?" + flashVar + "\" FlashVars=\"" + flashVar + "\"" + size_html + style + " type=\"application/x-shockwave-flash\" pluginspage=\"http://www.adobe.com/go/getflashplayer\" quality=\"high\" align=\"middle\" allowScriptAccess=\"sameDomain\" allowFullScreen=\"false\" WMode=\"Transparent\"></embed>";
		htmls += "</object>";
		return htmls;
	}
	
	// 삭제 관련
	Close_Action = function() {
		
		if(action == "close") {
			action_type = "close";
		} else {
			switch(action) {
				case "prev":
					if(_viewer_div.children().length > 1) {
						action_type = "prev";
					} else {
						action_type = "close";
					}
				break;
				case "next":
					if(_viewer_div.children().length > 0) {
						action_type = "next";
					} else {
						action_type = "none";
					}
				break;
				case "close":
					action_type = "close";
				break;
				default:
					if(url != "") {
						if(_viewer_div.children().length > 0) {
							action_type = "next";
						} else {
							action_type = "none";
						}
					} else {
						action_type = "close";
					}
			}
		}
		
		//alert(action_type);
		
		var _viewer = jQuery("> :last-child", _viewer_div);
		
		if(_viewer.length > 0) {
			switch(action_type) {
				case "prev":
					_viewer_div.css("overflow", "hidden");
					var _viewer_prev = _viewer.prev();
					
					_viewer.stop().animate({
						left : jQuery(window).width()
						, opacity : 0
					}, duration, ease, function() {
						jQuery(this).remove();
					});
					
					Reform_Object(jQuery("> div", _viewer_prev));
					
					_viewer_prev.stop().animate({
						left : 0
						, opacity : 1
					}, duration, ease, function() {
						_viewer_div.css("overflow", "");
					});
					return true;
				break;
				case "close":
					_viewer_div.css("overflow", "hidden");
					_viewer.stop().animate({
						left : jQuery(window).width()
						, opacity : 0
					}, duration, ease, function() {
						jQuery(this).remove();
						_viewer_div.css("overflow", "");
					});
					Remove();
					return true;
				break;
			}
		}
	};
	
	// 옵션 값 정리
	Option_Init = function() {
		
		// 옵션 정리
		var d = _base_div.data("lh_popup_data"), width, height;
		if(!d || d.length == 0) d = {};
		
		if($_config) {
			if(typeof($_config.keep) == 'boolean') d["keep"] = $_config.keep;
			if($_config.duration) d["duration"] = $_config.duration;
			if($_config.ease) d["ease"] = $_config.ease;
			if($_config.action) d["action"] = $_config.action;
			if($_config.window_size) {
				if($_config.window_size.width) d["window_width"] = $_config.window_size.width;
				if($_config.window_size.height) d["window_height"] = $_config.window_size.height;
			}
			if(typeof($_config.scroll_sync) == 'boolean') scroll_sync = $_config.scroll_sync;
			if($_config.width) width = $_config.width;
			if($_config.height) height = $_config.height;
		}
		
		if(d) {
			if(typeof(d["keep"]) == 'boolean') keep = d["keep"];
			if(d["duration"]) duration = d["duration"];
			if(d["ease"]) ease = d["ease"];
			if(d["action"]) action = d["action"];
			if(typeof(d["action"]) == 'boolean') action = d["action"];
			if(d["window_width"]) window_width = d["window_width"];
			if(d["window_height"]) window_height = d["window_height"];
		}
		
		_base_div.data("lh_popup_data", d);
	};
	
	// 오브젝트 로그 하기
	Load_Object = function() {
		
		if(Close_Action()) return;
		
		//alert(action_type);
		
		switch(url.File_Ext().toLowerCase()) {
			case "jpge":
			case "jpg":
			case "png":
			case "pic":
			case "gif":
				try {
					Image_Loader(url, function($_img) {
						var _div = jQuery("<div/>").append($_img);
						Viewer_Append(_div);
					});
				} catch(e) {
					alert(url + " 파일이 존재하지 않습니다.(Image)");
					Remove();
					return;
				}
			break;
			case "html":
			case "htm":
			case "php":
			case "jsp":
			case "asp":
				try {
					$.ajax({
						//contentType을 지정해줘야 합니다.
						contentType : "text/html",
						url : url,
						success : function(data) {
							var _div = jQuery("<div/>").html(data);
							Viewer_Append(_div);
						},
						error : function() {
							alert(url + " 파일이 존재하지 않습니다.(Html)");
							Remove();
							return;
						}
					});
					/*$.get(url, function(data) {
						var div = jQuery("<div/>").html(data);
						Viewer_Append(div);
					});*/
				} catch(e) {
					alert(url + "파일이 존재하지 않습니다.(Html)");
					Remove();
					return;
				}
			break;
			case "swf":
				if(!width || !height) {
					alert("플래시 파일의 경우 사이즈가 필요합니다.");
					return;
				}
				var _div = jQuery("<div/>").html(Flash_Loader(url, width, height));
				var _a = jQuery("<a/>")
				.attr({
					"href" : url
					, "title" : "팝업창을 닫습니다."
				})
				.css({
					"width" : "20px"
					, "height" : "20px"
					, "display" : "block"
					, "font-size" : "16px"
					, "color" : "#FF0000"
					, "font-weight" : "bold"
					, "font-family" : "Arial"
					, "text-decoration" : "none"
					, "float" : "right"
					, "text-align" : "center"
				})
				.click(function(e) {
					String('').lh_Block_Popup({ action : 'close' });
					return false;
				})
				.html("X")
				.prependTo(_div);
				
				Viewer_Append(_div);
			break;
			default:
				alert(url.File_Ext().toLowerCase() + "는 지원하지 않는 확장자 입니다.");
				Remove();
				return;
		}
	};
	
	// Popup Base 가 없으면 새로 생성 아니면 그냥 진행
	if(_base_div.length > 0) {
		Option_Init();
		Load_Object();
	} else {
		Base_Init();
	}
};
/*

String.prototype.File_Ext = function() {
	var file = this;
	var str = file.toString();
	var lastIndex = (str.indexOf("?") > -1) ? str.indexOf("?") - (str.lastIndexOf(".") + 1) : str.length;
	return str.substr(str.lastIndexOf(".") + 1, lastIndex ).toUpperCase();
};
String.prototype.trim = function() {
	return this.replace(/(^\s*)|(\s*$)/gi, "");
};
String.prototype.HexToDec = function() {
	var arr = new Array();
	arr["A"] = 10;
	arr["B"] = 11;
	arr["C"] = 12;
	arr["D"] = 13;
	arr["E"] = 14;
	arr["F"] = 15;
	
	var count = this.length;
	var str = '';
	var tmpStr = "";
	var strBytes = 0;
	for(var i = count - 1; i >= 0 ; i-=2){
		str = this.charAt(i).toUpperCase();
		tmpStr = (str <= 9) ? Number(str) : Number(arr[str]);
		strBytes += Math.pow(16, count - (i - 1) - 1) * tmpStr;
		
		str = this.charAt(i - 1).toUpperCase();
		tmpStr = (str <= 9) ? Number(str) : Number(arr[str]);
		strBytes += Math.pow(16, count - 1 - i) * tmpStr;
	}
	return strBytes;
};
String.prototype.DecToHex = function() {
	var colors = this;
	var str = '';
	var strBytes = '';
	var tempStr01 = '';
	var tempStr02 = '';
	for(i = 0; i < 3; i++) {
		strBytes = colors & 0xFF;
		colors >>= 8;
		tempStr02 = strBytes & 0x0F;
		tempStr01 = (strBytes>>4) & 0x0F;
		str += tempStr01.toString(16);
		str += tempStr02.toString(16);
	}
	var strOut = "";
	var count = str.length;
	for(i = 0; i < count; i++) {
		strOut += str.charAt(count - 1 - i);
	}
	return strOut.toUpperCase();
};
String.prototype.RgbToHex = function() {
	var str = '';
	var tempStr01 = '';
	var tempStr02 = '';
	var firstIndex = this.indexOf("(") + 1;
	var len = this.lastIndexOf(")") - firstIndex;
	var str = this.substr(firstIndex, len);
	var arr = str.split(",");
	
	str = "";
	for(i = 0 ; i < 3 ; i++) {
		tempStr02 = String(arr[i]).trim() & 0x0F;
		tempStr01 = (String(arr[i]).trim()>>4) & 0x0F;
		str += tempStr01.toString(16);
		str += tempStr02.toString(16);
	}
	return str.toUpperCase();
};
String.prototype.HexToRgb = function() {
	var arr = new Array();
	arr["A"] = 10;
	arr["B"] = 11;
	arr["C"] = 12;
	arr["D"] = 13;
	arr["E"] = 14;
	arr["F"] = 15;
	var count = this.length;
	var str = '';
	var tmpStr = "";
	var strBytes = 0;
	var strOut = "";
	
	for(i = count - 1; i >= 0 ; i-=2){
		str = this.charAt(i).toUpperCase();
		tmpStr = (str <= 9) ? Number(str) : Number(arr[str]);
		strBytes = Math.pow(16, (i - 1)) * tmpStr;
		
		str = this.charAt(i - 1).toUpperCase();
		tmpStr = (str <= 9) ? Number(str) : Number(arr[str]);
		strBytes += Math.pow(16, i) * tmpStr;
		
		strOut += (strOut) ? ", " + strBytes : strBytes;
	}
	return "rgb(" + strBytes + ")";
};
*/