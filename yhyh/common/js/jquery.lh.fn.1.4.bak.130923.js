jQuery.fn.Flash_Append_LH = function($_url, $_width, $_height) {
	if($(this).length == 0) {
		alert("element가 존재하지 않습니다.");
		return;
	}
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
	$(this).html(htmls);
};

jQuery.fn.Checkbox_All_Check_LH = function(target, type) {
	if($(this).length > 0) {
		$(this).change(function() {
			var This = this;
			if(!type) {
				$(this).get(0).checked = true;
			}
			$(target).each(function() {
				if(!type) {
					$(this).get(0).checked = $(this).get(0).checked ? false : true;
				} else {
					$(this).get(0).checked = $(This).get(0).checked;
				}
			});
		});
	}
};

jQuery.fn.Animate_LH = function(callBack) {
	var ani_params = new Array();
	$("*", this).each(function() {
		var params_text = $(this).data("lh-ani");
		if(params_text) {
			var params = String(params_text).split(",");
			var arr = String("=").Request_Object(params);
			arr["target"] = $(this);
			/*for(var s in arr) {
				alert(s + " : " + arr[s]);
			}*/
			ani_params.push(arr);
		}
	})
	
	
	var ani_arr = new Array();
	for(var i = 1; i <= ani_params.length; i++) {
		//alert(i);
		for(var p in ani_params) {
			//alert(p);
			var _d = ani_params[p];
			if(_d.no == i) {
				ani_arr.push(_d);
			}
		}
	}
	
	for(var p in ani_arr) {
		var _d = ani_arr[p];
		var _target = $(_d.target);
		if(_target.length == 0) return;
		
		var css_params = new Array();
		var to_params = new Array();
		
		var delay =	_d.delay ? Number(_d.delay) : 0;
		
		for(var s in _d) {
			var _default_d = _target.data("_default_d");
			if(!_default_d) _default_d = new Array();
			switch(s) {
				case "left":
				case "top":
				case "right":
				case "bottom":
				case "width":
				case "height":
					if(!_default_d[s] && _target.css(s) && _target.css(s) != "auto") {
						_default_d[s] = parseInt(_target.css(s));
					}
					if(_default_d[s]) {
						css_params[s] = _default_d[s] + Number(_d[s]);
						to_params[s] = _default_d[s];
					}
				break;
				case "opacity":
					_target.stop().fadeTo(0, Number(_d[s]));
					to_params[s] = 1;
				break;
			}
		}
		_target.data("_default_d", _default_d).css(css_params).stop().delay(delay).animate(to_params, Number(_d.duration), _d.ease, callBack);
	}
};
jQuery.fn.Swiper_LH = function(paging_target, autotime, speed) {
	if(!autotime) autotime = 4000;
	if(!speed) speed = 1000;
	var This = $(this);
	var _body = $("> .swiper-wrapper", This);
	var _Paging = $(paging_target);
	var m_visual_swiper;
	if(browser.name == "msie" && Number(browser.version) <= 8) {
		var interval_swiper = 0;
		var Lh_m_visual = function() {
			var count = 0;
			var body_w = 0;
			$("> *", _body).each(function(i) {
				count++;
				$("<span/>")
				.addClass(i == 0 ? "swiper-active-switch" : "swiper-pagination-switch")
				.appendTo(_Paging)
				.click(function() {
					Motion($(this).index());
				});
				
				body_w += $(this).width();
			});
			_body.css("width", body_w + "px");
			var choice_no = 0;
			var Motion = function(i) {
				choice_no = i;
				_body.stop().animate({
					left : $("> *:eq(" + i + ")", _body).position().left * -1
				}, 1000, "easeInOutCubic");
				//alert($("#lh-visual-body > div:eq(" + i + ")").position().left * -1);
				var choice_paging = $("> span:eq(" + i + ")", _Paging);
				choice_paging.removeClass("swiper-pagination-switch")
				.addClass("swiper-active-switch");
				
				choice_paging
				.siblings()
				.removeClass("swiper-active-switch")
				.addClass("swiper-pagination-switch");
			};
			var Loop = function() {
				if(choice_no + 1 < count) {
					choice_no++;
				} else {
					choice_no = 0;
				}
				Motion(choice_no);
			};
			
			interval_swiper = setInterval(Loop, 3000);
			
		};
		Lh_m_visual();
	} else {
		//alert(1);
		var identify = $(this).attr("id") ? "#" + $(this).attr("id") : "." + $(this).attr("class");
		/* main visual swiper */
		m_visual_swiper = new Swiper(identify, {
			pagination : paging_target,
			loop:true,
			autoPlay:autotime,
			speed:speed,
			//mode:'vertical',
			grabCursor: false
		});
		//alert(m_visual_swiper);
		
		//Clickable pagination
		$(".swiper-pagination-switch", paging_target).click(function(){
			m_visual_swiper.swipeTo($(this).index())
		});
	}
};

jQuery.fn.Copy_LH = function(str) {
	if(browser.name != "msie") {
		alert("현재 브라우저에서는 클립복사기능을 지원하지 않습니다.\n드래그 하여 Ctrl + C로 복사하시기 바랍니다.");
		return;
	}
	
	
	var target = $(this);
	if(target.length == 0) {
		alert("타겟이 없습니다.");
		return;
	}
	
	var doc = target.get(0).createTextRange();
	target.get(0).select();
	doc.execCommand('copy');
	if(str) alert(str);
};

jQuery.fn.Form_Submit_LH = function(func, action) {
	var This = $(this);
	if(This.length == 0) return false;
	if(This.get(0).tagName.toString().toLowerCase() != "form") return false;
	
	This
	.unbind("submit")
	.attr("method", "post");
	
	if(action) {
		This.attr("action", action);
	};
	alert(1);
	
	This.get(0).disabled = true;
	
	This.ajaxSubmit({
		statusCode : {
			400 : function() { alert("파일 내용이 잘못되었습니다."); }
			, 500: function() { alert("파일을 업로드할 수 없습니다."); }
		}
		, success : function(data) {
			This.get(0).disabled = false;
			func(data);
		}
	});
	return false;
};

jQuery.fn.Href_Pop_LH = function(func, type) {
	var url = $(this).data("url");
	if(!url) url = $(this).attr("href");
	if(!url) {
		alert("url 주소가 없습니다.");
		return false;
	}
	
	//url.toString().LayoutPop({ marginTop : 0, completeFunction : func, type : type });
	url.toString().lh_Block_Popup( { action : 'next' } );
	return false;
};

jQuery.fn.Css_Append_LH = function(url) {
	var _link = $("<link/>");
	_link.attr({
		"rel" : "stylesheet"
		, "href" : "css/main.css"
	}).appendTo(this);
};
jQuery.fn.RowsEllipsis_LH = function() {
	var oParent, oTarget = $(this);
	oTarget.css({
		"white-space" : "normal"
		, "display" : "inline-block"
		, "width" : "auto"
		, "vertical-align" : "middle"
		, "overflow" : "hidden"
		/*, white-space" : "nowrap"*/
		, "text-overflow" : "ellipsis"
		, "-o-text-overow" : "ellipsis"

	});
	
	oTarget.width(0);
	var resizeLoop = function() {
		var iw = oParent.innerWidth();
		oTarget.each(function(i) {
			var sObj = $(this).siblings();
			var sw = 0;
			if(sObj.length > 0) {
				sObj.each(function(i) {
					sw += $(this).width();
				});
			}
			//$(this).attr("testw", iw + " : " + sw);
			$(this).width(iw - 15 - sw);
		});
	};
	oParent = oTarget.parent();
	//oParent.css("display", "block");
	if(oParent.length > 0) {
		$(window).resize(resizeLoop);
	}
	resizeLoop();
	oTarget.css("white-space", "nowrap");
};

jQuery.fn.Register_Save_LH = function(register_back) {
	var _form = $(this);
	var url = _form.attr("action") ? _form.attr("action") : "";
	
	if(!url && typeof url == "undefined") return false;
	var params = {
	};
	
	$("input:text, input:hidden, input:radio, input:checkbox, input:file, input:password, select, textarea", _form).each(function(i) {
		if($(this).attr("type")) {
			switch($(this).attr("type").toString().toLowerCase()) {
				case "checkbox":
					params[$(this).attr("name")] = $(this).get(0).checked ? $(this).val() : "";
				break;
				case "radio":
					if(!params[$(this).attr("name")]) {
						params[$(this).attr("name")] = $(this).get(0).checked ? $(this).val() : "";
					}
				break;
				default:
					params[$(this).attr("name")] = $(this).val();
			}
		} else {
			params[$(this).attr("name")] = $(this).val();
		}
		//alert($(this).attr("name") + " : " + params[$(this).attr("name")]);
	});
	
	$.post(url, params, function(data) {
		register_back(data);
	}); // // IE의 경우 url 값을 안 넣으면 폼이 안날라감
	return false;
};

jQuery.fn.Tween3D_LH = function(option) {
	var ranges = ["scalex", "scaley", "scalez"
					, "rotationx", "rotationy", "rotationz"
					, "x", "y", "z", "angel", "perspective"];
					
	var oTransform = $(this).css("-webkit-transform");
	var transform = [1, 1, 1 /* scale */
					, 0, 0, 0 /* rotation */
					, 0, 0, 0 /* translate3d(px) */
					, 0 /* angel(deg) */
					, 0 /* perspective */];
	if($(this).data("transform-lh")) {
		transform = $(this).data("transform-lh");
	}
	for(var key in option) {
		for(var r in ranges) {
			if(key.toString().toLowerCase() == ranges[r]) {
				switch(key.toString().toLowerCase()) {
					default:
						transform[r] = option[key];
				}
			}
		}
	}
	
	$(this).css({
		"-webkit-transform" : "perspective(" + transform[10] + ") translate3d(" + transform[6] + "px, " + transform[7] + "px, " + transform[8] + "px) scale3d(" + transform[0] + ", " + transform[1] + ", " + transform[2] + ") rotate3d(" + transform[3] + ", " + transform[4] + ", " + transform[5] + ", " + transform[9] + "deg)"
		, "-webkit-transform-style" : "preserve-3d"
		, "-webkit-transform-origin" : "50% 50% 50%"
		, "transform" : "perspective(" + transform[10] + ") translate3d(" + transform[6] + "px, " + transform[7] + "px, " + transform[8] + "px) scale3d(" + transform[0] + ", " + transform[1] + ", " + transform[2] + ") rotate3d(" + transform[3] + ", " + transform[4] + ", " + transform[5] + ", " + transform[9] + "deg)"
		, "transform-style" : "preserve-3d"
		, "transform-origin" : "50% 50% 50%"
	});
};

jQuery.fn.Cube3D_LH = function() {
	var count = $("> *", this).css({
		"display" : "block"
		, "position" : "absolute"
	}).length;
	if(count != 6) {
		alert("6개의 면이 필요합니다.");
		return;
	}
	
	var h = $("> *:eq(2)", this).height() * 0.5;
	var w = $("> *:eq(0)", this).width() * 0.5;
	var z = $("> *:eq(2)", this).width() * 0.5;
	
	var values = [
		{rotationX : 1,		rotationY : 0, angel : 90,		x : 0,		y : -h,		z : 0}
		, {rotationX : 1,	rotationY : 0, angel : -90,	x : 0,		y : h,		z : 0}
		, {rotationX : 0,	rotationY : 1, angel : -90,		x : -w,		y : 0,		z : 0}
		, {rotationX : 0,	rotationY : 1, angel : 90,		x : w,		y : 0,		z : 0}
		, {rotationX : 0,	rotationY : 1, angel : 180,		x : 0,		y : 0,		z : -z}
		, {rotationX : 0,	rotationY : 1, angel : 0,		x : 0,		y : 0,		z : z}
	];
	
	$("> *", this).each(function(i) {
		$(this).css({
		}).Tween3D_LH(values[i]);
	});

}
/*jQuery.fn.Tween3D_LH = function(type, option) {
	var oMatrix = $(this).Get_Matrix3D_LH();
	var ranges = ["scalex", "skewy", "zx", "rotationx"
					, "skewx", "scaley", "zy", "rotationy"
					, "xz", "yz", "scalez", "rotationz"
					, "x", "y", "z", "zoom"];
					
	//alert(oMatrix);
	for(var key in option) {
		//alert(key);
		for(var r in ranges) {
			switch(type.toString().toLowerCase()) {
				case "from":
				break;
				case "to":
					if(key.toString().toLowerCase() == ranges[r]) {
						oMatrix[r] = oMatrix[r] + option[key];
					}
				break;
				default:
					if(key.toString().toLowerCase() == ranges[r]) {
						oMatrix[r] = option[key];
					}
			}
		}
	}
	//alert(oMatrix);
	$(this).Matrix3D_LH(oMatrix);
};
*/
jQuery.fn.Get_Matrix3D_LH = function() {
	var out = new Array();
	var new_matrix = [1, 0, 0, 0
					, 0, 1, 0, 0
					, 0, 0, 1, 0
					, 0, 0, 0, 1];
	out = $(this).css("-webkit-transform") != "none" ? $(this).css("-webkit-transform").toString().replace(/matrix3d\(/i, "").replace(/\)/i, "").split(",") : new_matrix;
	
	if(out.length != 16) out = new_matrix;
	
	return out;
}
jQuery.fn.Matrix3D_LH = function(oMatrix) {
	var matrix = "";
	if(!oMatrix) oMatrix = [1, 0, 0, 0
							, 0, 1, 0, 0
							, 0, 0, 1, 0
							, 0, 0, 0, 1];
							
	var ranges = ["", "xx", "yx", "zx", "px"
					, "xy", "yy", "zy", "py"
					, "xz", "yz", "zz", "pz"
					, "tx", "ty", "tz", "tp"];
	
	for(var i = 0; i < oMatrix.length; i++) {
		var value = 0;
		switch(i) {
			case 0:
			case 1:
			case 2:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
				value = Number(oMatrix[i]);
				//alert(oMatrix[i]);
			break;
			case 3:
			case 7:
			case 11:
				//alert(ranges[i] + ":" + $("#" + ranges[i]).get(0).value);
				value = Number(oMatrix[i]) * 0.0001;
			break;
			case 15:
				value = Number(oMatrix[i]);
			break;
			default:
				value = Number(oMatrix[i]);
		}
		matrix += (i == 0) ? value : ", " + value;
	}
	//alert(matrix);
	//alert($(this).parent().length);
	if($(this).parent().length > 0) {
		$(this).css({
			"-webkit-perspective" : "250"
			, "-webkit-perspective-origin-x" : "50%"
			, "-webkit-perspective-origin-y" : "50%"
			, "perspective" : "250"
			, "perspective-origin-x" : "50%"
			, "perspective-origin-y" : "50%"
		});
	}
	$(this).attr("count", matrix).css({
		"transform-style" : "preserve-3d"
		, "-webkit-transform-style" : "preserve-3d"
		, "-webkit-transform" : "matrix3d(" + matrix + ")"
		, "transform" : "matrix3d(" + matrix + ")"
	});
};

jQuery.fn.Full_Resize = function(type) {
	var This = $(this);
	var Page_Loader_Loop = function() {
		if(type == "width" || !type) {
			if(!$("body").data("dh")) {
				$("body").data("dh", $("body").height());
			}
			var plh = $(window).height() < Number($("body").data("dh")) ? Number($("body").data("dh")) : $(window).height();
			This.css("min-height", (plh) + "px");
		}
		if(type == "width" || !type) {
			if(!$("body").data("dw")) {
				$("body").data("dw", $("body").width());
			}
			var plw = $(window).width() < Number($("body").data("dw")) ? Number($("body").data("dw")) : $(window).width();
			This.css("min-width", (plw) + "px");
		}
	}
	
	$(window).resize(Page_Loader_Loop);
	Page_Loader_Loop();
}
jQuery.fn.Scroll_Object = function(space_h) {
	
	var _target = $(this);
	if(_target.length == 0) return;
	
	$(window).scroll(function(e) {
		if(!$("body").data("dh")) {
			$("body").data("dh", $("body").height());
		}
		
		if(!_target.data("dpt")) {
			_target.data({
				"dpt" : _target.position().top
				, "old_st" : $(window).scrollTop()
			})
			.attr({
				"dpt" : _target.position().top
			}); // + parseInt(_target.css("margin-top") ? _target.css("margin-top") : 0));
		}
		
		var dpt = Number(_target.data("dpt"));
		var h = Number($("body").data("dh"));
		var st = $(window).scrollTop();
		var ost = Number(_target.data("old_st"));
		var wh = $(window).height();
		var th = _target.height();
		var mt = 0;
		var tt = h - dpt - th + space_h;
		var th_space = _target.height() > $(window).height() && st > ost ? $(window).height() - _target.height() : 0;
		
		mt = st - dpt + th_space < 0 ? 0 : st - dpt;
		
		if(mt > tt) mt = tt;
		
		_target.stop().animate({
			"margin-top" : mt
		}, {
			duration : 400
			, step : function(e, proc) {
				var mt = parseInt($(this).css("margin-top"));
				_target.attr({
					"old_st" : $(window).scrollTop()
					, "body_h" : h
					, "target_h_top" : th + tt + space_h + dpt
				})
				.data({
					"old_st" : st
				});
				if(mt > tt) {
					$(this).stop(false, false);
				}
			}
		});
		
	});
};
jQuery.fn.Clone_Object = function(type) {
	var cln = $(this).clone(true);
	if(!type) $(this).remove();
	return cln;
};
jQuery.fn.Full_Page_Resize = function(target, space_w, limit_w) {
	var _target = $(target);
	if(!space_w) space_w = 0;
	
	if(_target.length == 0) return;
	
	var This = $(this);
	var sib = This.siblings();
	var _parent = This.parent();
	
	var Page_Resize_Loop = function() {
		if(!_parent.data("dw")) {
			_parent.data("dw", _parent.width());
		}
		var w = _target.width() - sib.width()
		
		if(w + space_w > limit_w || !limit_w) {
			This.css("width", (w + space_w) + "px");
		} else {
			This.css("width", (limit_w) + "px");
		}
	}
	
	$(window).resize(Page_Resize_Loop);
	Page_Resize_Loop();
}

jQuery.fn.Target_Height_Resize = function(target, space_h) {
	var _target = $(target);
	if(!space_h) space_h = 0;
	if(_target.length == 0) return;
	
	var This = $(this);
	
	var Page_Resize_Loop = function() {
		var h = _target.height();
		//alert(h);
		This.css("min-height", (h + space_h) + "px");
	}
	
	$(window).resize(Page_Resize_Loop);
	Page_Resize_Loop();
}

	
jQuery.fn.FullDown_Slide = function(type) {
	var _target = $(this);
	if(_target.length == 0) return;
	
	_target.css({
		position : "absolute"
		, top : _target.css("top").toString().toLowerCase() == "auto" ? "0px" : _target.css("top")
	});
	
	_target.data("dt", _target.data("dt") || _target.data("dt") == 0 ? _target.data("dt") : parseInt(_target.css("top")));
	var dt = Number(_target.data("dt"));
	var dh = _target.height();
	var mt = dt;
	var duration = 500;
	
	switch(type) {
		case "up":
			mt = dh * -1;
			duration = 500;
		break;
		case "down":
			mt = dt;
		break;
		default:
	}
	
	_target.stop(true, false).animate({
		top : mt
	}, duration, "easeInOutCubic", function() {});
}

//(function() {
jQuery.fn.FormAphabat_NumberCheck = function(type, msg_type) {
	var str = $(this).val();
	var count = str.length;
	var check = 0;
	var out = "";
	for(var i = 0; i < count; i++) {
		if((str.charCodeAt(i) >= 48 && str.charCodeAt(i) <= 57)
		 || (str.charCodeAt(i) >= 65 && str.charCodeAt(i) <= 90)
		 || (str.charCodeAt(i) >= 97 && str.charCodeAt(i) <= 122)
		 ) {
			out += "" + str.charAt(i);
		} else {
			check++;
			if(!type) {
				if(msg_type) alert("영문 또는 숫자만 입력하실 수 있습니다.");
				$(this).focus();
				return true;
			}
		}
	}
	if(type && check > 0) {
		if(msg_type) alert("영문 또는 숫자만 입력하실 수 있습니다.");
		$(this).val(out);
		return true;
	}
	return false;
};
jQuery.fn.Number_Format_Check = function() {
	var str = $(this).val().toString();
	$(this).val(str.replace(/,/gi, ""));
	$(this).FormInputNumberCheck(true);
	str = $(this).val().toString();
	$(this).val(str.Number_Format());
}
jQuery.fn.FormInputNumberCheck = function(type, msg_type) {
	var str = $(this).val();
	var count = str.length;
	var check = 0;
	var out = "";
	for(var i = 0; i < count; i++) {
		if(isNaN(str.charAt(i))) {
			check++;
			if(!type) {
				if(!msg_type) alert("숫자만 입력하실 수 있습니다.");
				else alert(msg_type);
				$(this).focus();
				return true;
			}
		} else {
			out += "" + str.charAt(i);
		}
	}
	if(type && check > 0) {
		if(!msg_type) alert("숫자만 입력하실 수 있습니다.");
		else alert(msg_type);
		$(this).val(out);
		return true;
	}
	return false;
};
jQuery.fn.FormInputCheck = function(obj, type) {
	if($(this).length == 0 || !obj) {
		//alert("필드 정보가 잘못되었습니다.");
		return false;
	}
	
	var check = ($(this).attr("alt") == "필수" || type == true);
	var msg = (obj && obj.msg) ? obj.msg : "";
	var style = (obj && obj.type) ? obj.type : "";
	var max_length = (obj && obj.max_length) ? Number(obj.max_length) : "";
	var min_length = (obj && obj.min_length) ? Number(obj.min_length) : "";
	var same = (obj && obj.same) ? obj.same : "";
	var no_eng_num = (obj && obj.no_eng_num) ? obj.no_eng_num : "";
	var value = $(this).val().toString();
	
	// 자신의 값이 없으면 경고 메세지
	if(!value && check) {
		alert(msg);
		$(this).focus();
		return true;
	}
	
	// 숫자가 아닌 경우 체크해서 경고 메세지
	if(check && obj.only_number) {
		if($(this).FormInputNumberCheck(true)) {
			alert(obj.only_number);
			$(this).focus();
			return true;
		}
	}
	
	// 자신의 문자수가 지정된 숫자 보다 낮을때 경고 메세지
	if(check && obj.length_limit && obj.length_limit.max != "" && obj.length_limit.max > 0) {
		if(value.length < obj.length_limit.max) {
			alert(obj.length_limit.max_msg);
			$(this).focus();
			return true;
		}
	}
	
	// 자신의 문자수가 지정된 숫자 보다 높을때 경고 메세지
	if(check && obj.length_limit && obj.length_limit.min != "" && obj.length_limit.min > 0) {
		if(value.length > obj.length_limit.min) {
			alert(obj.length_limit.min_msg);
			$(this).focus();
			return true;
		}
	}
	
	// 타겟 폼테그 값과 비교해서 같지 않으면 경고 메세지
	if(obj.same && obj.same.target) {
		if($(obj.same.target).val() != $(this).val()) {
			alert(obj.same.msg);
			$(this).focus();
			return true;
		}
	}
	
	// 영문/숫자가 아닌 경우 체크해서 경고 메세지
	if(obj.no_eng_num && obj.no_eng_num.msg) {
		if($(this).FormAphabat_NumberCheck(true)) {
			alert(obj.no_eng_num.msg);
			$(this).focus();
			return true;
		}
	}
	return false;
};
jQuery.fn.Form_Submit_LH = function(func) {
	var action, This = jQuery(this);
	if(This.length == 0) return;
	if(This.get(0).tagName.toString().toLowerCase() != "form") {
		alert("폼 태그가 존재하지 않습니다.");
		return false;
	}
	
	if(!This.attr("action")) {
		alert("action 값이 존재하지 않습니다");
		return false;
	}
	
	action = This.attr("action");
	
	This
	.unbind("submit")
	.attr({
		"method" : "post"
	});
	
	This.ajaxSubmit({
		statusCode : {
			400 : function() { alert("파일 내용이 잘못되었습니다."); }
			, 500: function() { alert("파일을 업로드할 수 없습니다."); }
		}
		, success : func
	});
	return false;
};
