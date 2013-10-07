// JavaScript Document
String.prototype.$ = function(_target) {
	if(target) return (jQuery) ? jQuery(this, _target) : null;
	return (jQuery) ? jQuery(this) : null;
};
String.prototype.jQuery = function(_target) {
	if(target) return (jQuery) ? jQuery(this, _target) : null;
	return (jQuery) ? jQuery(this) : null;
};
String.prototype.Split_Check = function($_str, $_division) {
	var This = this.toString();
	var arr = This.split($_division);
	var count = arr.length;
	for(var i = 0; i < count; i++) {
		if($_str.toLowerCase() == arr[i].toString().replace(/\s/g, "").toLowerCase()) {
			return true;
		}
	}
	return false;
};
String.prototype.Get_Split = function($_idx, $_division) {
	var This = this.toString();
	var arr = This.split($_division);
	return arr[$_idx];
};

String.prototype.LayoutPop = function(object) {
	var url = this;
	var top = 0;
	var margin_top = 0;
	var duration = 500;
	var ease = "easeOutCubic";
	var scrollTop_response = false;
	if(object) {
		if(object.top) {
			top = object.top;
		}
		if(object.scrollTop_response) {
			scrollTop_response = object.scrollTop_response;
		}
		if(object.top) {
			top = object.top;
		}
		if(object.ease) {
			ease = object.ease;
			$(window).data("ease", ease);
		}
		if(object.duration) {
			duration = object.duration;
			$(window).data("duration", duration);
		}
		if(object.marginTop) {
			margin_top = object.marginTop;
		}
	}
	var PopupViewSize = function(oView, url) {
		var w = 0, h = 0, oViewHeight = 0;
		switch(url.File_Ext().toLowerCase()) {
			case "jpge":
			case "jpg":
			case "png":
			case "pic":
			case "gif":
				w = oView.width();
				h = oView.height();
			break;
			case "html":
			case "htm":
			case "php":
			case "jsp":
			case "asp":
				w = oView.find(":last-child").width();
				h = oView.find(":last-child").height();
			break;
			case "swf":
				w = (object && object.width && object.width > 0) ? object.width : 550;
				h = (object && object.height && object.height > 0) ? object.height : 400;
			break;
			case "flv":
			break;
			case "f4v":
			break;
		}
		return { w : w, h : h };
	};
	var PopupResize = function(e) {
		var oView = e.data.oView;
		
		var h = jQuery(window).height() > jQuery("body").height() ? jQuery(window).height() : jQuery("body").height();

		oViewHeight = (h > PopupViewSize(oView, url).h) ? (h - PopupViewSize(oView, url).h) * 0.5 : 0;
		if(scrollTop_response) {
			oViewHeight += ($(window).scrollTop()) ? $(window).scrollTop() : 0;
		}
		var marginTop = oViewHeight + "px auto 0px";
		
		if(margin_top > 0) {
			if(PopupViewSize(oView, url).h > jQuery(window).height()) {
				marginTop = margin_top + "px auto 0px";
			} else {
				marginTop = (margin_top + (jQuery(window).height() - PopupViewSize(oView, url).h) * 0.5) + "px auto 0px";
			}
		} else if(top > 0) {
			if(top + PopupViewSize(oView, url).h > jQuery(document).height()) {
				marginTop = (jQuery(document).height() - PopupViewSize(oView, url).h) + "px auto 0px";
			} else {
				marginTop = (top) + "px auto 0px";
			}
		}
		
		oView.css({
			"margin" : marginTop
			, "width" : PopupViewSize(oView, url).w + "px"
			, "height" : PopupViewSize(oView, url).h + "px"
		});
		
		jQuery("#LayoutPopupView > div:not(:last-child)").each(function(i) {
			jQuery(this).css({
				"left" : "0px" //(jQuery(window).width() > PopupViewSize(jQuery(this), url).w) ? (jQuery(window).width() * -1) + "px" : (PopupViewSize(jQuery(this), url).w * -1) + "px"
			});
		});
		
	};
	
	var PopupImageView = function(vDiv, oView) {
		
		jQuery(window).unbind("resize", PopupResize);
		jQuery(window).bind("resize", { oView : oView }, PopupResize);
		
		oView.css({
			"display" : "block"
			, "visibility" : "visible"
		})
		.click(function() {
			if(jQuery(this).get(0).tagName.toLowerCase() == "img") {
				String("/test.html").LayoutPop({ type : "show" });
			}
		});
		
		PopupResize({ data : { oView : oView }});
		
		jQuery("#LayoutPopupView").css("overflow", "hidden");
		
		jQuery("> div", vDiv).each(function(i) {
			jQuery(this)
			.stop()
			.animate({
				left : (jQuery(window).width() > PopupViewSize(jQuery(this), url).w) ? jQuery(window).width() * -1 : PopupViewSize(jQuery(this), url).w * -1
				, opacity : 0
			}, duration, ease, function() {
				if(!object || !object.type || object.type != "show") {
					jQuery(this).remove();
				}
			});
		});
		
		jQuery("<div/>")
		.css({
			"display" : "block"
			, "width" : jQuery(window).outerWidth() > jQuery(document).outerWidth() ? jQuery(window).outerWidth() : jQuery(document).outerWidth() //"100%"
			, "height" : jQuery(window).outerHeight() > jQuery(document).outerHeight() ? jQuery(window).outerHeight() : jQuery(document).outerHeight() //"100%"
			, "position" : "absolute"
			, "top" : "0px"
			, "left" : jQuery(window).width() + "px"
		})
		.appendTo(vDiv)
		.append(oView)
		.stop()
		.fadeTo(0, 0)
		.animate({
			left : 0
			, opacity : 1
		}, duration, ease, function() {
			if(object) {
				if(object.completeFunction && object.completeFunction != null) {
					object.completeFunction();
				}
			}
			jQuery("#LayoutPopupView").css("overflow", "");
		});
	};
	
	var PopupView = function(oView) {
		var bDiv, vDiv;
		if(jQuery("[class*=_LayoutPopupView]", "body").length == 0) {
			jQuery("body").children().css("position", "relative");
			
			bDiv = jQuery("<div/>");
			vDiv = jQuery("<div/>");
			
			bDiv
			.attr({
				"class" : "_LayoutPopupView"
				, "id" : "LayoutPopupViewBg"
			})
			.css({
				"display" : "block"
				, "width" : jQuery(window).outerWidth() > jQuery(document).outerWidth() ? jQuery(window).outerWidth() : jQuery(document).outerWidth() //"100%"
				, "height" : jQuery(window).outerHeight() > jQuery(document).outerHeight() ? jQuery(window).outerHeight() : jQuery(document).outerHeight() //"100%"
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
				PopupImageView(vDiv, oView);
			})
			.appendTo("body");
			
			vDiv
			.attr({
				"class" : "_LayoutPopupView"
				, "id" : "LayoutPopupView"
			})
			.css({
				"display" : "block"
				, "width" : jQuery(window).outerWidth() > jQuery(document).outerWidth() ? jQuery(window).outerWidth() : jQuery(document).outerWidth() //"100%"
				, "height" : jQuery(window).outerHeight() > jQuery(document).outerHeight() ? jQuery(window).outerHeight() : jQuery(document).outerHeight() //"100%"
				, "position" : "absolute"
				, "top" : "0px"
				, "left" : "0px"
				, "z-index" : 2001
			})
			.appendTo("body")
		} else {
			PopupImageView(jQuery("#LayoutPopupView"), oView);
		}
	};
	
	var ImageLoader = function(url, _func) {
		var func = function(_url) {
			var oImg = jQuery("<img/>")
			.attr({
				"src" : _url
			})
			.css({
				"visibility" : "hidden"
			})
			.appendTo("body");
			
			if(oImg.width() > 0 && oImg.height() > 0) {
				_func(oImg);
			} else {
				oImg.load(function() {
					if(jQuery(this).width() > 0 && jQuery(this).height() > 0) {
						_func(jQuery(this));
					} else {
						func(_url);
					}
				});
			}
		};
		func(url);
	};

	var FlashSwfLoad = function(url, flashVar) {
		var htmls = "<object width=\"100%\" height=\"100%\" id=\"flashSwfLoadId\" name=\"flashSwfLoadName\" classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0\">";
		htmls += "<param name=\"movie\" value=\"" + url + "?" + flashVar + "\">";
		htmls += "<param name=\"allowScriptAccess\" value=\"sameDomain\">";
		htmls += "<param name=\"allowFullScreen\" value=\"false\">";
		htmls += "<param name=\"quality\" value=\"high\">";
		htmls += "<param name=\"WMode\" value=\"Transparent\">";
		htmls += "<param name=\"FlashVars\" value=\"" + flashVar + "\">";
		htmls += "<embed src=\"" + url + "?" + flashVar + "\" width=\"100%\" FlashVars=\"" + flashVar + "\" height=\"100%\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.adobe.com/go/getflashplayer\" quality=\"high\" align=\"middle\" allowScriptAccess=\"sameDomain\" allowFullScreen=\"false\" WMode=\"Transparent\"></embed>";
		htmls += "</object>";
		return htmls;
	};
	
	try {
		switch(url.File_Ext().toLowerCase()) {
			case "jpge":
			case "jpg":
			case "png":
			case "pic":
			case "gif":
				ImageLoader(url, PopupView);
			break;
			case "html":
			case "htm":
			case "php":
			case "jsp":
			case "asp":
				$.get(url, function(data) {
					PopupView(jQuery("<div/>").html(data));
				});
			break;
			case "swf":
				var hDiv = jQuery("<div/>").html(FlashSwfLoad(url));
				PopupView(hDiv);
			break;
		}
	} catch(e) {
		alert(e);
	}
};

String.prototype.LayoutPopClose = function() {
	var type = this;
	var vDiv = jQuery("#LayoutPopupView");
	var duration = $(window).data("duration") ? Number($(window).data("duration")) : 500;
	var ease = $(window).data("ease") ? $(window).data("ease") : "easeOutCubic";
		
	vDiv.css("overflow", "hidden");
	
	if(vDiv.children().length > 1 && type == "prev") {
		oView = jQuery("> :last-child", vDiv);
		oPrev = oView.prev();
		
		oView.stop().animate({
			left : jQuery(window).width()
			, opacity : 0
		}, duration, ease, function() {
			jQuery(this).remove();
		});
		
		oPrev.stop().animate({
			left : 0
			, opacity : 1
		}, duration, ease, function() {
			//vDiv.css("overflow", "");
		});
	} else {
		oView = jQuery("> :last-child", vDiv);
		oView.stop().animate({
			left : jQuery(window).width()
		}, duration, ease, function() {
			jQuery(this).remove();
		});
		
		jQuery("[class*=_LayoutPopupView]").stop().animate({
			opacity : 0
		}, duration, ease, function() {
			jQuery(this).remove();
		});
	}
};

String.prototype.ImageLoader = function(_func) {
	var func = function(_url) {
		//alert(_url);
		var oImg = jQuery("<img/>")
		.attr({
			"src" : _url
		})
		.css({
			"visibility" : "hidden"
		})
		.appendTo("body");
		
		if(oImg.width() > 0 && oImg.height() > 0) {
			_func(oImg);
		} else {
			oImg.load(function() {
				//alert(jQuery(this).width());
				if(jQuery(this).width() > 0 && jQuery(this).height() > 0) {
					_func(jQuery(this));
				} else {
					func(_url);
				}
			});
		}
	};
	func(this);
};

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
