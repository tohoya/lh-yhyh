(function() {
	$(document).ready(function() {
		var titles = $("title").html();
		var list_title = $("#yhyh_board_main_body > h1");
		if(list_title.length > 0) {
			if(list_title.text() != "") {
				titles += " - " + list_title.text();
				document.title = titles;
			}
		}
		
		var view_title = $(".yhyh_view_title h1");
		if(view_title.length > 0) {
			if(view_title.text() != "") {
				titles += " - " + view_title.text();
				document.title = titles;
			}
		}
	});
})();


function Yh_PasteHTML(target, sHTML) {
	oEditors.getById[target].exec("PASTE_HTML", [sHTML]);
}

function Pop_Design_form_Init(target) {
	$("#yhb_board_pass_view").change(function() {
		switch($(this).get(0).checked) {
			case true:
				var o = $("#yhb_board_pass").clone();
				o.attr("type", "text");
			break;
			case false:
				var o = $("#yhb_board_pass").clone();
				o.attr("type", "password");
			break;
		}
		var oLabel = $("label[for=yhb_board_pass]");
		$("#yhb_board_pass").replaceWith(o);
		$("#yhb_board_pass").focus(function() {
			oLabel.css("visibility", "hidden");
		})
		.blur(function() {
			if(!$(this).val()) {
				oLabel.css("visibility", "visible");
			}
		});
	});
	DesignFormInit(target);
}
function Alert_Message(msg) {
	alert(msg);
}

function ListRowsEllipsis_title(target) {
	var oParent, oTarget = $(target);
	oTarget.css("white-space", "normal");
	oTarget.width(0);
	var resizeLoop = function() {
		var iw = oParent.innerWidth();
		oTarget.attr("testw", iw);
		oTarget.width(iw - 15);
	};
	oParent = oTarget.parent();
	//oParent.css("display", "block");
	if(oParent.length > 0) {
		$(window).resize(resizeLoop);
	}
	resizeLoop();
	oTarget.css("white-space", "nowrap");
}

function DesignFormInit($target) {
	$("input:text, input:password", $target).each(function(i) {
		var id = $(this).attr("id");
		$(this).data("dw", $(this).outerWidth());
		if(id) {
			var oLabel = $("label[for=" + id + "]");
			if(oLabel.length > 0) {
				if(oLabel.parent().length > 0) {
					oLabel.parent().css({
						"position" : "relative"
						, "display" : browser.name == "mozilla" ? "block" : ""
					});
				}
				var top = $(this).position().top + 0;
				var left = $(this).position().left + 5;
				oLabel.css({
					"position" : "absolute"
					, "top" : top + "px"
					, "left" :left + "px"
					, "color" : "#AAAAAA"
					, "text-decoration" : "none"
					, "overflow" : "hidden"
					, "white-space" : "nowrap"
					//, "visibility" : $(this).val() ? "hidden" : "visible"
				});
				if($(this).val()) oLabel.fadeOut(0);
				else oLabel.fadeIn(0);
				
				oLabel.css({
					"visibility" : "visible"
				});
				oLabel.data("dw", oLabel.outerWidth());
				
				$(this).focus(function() {
					oLabel.fadeOut(200);
					//oLabel.css("visibility", "hidden");
				})
				.change(function() {
					if(!$(this).val()) {
						oLabel.fadeIn(200);
					} else {
						oLabel.fadeOut(200);
					}
				})
				.blur(function() {
					if(!$(this).val()) {
						oLabel.fadeIn(200);
						//oLabel.css("visibility", "visible");
					}
				});
			}
		}
	});
}

function Email_Type_Change(type, target, sel) {
	var oSel = (sel) ? jQuery(sel) : null, time = 0;
	if(!sel) {
		oSel = jQuery("#" + target + "_l");
		time = 0;
	} else {
		if(oSel == null) return;
		type = (oSel.val() != "direct") ? false : true;
		time = 300;
	}
	var direct = jQuery("#" + target + "_m");
	var oLabel = jQuery("label[for=" + target + "_m]");
	if(direct.length == 0) return;
	if(type) {
		direct.css({
			"visibility" : "visible"
			, "display" : ""
		})
		.stop().animate({
			opacity : 1
			, width : direct.data("dw")
		}, time, "easeOutCubic", function() {
		});
		if(oLabel.length > 0) {
			oLabel.stop().animate({
				opacity : 1
				, width : oLabel.data("dw")
			}, time, "easeOutCubic");
		}
	} else {
		direct.stop().animate({
			opacity : 0
			, width : 0
		}, time, "easeOutCubic", function() {
			direct.css({
				"visibility" : "hidden"
				, "display" : "none"
			});
		});
		if(oLabel.length > 0) {
			oLabel.stop().animate({
				opacity : 0
				, width : 0
			}, time, "easeOutCubic");
		}
	}
}

function Content_frame_resize(h, target, img_width_type) {
	if(img_width_type == "auto") {
		var iframe = $(target);
		var iframe_body = iframe.get(0).contentWindow.document.body;
		$("img", iframe_body).each(function() {
			if(iframe.width() < $(this).width()) {
				$(this).css("max-width", iframe.width() - 5);
			}
		});
	}
	jQuery(target).height(h); //.parent().css("display", "none");
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
					$(this).focus();
					return true;
				}
			} else {
				out += "" + str.charAt(i);
			}
		}
		if(type && check > 0) {
			if(!msg_type) alert("숫자만 입력하실 수 있습니다.");
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
	jQuery.fn.YhyhFormSubmit = function(action, func) {
		var This = jQuery(this);
		if(This.length == 0) return;
		if(This.get(0).tagName.toString().toLowerCase() != "form") return;
		
		This
		.unbind("submit")
		.attr({
			"method" : "post"
			, "action" : action ? action : This.attr("action")
		});
		
		if(browser.name == "msie" && false) {
			This.ajaxForm(function(responseText, statusText, xhr, $form) {
				alert(statusText);
				func(responseText);
			});
			//alert(1);
			/*.submit(function() {
				return false;
			});*/
		} else {
			This.ajaxSubmit({
				statusCode : {
					400 : function() { alert("파일 내용이 잘못되었습니다."); }
					, 500: function() { alert("파일을 업로드할 수 없습니다."); }
				}
				, success : func
			});
			//alert(This.ajaxSubmit + " : " + browser.name);
		}
		return false;
	};
	function LHtmlEditerClass() {
	}
	LHtmlEditerClass.prototype = {
		// 변수 설정
		dirDefault : $_yhyh_web + "/common/",
		dirImage : $_yhyh_web + "/common/image/",
		target : null,
		target_id : "",
		outer : null,
		editorDoc : null,
		oLayout : null,
		oIframe : null,
		oRange : null,
		oElement : null,
		toolbar : null,
		fontSize : 1,
		fontName : 0,
		colorPanel : new Array(),
		fontPx : new Array(),
		firstHtml : "",
		defaultCss : {},
		frameOuterWidth : 0,
		frameOuterHeight : 0,
		editorBody : null,
		editorDiv : null,
		oEditors : null,
		files : new Array(),
		
		// 초기화 및 Html 편집기 삽입
		Init : function(_target, _outer, _files, _width, _height, _oEditors) {
			// 변수 초기화
			this.dirImage = this.dirDefault + "image/";
			this.oLayout = null;
			this.oEditors = _oEditors;
			this.editorDoc = null;
			this.editorBody = null;
			this.editorDiv = null;
			this.oIframe = null;
			this.frameOuterWidth = (_width) ? _width : 650;
			this.frameOuterHeight = (_height) ? _height : 350;
			this.target = null;
			this.outer = (_outer) ? jQuery(_outer) : null;
			this.fontPx["11px"] = "8pt";
			this.fontPx["12px"] = "9pt";
			this.fontPx["13px"] = "10pt";
			this.fontPx["16px"] = "12pt";
			this.fontPx["19px"] = "14pt";
			this.fontPx["24px"] = "18pt";
			this.fontPx["32px"] = "24pt";
			this.fontPx["48px"] = "36pt";
			this.defaultCss = {
				"margin" : "0"
				, "padding" : "0"
				, "border" : "0"
				, "outline" : "0"
				, "font-size" : "9pt"
				, "vertical-align" : "baseline"
				, "line-height" : "1"
			};
			
			
			if(_target) {
				this.target = _target;
				if(_target.indexOf("#") > -1) {
					this.target_id = _target.substr(_target.indexOf("#") + 1);
				} else if(_target.indexOf(".") > -1) {
					this.target_id = _target.substr(_target.indexOf(".") + 1);
				} else {
					this.target_id = _target;
				}
			}
			
			if(_files) {
				this.files = String(_files).split("_***_");
			}
			
			this.Html_Init();
		}
		
		, Layout_Init : function() {
			if(this.target == null) {
				document.write(this.oLayout.get(0).outerHTML);
			} else {
				if(jQuery(this.target).length > 0) jQuery(this.target).html("");
				this.oLayout.appendTo(this.target);
			}
		}
		
		, Html_Layout : function() {
			var oIframe, oTextarea, oInput, oLable, oDiv, oDl, oDd, oA, oImg, count, i, jCount, j, This
			, oFileLayout, oFileSelect, oFileBtnUpload;
			This = this;
			This.oLayout = jQuery("<div/>")
			.css(This.defaultCss);
			
			This.oLayout.attr({
				"class" : "LHtmlEditers"
			})
			.css({
				"display" : "block"
				, "width" : "100%"
				, "max-width" : "850px"
				, "min-width" : "650px"
				, "position" : "relative"
			});
			
			var oFileBtnField = jQuery("<input/>")
			.attr({
				"type" : "file"
				, "name" : "_upload_file_" + This.target_id
				, "id" : "_upload_file_" + This.target_id
			})
			.hover(function() {
				oFileBtnUpload.addClass("fdnButtonOver");
			}, function() {
				oFileBtnUpload.removeClass("fdnButtonOver");
			})
			.css({
				"width" : "88px"
				, "opacity" : "0"
				, "filter" : "alpha(opacity=0)"
				, "cursor" : "pointer"
				//, "display" : "none"
			});
			
			oFileBtnUpload = jQuery("<label/>")
			.attr({
				"for" : "_upload_file_" + This.target_id
			})
			.addClass("a_button")
			.css({
				"position" : "absolute"
				, "display" : "block"
				, "padding" : "7px 10px"
				, "height" : "10px"
			})
			.html("파일올리기");
			
			var oFileBtnAppend = jQuery("<input/>")
			.attr({
				"type" : "button"
			})
			.click(function() {
				var value = oFileSelect.val();
				var sel = oFileSelect.get(0);
				var index = sel.selectedIndex;
				
				if(index < 0) {
					alert("선택된 파일이 없습니다.");
					return;
				}
				var arr = String(value).split("_**_");
				var file = {
					url : arr[0]
					, save : arr[1]
					, name : arr[2]
					, size : arr[3]
					, ext : arr[4]
					, seq : arr[5]
					, type : arr[6]
				};
				
				var sHTML;
				switch(String(file.ext).toLowerCase()) {
					case "png":
					case "jpeg":
					case "jpg":
					case "gif":
					case "bmp":
					case "pic":
						sHTML = "<img src=\"" + file.url + "\" alt=\"" + file.name + "\">";
					break;
					default:
						var _oP = $("<p/>").append(
							jQuery("<a/>")
							.attr({
								"href" : $_yhyh_web + "/?_module=download_proc&_file_name=" + file.save
								, "title" : file.name + " (" + This.Html_Byte_Set(file.size, 2) + ") 다운로드"
							})
							.html(file.name + " (" + This.Html_Byte_Set(file.size, 2) + ")")
						);
						sHTML = _oP.html();
				}

				Yh_PasteHTML(This.outer.attr("id"), sHTML);
			})
			.css({
				"position" : "absolute"
				, "left" : "100px"
			})
			.val("파일본문넣기");
			
			var oFileBtnLInk = jQuery("<input/>")
			.attr({
				"type" : "button"
			})
			.click(function() {
				var value = oFileSelect.val();
				var sel = oFileSelect.get(0);
				var index = sel.selectedIndex;
				
				if(index < 0) {
					alert("선택된 파일이 없습니다.");
					return;
				}
				
				var arr = String(value).split("_**_");
				var file = {
					url : arr[0]
					, save : arr[1]
					, name : arr[2]
					, size : arr[3]
					, ext : arr[4]
					, seq : arr[5]
					, type : arr[6]
				};
				var _oA = jQuery("<a/>")
				.attr({
					"href" : $_yhyh_web + "/?_module=download_proc&_file_name=" + file.save
					, "title" : file.name + "(" + This.Html_Byte_Set(file.size, 2) + ") 다운로드"
				})
				.html(file.name + "(" + This.Html_Byte_Set(file.size, 2) + ")")
				
				Yh_PasteHTML(This.outer.attr("id"), _oA.html());
			})
			.css({
				"position" : "absolute"
				, "left" : "100px"
			})
			.val("본문링크넣기");
			
			var oFileBtnDelete = jQuery("<input/>")
			.attr({
				"type" : "button"
			})
			.css({
				"position" : "absolute"
				, "left" : "202px"
			})
			.click(function() {
				var sel = oFileSelect.get(0);
				var index = sel.selectedIndex;
				var value = oFileSelect.val();
				
				if(index < 0) {
					alert("선택된 파일이 없습니다.");
					return;
				}
				
				var arr = String(value).split("_**_");
				var oOpt = jQuery(sel.options[index]);
				var file = {
					url : arr[0]
					, save : arr[1]
					, name : arr[2]
					, size : arr[3]
					, ext : arr[4]
					, seq : arr[5]
					, type : arr[6]
				};
				if(This.outer.length > 0) {
					var text = This.outer.val().toString();
					if(text.indexOf(file.url) > -1) {
						if(!confirm("본문에서 사용중인 파일입니다. 삭제를 진행 하시겠습니까?\r삭제하시면 본문에 이미지 없음 표시가 나타납니다.")) {
							return false;
						}
					}
				}
				
				if(file.type == "yes") {
					jQuery.post($_yhyh_web + "/", { "_module" : "file_delete_proc", "_seq" : file.seq, "_file" : file.url }, function(data) {
						oOpt.remove();
						This.Html_Byte_View();
					});
				} else {
					var oDelete = jQuery("#file_delete_" + This.target_id);
					if(oDelete.length > 0) {
						oDelete.val(oDelete.val() ? oDelete.val() + "," + file.seq : file.seq);
					}
					oOpt.remove();
					This.Html_Byte_View();
				}
				This.Html_Image_Viewer(oFileView, "");
			})
			.val("선택파일삭제");
			
			oFileView = jQuery("<span/>")
			.css(This.defaultCss)
			.css({
				"width" : "90px"
				, "position" : "absolute"
				, "top" : "5px"
				, "left" : "0px"
				, "height" : "90px"
				, "display" : "block"
				, "border-radius" : "3px"
				, "background" : "#DDDDDD"
				, "border" : "1px solid #CCCCCC"
				, "overflow" : "hidden"
			});

			
			oFileSelect = jQuery("<select/>")
			.attr({
				 "size" : "4"
				 , "id" : "file_select" + This.target_id
				 , "multiple" : ""
			})
			.css(This.defaultCss)
			.css({
				"width" : "310px"
				, "height" : "92px"
				, "border" : ""
				, "display" : "block"
				, "position" : "absolute"
				, "top" : "5px"
				, "left" : "95px"
			});
			
			oFileLayout = jQuery("<div/>")
			.css(This.defaultCss)
			.css({
				"width" : "100%"
				, "padding" : "7px 0px 0px"
				, "display" : "block"
				, "position" : "relative"
				, "height" : "92px"
			})
			.append(oFileView)
			.append(oFileSelect)
			.append(
				jQuery("<div/>")
				.css(This.defaultCss)
				.css({
					"width" : "290px"
					, "text-align" : "left"
					, "position" : "absolute"
					, "top" : "5px"
					, "left" : "408px"
					, "height" : "70px"
					, "display" : "block"
					, "padding" : "10px"
					, "border-radius" : "3px"
					, "background" : ""
					, "border" : "1px solid #DDDDDD"
				})
				.append(oFileBtnUpload)
				.append(oFileBtnField)
				.append(oFileBtnAppend)
				//.append(oFileBtnLInk)
				.append(oFileBtnDelete)
				.append(
					jQuery("<p/>")
					.attr("id", "byte_view" + This.target_id)
					.css({
						"width" : "290px"
						, "text-align" : "left"
						, "position" : "absolute"
						, "top" : "44px"
						, "left" : "10px"
						, "display" : "block"
						, "line-height" : "1.2em"
						, "color" : "#666666"
					})
					.html("문서 첨부 제한 : <span>0Byte</span>/ 100.00Mbyte<br>파일 제한 크기 : 100.00Mbyte<br>허용 확장자 : *.*"))
			);
			
			This.oLayout.append(oFileLayout);
			
			
			This.Layout_Init();
			
			count = This.files.length;
			if(count > 0) {
				for(i = 0; i < count; i++) {
					var value = This.files[i];
					var arr = String(value).split("_**_");
					var file = {
						url : arr[0]
						, save : arr[1]
						, name : arr[2]
						, size : arr[3]
						, ext : arr[4]
						, seq : arr[5]
						, type : arr[6]
					};
					if(i == 0) {
						This.Html_Image_Viewer(oFileView, file);
					}
					
					var oOption = jQuery("<option/>").attr("value", file.url + "_**_" + file.save + "_**_" + file.name + "_**_" + file.size + "_**_" + file.ext + "_**_" + file.seq + "_**_" + file.type).html(file.name + "(" + This.Html_Byte_Set(file.size, 2) + ")");
					oFileSelect.append(oOption);
				}
				var sel = oFileSelect.get(0);
				sel.selectedIndex = 0;
				This.Html_Byte_View();
			}
			
			var f = This.oLayout.parents("form");
			if(f.length == 0) {
				f = jQuery("<form/>")
				.attr({
					"name" : "forms"
					, "action" : $_yhyh_web + "/module/file_upload_proc.php"
					, "method" : "POST"
					, "enctype" : "multipart/form-data"
				})
				.appendTo(This.oLayout);
			}
			
			oFileSelect.change(function() {
				var value = jQuery(this).val();
				var arr = String(value).split("_**_");
				var file = {
					url : arr[0]
					, save : arr[1]
					, name : arr[2]
					, size : arr[3]
					, ext : arr[4]
					, seq : arr[5]
					, type : arr[6]
				};
			
				This.Html_Image_Viewer(oFileView, file);
			});
			
			oFileBtnField
			.css({
				"position" : "absolute"
				, "width" : (oFileBtnUpload.outerWidth()) + "px"
				, "height" : (oFileBtnUpload.outerHeight()) + "px"
			})
			.mousedown(function() {
			})
			.change(function() {
				oFileBtnUpload.val("올리는중..");
				oFileBtnUpload.get(0).disabled = true;
				oFileBtnField.css("display", "none");
				var oInput = jQuery("<input/>")
				.attr({
					"type" : "text"
					, "name" : "_upload_type"
				})
				.val("_upload_file_" + This.target_id);
				//alert(This.target_id);
				f.append(oInput);
				f.get(0)._module.value = "file_upload_proc"
				f.YhyhFormSubmit($_yhyh_web + "/", function(data) {
					//alert(data);
					oFileBtnUpload.val("파일올리기");
					oFileBtnUpload.get(0).disabled = false;
					oFileBtnField.css("display", "block");
					//jQuery(this).replaceWith(fileField);
					
					oInput.remove();
					
					var oP = jQuery("<p/>");
					oP.html(data);
					switch(jQuery("> p", oP).attr("class")) {
						case "error":
						break;
						case "complete":
							var sel = oFileSelect.get(0);
							var sel_len = sel.length;
							var file = {
								url : jQuery("> p > span:eq(0)", oP).html()
								, save : jQuery("> p > span:eq(1)", oP).html()
								, name : jQuery("> p > span:eq(2)", oP).html()
								, size : jQuery("> p > span:eq(3)", oP).html()
								, ext : jQuery("> p > span:eq(4)", oP).html()
								, seq : jQuery("> p > span:eq(5)", oP).html()
								, type : "yes"
							};
							
							This.Html_Image_Viewer(oFileView, file);
							
							var oOption = jQuery("<option/>").attr("value", file.url + "_**_" + file.save + "_**_" + file.name + "_**_" + file.size + "_**_" + file.ext + "_**_" + file.seq + "_**_" + file.type).html(file.name + "(" + This.Html_Byte_Set(file.size, 2) + ")");
							oFileSelect.append(oOption);
							//alert(sel_len);
							sel.selectedIndex = sel_len;
							
							This.Html_Byte_View();
						break;
					}
				});
			});
			//alert(oFileBtnUpload.outerWidth());
			
			jQuery(window).unload(function() {
				var oWrites = jQuery("#write_complete_" + This.target_id);
				if(!oWrites.val()) {
					var oAdd = jQuery("#file_add_" + This.target_id);
					
					//alert(oAdd.val());
					jQuery.get($_yhyh_web + "/", { "_module" : "file_delete_proc", "_delete_type" : "write_out", "_seqs" : oAdd.val() }, function(data) {
						alert(data);
					});
				}
				//alert("END");
			});

		}
		
		, Html_Image_Viewer : function(target, file) {
			jQuery("> img", target).each(function(i) {
				jQuery(this).stop().animate({
					opacity : 1
					, left : target.outerWidth() * -1
				}, 300, "easeOutCubic", function() {
					jQuery(this).remove();
				})
			});
			if(!file || (file && !file.ext)) return;
			switch(file.ext.toLowerCase()) {
				case "png":
				case "jpg":
				case "jpeg":
				case "bmp":
				case "gif":
					var img = jQuery("<img/>")
					.css({
						"position" : "absolute"
						, "display" : "none"
						, "left" : target.outerWidth() + "px"
						, "width" : target.innerWidth() + "px"
						, "height" : target.innerHeight() + "px"
					});
					target.append(img);
					//img.fadeTo(0, 0)
					
					img.get(0).src = file.url;
					img.css("display", "block")
					.stop().animate({
						opacity : 1
						, left : 0
					}, 300, "easeOutCubic");
				break;
			}
		}
		
		, Html_Byte_View : function() {
			var out = 0, This = this, oFileSelect = jQuery("#file_select" + This.target_id, This.oLayout), oAdd = jQuery("#file_add_" + This.target_id);
			oAdd.val("");
			if(oFileSelect.length > 0) {
				var sel = oFileSelect.get(0);
				var len = sel.length;
				for(var i = 0; i < len; i++) {
					var value = sel.options[i].value;
					var arr = String(value).split("_**_");
					var file = {
						url : arr[0]
						, save : arr[1]
						, name : arr[2]
						, size : arr[3]
						, ext : arr[4]
						, seq : arr[5]
						, type : arr[6]
					};
					
					if(file.size) {
						out += Number(file.size);
					}
					//alert(oAdd.length);
					if(file.type == "yes") {
						oAdd.val(oAdd.val() ? oAdd.val() + "," + file.seq : file.seq);
					}
				}
				//alert(len);
			}
			
			var byte_view = jQuery("#byte_view" + This.target_id + "> span", This.oLayout);
			byte_view.html(This.Html_Byte_Set(out, 2));
		}
		
		, Html_Byte_Set : function(value, len) {
			var num = Number(value);
			var out = "0";
			var byte = "byte"
			if(num >= 1000 && num < 1000000) {
				out = String(num / 1000);
				byte = "Kbyte";
			} else if(num >= 1000000 && num < 1000000000) {
				out = String(num / 1000000);
				byte = "Mbyte";
			} else if(num >= 1000000000){
				out = String(num / 1000000000);
				byte = "Gbyte";
			}
			return out.substring(0, out.indexOf(".") + Number(len)) + " " + byte;
			
		}
		
		, Html_Html_Type : function(oDivTool, type) {
			var This = this;
			switch(type) {
				case true:
					This.Html_Outer_Set();
					jQuery("textarea", This.oLayout).parent().parent().css("display", "block");
					jQuery("iframe", This.oLayout).parent().parent().css("display", "none");
					oDivTool.append(
						jQuery("<div/>")
						.attr("_disabled", "true")
						.css(This.defaultCss)
						.css({
							"background" : "#FFFFFF"
							, "position" : "absolute"
							, "opacity" : "0.7"
							, "display" : "block"
							, "width" : "100%"
							, "height" : (oDivTool.height() > 25) ? oDivTool.height() + "" : "28px"
							, "top" : "0px"
							, "z-index" : "200"
						})
					);
					This.Html_Popup();
				break;
				case false:
					This.Html_Outer_Set("html");
					jQuery("textarea", This.oLayout).parent().parent().css("display", "none");
					jQuery("iframe", This.oLayout).parent().parent().css("display", "block");
					jQuery("[_disabled=true]", oDivTool).remove();
				break;
			}
		}
		
		, Html_ImageLoader : function(This, img) {
			var oA, oDd, property, i, j, count, jCount, oUl, oLi;
			
			oA = img.parent();
			oDd = oA.parent();
			oDdData = oDd.data("ddData");
			property = oDdData.property;
			
			if(img.width() > 0 && img.height() > 0) {
				oA.width(img.width())
				.hover(function() {
					var activeType = This.Html_QueryCommandState(property);
					
					if(property == "ForeColor" || property == "BackColor") {
						activeType = This.Html_QueryCommandState(property + "Choice");
					}
					
					if(!activeType) {
						
						jQuery(this)
						.css({
							"background" : "url(" + This.dirImage + "btn" + property + "Over.gif) no-repeat"
						});
						if(property == "ForeColorChoice" || property == "BackColorChoice") {
							var oDd = jQuery(this).parent().prev("dd");
							var oA = jQuery("> a", oDd);
							oA.css({
								"background" : "url(" + This.dirImage + "btn" + oDd.data("ddData").property + "TargetOver.gif) no-repeat"
							});
						}
					}
				}, function() {
					var activeType = This.Html_QueryCommandState(property);
					if(property == "ForeColor" || property == "BackColor") {
						activeType = This.Html_QueryCommandState(property + "Choice");
					}
					if(!activeType) {
						jQuery(this)
						.css({
							"background" : "url(" + This.dirImage + "btn" + property + "Out.gif) no-repeat"
						});
						if(property == "ForeColorChoice" || property == "BackColorChoice") {
							var oDd = jQuery(this).parent().prev("dd");
							var oA = jQuery("> a", oDd);
							oA.css({
								"background" : "url(" + This.dirImage + "btn" + oDd.data("ddData").property + "Out.gif) no-repeat"
							});
						}
					}
				})
				.height(img.height())
				.css({
					"background" : "url(" + img.attr("src") + ") no-repeat"
				});
				
				img.remove();
			} else {
				if(img.parent().attr("test")) {
					img.parent().attr("test", (eval(img.parent().attr("test")) + 1));
				} else {
					img.parent().attr("test", 1);
				}
				img.remove();
				
				jQuery("<img/>").attr({
					"src" : oDdData.src
					, "alt" : oDdData.alt
				})
				.css(This.defaultCss)
				.css({
					"display" : "block"
					,"color" : "#333333"
				})
				.appendTo(oA)
				.load(function() {
					var img = jQuery(this);
					This.Html_ImageLoader(This, img);
				});
			}
		}
		
		
		// Html 편집기 기본 프래임 세팅
		, Html_Init : function() {
			
			this.Html_Layout();
		}
		
	}
	
//})();