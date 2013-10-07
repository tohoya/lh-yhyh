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
						, "display" : $.browser.mozilla ? "block" : ""
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

function Content_frame_resize(h, target) {
	jQuery(target).height(h + 30);
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
		if(jQuery.browser.msie == "true") {
			This.ajaxForm(function(responseText, statusText, xhr, $form) {
				alert(statusText);
				func(responseText);
			});
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
		}
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
		files : new Array(),
		
		// 초기화 및 Html 편집기 삽입
		Init : function(_target, _outer, _files, _width, _height) {
			// 변수 초기화
			this.dirImage = this.dirDefault + "image/";
			this.oLayout = null;
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
			
			this.colorPanel = [["FF0000", "ff5e00", "ffbb00", "FFE400", "abf200", "1fda11", "00d8ff", "0055ff", "0900ff", "6600ff", "ff00dd", "ff007f", "000000", "FFFFFF"]
			, ["ffd8d8", "fae0d4", "faecc5", "faf4c0", "e4f7ba", "cefbc9", "d4f4fa", "d9e5ff", "dad9ff", "e8d9ff", "ffd9fa", "ffd9ec", "f6f6f6", "eaeaea"]
			, ["ffa7a7", "ffc19e", "ffe08c", "faed7d", "cef279", "b7f0b1", "b2ebf4", "b2ccff", "b5b2ff", "d1b2ff", "ffb2f5", "ffb2d9", "d5d5d5", "bdbdbd"]
			, ["f15f5f", "f29661", "f2cb61", "e5d85c", "bce55c", "86e57f", "5cd1e5", "6699ff", "6b66ff", "a366ff", "f261df", "f261aa", "a6a6a6", "8c8c8c"]
			, ["cc3d3d", "cc723d", "cca63d", "c4b73b", "9fc93c", "47c83e", "3db7cc", "4174d9", "4641d9", "7e41d9", "d941c5", "d9418d", "747474", "5d5d5d"]
			, ["980000", "993800", "997000", "998a00", "6b9900", "2f9d27", "008299", "003399", "050099", "3d0099", "990085", "99004c", "4c4c4c", "353535"]
			, ["670000", "662500", "664b00", "665c00", "476600", "22741c", "005766", "002266", "030066", "290066", "660058", "660033", "212121", "000000"]];

			
			this.toolbar = {
				def : [
					[
						{ type : "select", property : "FontName", src : "", select :
							[
								{ name : "돋움", value : "돋움" }
								,{ name : "굴림", value : "굴림" }
								,{ name : "궁서", value : "궁서" }
								,{ name : "Arial", value : "Arial" }
								,{ name : "Arial Black", value : "Arial Black" }
								,{ name : "Century Gothic", value : "Century Gothic" }
								,{ name : "Courier New", value : "Courier New" }
								,{ name : "Helvetica", value : "Helvetica" }
								,{ name : "Symbol", value : "Symbol" }
								,{ name : "Tahoma", value : "Tahoma" }
								,{ name : "Terminal", value : "Terminal" }
								,{ name : "Times New Roman", value : "Times New Roman" }
								,{ name : "Verdana", value : "Verdana" }
							]
						}
					]
					, [
						{ type : "select", property : "FontSize", src : "", select :
							[
								{ name : "가나다라마바사(8pt)", value : "8pt" }
								,{ name : "가나다라마바사(9pt)", value : "9pt" }
								,{ name : "가나다라마바사(10pt)", value : "10pt" }
								,{ name : "가나다라마바사(12pt)", value : "12pt" }
								,{ name : "가나다라마바사(14pt)", value : "14pt" }
								,{ name : "가나다라마바사(18pt)", value : "18pt" }
								,{ name : "가나다라마(24pt)", value : "24pt" }
								,{ name : "가나다(36pt)", value : "36pt" }
							]
						}
					]
					,[
						{ type : "button", alt : "굵게 (Ctrl+B)", property : "Bold", src : "" }
						,{ type : "button", alt : "밑줄 (Ctrl+U)", property : "Underline", src : "" }
						,{ type : "button", alt : "기울임 (Ctrl+I)", property : "Italic", src : "" }
						,{ type : "button", alt : "취소선 (Ctrl+D)", property : "StrikeThrough", src : "" }
						,{ type : "button", alt : "글자색", property : "ForeColor", src : "" }
						,{ type : "button", alt : "글자색 선택", property : "ForeColorChoice", src : "" }
						,{ type : "button", alt : "글 배경색", property : "BackColor", src : "" }
						,{ type : "button", alt : "글 배경색 선택", property : "BackColorChoice", src : "" }
					]
					,[
						{ type : "button", alt : "왼쪽정렬", property : "JustifyLeft", src : "" }
						,{ type : "button", alt : "가운데정렬", property : "JustifyCenter", src : "" }
						,{ type : "button", alt : "오른쪽정렬", property : "JustifyRight", src : "" }
						,{ type : "button", alt : "양쪽정렬", property : "JustifyFull", src : "" }
					]
					,[
						{ type : "button", alt : "들여쓰기", property : "Indent", src : "" }
						,{ type : "button", alt : "내어쓰기", property : "OutDent", src : "" }
					]
					, [
						/*{ type : "button", alt : "줄간격", property : "LineHeight", src : "" }
						,*/{ type : "button", alt : "리스트", property : "InsertUnOrderedList", src : "" }
					]
					/*,[
						{ type : "button", alt : "실행취소", property : "Redo", src : "" }
						,{ type : "button", alt : "다시실행", property : "Undo", src : "" }
					]*/
					/*,[
						{ type : "button", alt : "URL링크", property : "CreateLink", src : "" }
						,{ type : "button", alt : "테이블", property : "CreateTable", src : "" }
						//,{ type : "button", alt : "레이아웃", property : "CreateLayout", src : "" }
						,{ type : "button", alt : "이미지", property : "CreateImage", src : "" }
					]*/
				]
				,advanced : [
					[
						{ type : "button", alt : "셀 병합", property : "CellCell", src : "" }
						,{ type : "button", alt : "셀 삽입", property : "CellInsert", src : "" }
						,{ type : "button", alt : "셀 삭제", property : "CellDel", src : "" }
					]
					,[
						{ type : "button", alt : "셀선 색상", property : "CellLineColor", src : "" }
						,{ type : "button", alt : "셀선 두께", property : "CellLineHeight", src : "" }
						,{ type : "button", alt : "셀선 종류", property : "CellLineStyle", src : "" }
						,{ type : "button", alt : "테두리", property : "CellLineApply", src : "" }
					]
					,[
						{ type : "button", alt : "테이블 배경색", property : "CellColor", src : "" }
						,{ type : "button", alt : "셀 배경색", property : "TableColor", src : "" }
					]
					,[
						{ type : "button", alt : "복사하기", property : "Copy", src : "" }
						,{ type : "button", alt : "잘라내기", property : "Cut", src : "" }
						,{ type : "button", alt : "붙여넣기", property : "Paste", src : "" }
					]
				]
			}
			
			/*
			* execCommand *
			
			fontName : 글꼴
			fontSize : 글자크기
			foreColor : 글자색
			backColor : 글자배경색
			insertOrderedList : 숫자목록
			unlink : 링크제거
			delete : 삭제
			insertHorizontalrule : <hr> 넣기
			insertImage : 이미지넣기
			subScript : 밑첨자
			superScript : 윗첨자
			
			*/
			
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
			
			var oDivTool = jQuery("<div/>")
			.css(This.defaultCss)
			.css({
				"display" : "block"
				, "clear" : "both"
				, "width" : "100%"
				, "height" : "35px"
				, "padding-bottom" : "0px"
				, "position" : "relative"
				, "background" : "url(" + This.dirImage + "toolLayoutBgS.gif) repeat-x"
				, "z-index" : "200"
			})
			.append(
				jQuery("<div/>")
				.css({
					"display" : "block"
					, "width" : "4px"
					, "height" : "35px"
					, "position" : "absolute"
					, "background" : "url(" + This.dirImage + "toolLayoutLeftS.gif) no-repeat"
				})
			)
			.append(
				jQuery("<div/>")
				.css({
					"display" : "block"
					, "height" : "25px"
					, "padding" : "7px 5px 0px"
					, "position" : "absolute"
					, "left" : "4px"
				})
			)
			.append(
				jQuery("<div/>")
				.css({
					"display" : "block"
					, "width" : "4px"
					, "height" : "62px"
					, "position" : "absolute"
					, "right" : "0px"
					, "background" : "url(" + This.dirImage + "toolLayoutRightS.gif) no-repeat"
				})
			);

			
			
			This.Toolbar_Init(This.toolbar.def, jQuery("> div:eq(1)", oDivTool), 100);
			//This.Toolbar_Init(This.toolbar.advanced, jQuery("> div:eq(1)", oDivTool), 99);
			
			oDivTool.append(
				jQuery("<div/>")
				.css(This.defaultCss)
				.css({
					"display" : "block"
					, "clear" : "both"
					, "position" : "absolute"
					, "top" : "4px"
					, "right" : "20px"
					, "height" : "18px"
					, "line-height" : "1em"
					, "z-index" : "201"
				})
				.css("white-space", "nowrap")
				.append(
					jQuery("<input/>")
					.css(This.defaultCss)
					.css({
						"vertical-align" : "middle"
						, "line-height" : "1em"
						, "height" : "18px"
					})
					.attr({
						"type" : "checkbox"
						, "id" : "htmlSource_" + This.target_id
					})
					.change(function() {
						switch(this.checked) {
							case true:
								oFileLayout.slideUp(400, "easeOutCubic");
							break;
							case false:
								oFileLayout.slideDown(400, "easeOutCubic");
							break;
						}
						This.Html_Html_Type(oDivTool, this.checked);
					})
				)
				.append(
					jQuery("<label/>")
					.attr("for", "htmlSource_" + This.target_id)
					.css(This.defaultCss)
					.css({
						"white-space" : "nowrap"
						, "padding" : "9px 0 0 5px"
						, "text-decoration" : "none"
						, "color" : "#333333"
						, "vertical-align" : "middle"
						, "line-height" : "1em"
						, "height" : "18px"
					}).text("HTML 소스로 작업하기")
				)
			);

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
			/*
			.append(
				jQuery("<div/>")
				.css(This.defaultCss)
				.append(
					jQuery("<div/>")
					.css(This.defaultCss)
					.append(
						jQuery("<div/>")
						.css(This.defaultCss)
						.css({
							"position" : "relative"
							, "height" : "28px"
						})
					)
				)
			);*/
			
			oDivTool.appendTo(This.oLayout);
			
			
			This.oLayout.append(
				jQuery("<div/>")
				.css(This.defaultCss)
				.css({
					"display" : "block"
					, "clear" : "both"
					,"border" : "1px solid #C7C7C7"
					, "background-color" : "#E3E3E3"
					, "position" : "relative"
					, "text-align" : "center"
					, "z-index" : "0"
				})
				.append(
					jQuery("<div/>")
					.css(This.defaultCss)
					.css({
						"display" : "block"
						, "width" : (This.frameOuterWidth > 0) ? (This.frameOuterWidth) + "px" : "650px"
						, "min-width" : (This.frameOuterWidth > 0) ? (This.frameOuterWidth) + "px" : "650px"
						, "height" : (This.frameOuterHeight > 0) ? (This.frameOuterHeight) + "px" : "350px"
						, "margin" : "0px auto"
						, "position" : "relative"
						, "background" : "#FFFFFF"
						, "border-left" : "1px solid #BBBBBB"
						, "border-right" : "1px solid #BBBBBB"
						, "text-align" : "left"
					})
				)
				.append(
					jQuery("<div/>")
					.css(This.defaultCss)
					.css({
						"width" : "100%"
						, "min-width" : "630px"
						, "position" : "absolute"
						, "top" : "0px"
						, "background" : "transparent"
						, "left" : "0px"
					})
					.append(
						jQuery("<iframe/>")
						.attr({
							"frameborder" : "0"
							, "allowTransparency" : "allowtransparency"
							, "wmode" : "transparent"
						})
						.css(This.defaultCss)
						.css({
							"width" : "100%"
							, "min-width" : "628px"
							, "height" : (This.frameOuterHeight > 0) ? (This.frameOuterHeight) + "px" : "350px"
							, "margin" : "0px auto"
							, "background" : "transparent"
						})
					)
				)
			);
			
			var fontSize = This.toolbar.def[1][0].select[This.fontSize].value;
			var fontName = This.toolbar.def[0][0].select[This.fontName].value;
			var iePstr = "<p style=\"font-family:" + fontName + "; font-size:" + fontSize + ";\"></p>";
			var etcPstr = "<p style=\"font-family:" + fontName + "; font-size:" + fontSize + ";\"><br></p>";
			var tmpHtml = "";
			
			This.firstHtml = (jQuery.browser.msie) ? iePstr : etcPstr;
			if(This.outer != null && This.outer.length > 0) {
				tmpHtml = (This.outer.get(0).tagName.toLowerCase() == "textarea") ? This.outer.get(0).value : This.outer.html();
			}
			
			if(tmpHtml) This.firstHtml = tmpHtml;
			
			jQuery("<textarea/>")
			.css(This.defaultCss)
			.css({
				"width" : "100%"
				, "border" : "0px none #FFFFFF"
				, "height" : (This.frameOuterHeight - 22 > 0) ? (This.frameOuterHeight - 22) + "px" : "328px"
				, "margin" : "0px auto"
				, "padding" : "10px"
				, "line-height" : "1.4"
				, "overflow" : "auto"
			})
			.appendTo(
				jQuery("<div/>")
				.css(This.defaultCss)
				.css({
					"border" : "1px solid #C7C7C7"
					, "background" : "#FFFFFF"
					, "width" : ""
					, "text-align" : "center"
				})
				.appendTo(
					jQuery("<div/>")
					.appendTo(This.oLayout)
					.css(This.defaultCss)
					.css({
						"display" : "none"
						, "width" : "100%"
						, "clear" : "both"
					})
				)
			)
			.get(0).value = This.firstHtml;
			
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
				This.Html_Focus();
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
				
				This.Html_Insert("FileInsert", file);
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
				This.Html_Focus();
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
				
				This.Html_Insert("FileLink", file);
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
					, "name" : "_edit_type"
				})
				.val("_upload_file_" + This.target_id);
				
				f.append(oInput);
				f.get(0)._module.value = "file_upload_proc"
				f.YhyhFormSubmit($_yhyh_web + "/?_upload_type=_upload_file_" + This.target_id, function(data) {
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
		
		, Toolbar_Init : function(_array, oDivTool, index) {
			var oDiv, oDl, oDd, oA, oImg, count, i, jCount, j, This, setArr;
			This = this;
			count = _array.length;
			
			if(count > 0) {
				oDiv = jQuery("<div/>")
				.css(This.defaultCss)
				.css({
					"display" : "block"
					, "position" : "relative"
					, "clear" : "both"
					, "height" : "28px"
					, "padding" : "0px 0 0 0"
					, "z-index" : index
				});
				for(i = 0; i < count; i++) {
					oDl = jQuery("<dl/>")
					.css(This.defaultCss)
					.css({
						"display" : "block"
						, "float" : "left"
						, "margin" : "0px 2px"
					});
					jCount = _array[i].length;
					for(j = 0; j < jCount; j++) {
						setArr = _array[i][j];
						setArr.src = (setArr.src) ? this.dirImage + "" + setArr.src : this.dirImage + "btn" + setArr.property + "Out.gif";
						
						oDd = jQuery("<dd/>")
						.css(This.defaultCss)
						.css({
							"position" : "relative"
							, "float" : "left"
						})
						.data("ddData", setArr)
						.appendTo(oDl);
						
						oA = jQuery("<a/>")
						.css(This.defaultCss)
						.attr({
							"href" : "javascript:;"
							, "title" : setArr.alt
							, "class" : "_a" + setArr.property
						})
						.css({
							"display" : "block"
							, "text-align" : "center"
							, "text-decoration" : "none"
						})
						.click({ obj : setArr }, function(e) {
							var obj = e.data.obj;
							This.Html_Action(obj.property);
						})
						.appendTo(oDd);
						
						var oImg = jQuery("<img/>").attr({
							"src" : setArr.src
							, "alt" : setArr.alt
						})
						.css(This.defaultCss)
						.css({
							"display" : "block"
							,"color" : "#333333"
						})
						.appendTo(oA);
						This.Html_ImageLoader(This, oImg);
						
						switch(setArr.type) {
							case "select":
							break;
							case "button":
							default:
						}
					}
					oDiv.append(oDl);
				}
				oDivTool.append(oDiv);
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
				
				switch(property) {
					case "ForeColor":
						oA
						.css({
							"padding" : "4px 0 0 0"
						})
						.append(
							jQuery("<span/>")
							.css(This.defaultCss)
							.css({
								"color" : "#333333"
							})
							.html("가")
						);
						
					break;
					case "ForeColorChoice":
						oDd.append(This.Html_Color_Panel(oDd));
					break;
					case "FontSize":
					case "FontName":
						oA
						.css({
							"padding" : "4px 0px 0px 0px"
							, "text-align" : "left"
							, "display" : "block"
						})
						.append(
							jQuery("<span/>")
							.css(This.defaultCss)
							.css({
								"display" : "inline-block"
								, "padding" : "2px 0 0 4px"
								, "width" : (oA.width() - 20) + "px"
								, "height" : (oA.height() - 2) + "px"
								, "overflow" : "hidden"
								, "white-space" : "nowrap"
								, "color" : "#333333"
								, "line-height" : "1em"
							})
							.html((oDd.data("ddData").property == "FontName") ? oDd.data("ddData").select[0].name : oDd.data("ddData").select[This.fontSize].value)
						);
						
						oUl =	jQuery("<ul/>")
						.attr({
							"popup" : "_" + oDd.data("ddData").property
							, "class" : "_popup"
						})
						.css(This.defaultCss)
						.mouseleave(function() {
							This.Html_Popup();
						})
						.css({
							"display" : "none"
							, "position" : "absolute"
							, "left" : "0px"
							, "top" : oA.height() + "px"
							, "width" : "auto"
							, "height" : "auto"
							, "border" : "1px solid #DDDDDD"
							, "background" : "#FFFFFF"
							, "z-index" : "100"
						});
						
						oDd.append(oUl);
						count = oDd.data("ddData").select.length;
						for(i = 0; i < count; i++) {
							jQuery("<li/>")
							.css(This.defaultCss)
							.css({
								"display" : "block"
								, "position" : "relative"
								, "width" : "auto"
								, "text-align" : "left"
								, "border-bottom" : (i < count) ? "1px solid #DDDDDD" : "0px none #DDDDDD"
							})
							.append(
								jQuery("<a/>")
								.attr({
									"href" : "javascript:;"
								})
								.css(This.defaultCss)
								.css({
									"display" : "block"
									, "position" : "relative"
									, "width" : "auto"
									, "padding" : "2px 5px"
									, "background" : "#FFFFFF"
									, "white-space" : "nowrap"
									, "cursor" : "pointer"
									, "color" : "#333333"
									, "font-family" : (oDd.data("ddData").property == "FontName") ? oDd.data("ddData").select[i].name : ""
									, "font-size" : (oDd.data("ddData").property == "FontName") ? "" : oDd.data("ddData").select[i].value + ""
									, "text-decoration" : "none"
								})
								.hover(function() {
									jQuery(this).css("background-color", "#F4F4F4");
								}, function() {
									jQuery(this).css("background-color", "#FFFFFF");
								})
								.html(oDd.data("ddData").select[i].name)
								.click({ ddData : oDd.data("ddData"), i : i }, function(e) {
									var ddData = e.data.ddData;
									var i = e.data.i;
									
									var oA = jQuery("a[class=_a" + ddData.property + "]");
									jQuery("> span", oA).html((ddData.property == "FontName") ? ddData.select[i].name : ddData.select[i].value);
									oA.data("value", ddData.select[i].value);
									
									var value = ((ddData.property == "FontName")) ? ddData.select[i].value : ddData.select[i].value;
									
									This.Html_Insert(ddData.property, value);
								})
							)
							.appendTo(oUl);
						}
					break;
					case "BackColor":
						oA
						.css("padding", "4px 0 0 0")
						.append(
							jQuery("<span/>")
							.css(This.defaultCss)
							.css({
								"color" : "#FFFFFF"
								, "background" : "#ff7428"
							})
							.html("가")
						);
					break;
					case "BackColorChoice":
						oDd.append(This.Html_Color_Panel(oDd));
					break;
					case "CreateImage":
						var oDiv = jQuery("<div/>")
						.attr({
							"popup" : "_" + property
							, "class" : "_popup"
						});
						oDd.append(oDiv);
					break;
					case "CreateTable":
						var oDiv = jQuery("<div/>")
						.attr({
							"popup" : "_" + property
							, "class" : "_popup"
						})
						.mouseleave(function() {
							This.Html_Popup();
						})
						.css(This.defaultCss)
						.css({
							"display" : "none"
							, "position" : "absolute"
							, "border" : "1px solid #DDDDDD"
							, "background" : "#f6f6f6"
							, "padding" : "10px"
							, "white-space" : "nowrap"
						});
						var oP = jQuery("<p/>")
						.html("표 삽입")
						.css(This.defaultCss)
						.css({
							"margin" : "0px"
							, "padding" : "0px 0px 5px 3px"
							, "line-height" : "1em"
						})
						.appendTo(oDiv);
						var oTable = jQuery("<table/>")
						.attr({
							"border" : "0"
							, "width" : "160px"
							, "cellPadding" : "0"
							, "cellSpacing" : "2"
						})
						.css(This.defaultCss)
						.css({
						})
						.appendTo(oDiv);
						
						var oTbody = jQuery("<tbody/>").appendTo(oTable);
						
						for(i = 0; i < 10; i++) {
							var oTr = jQuery("<tr/>").appendTo(oTbody);
							for(j = 0; j < 10; j++) {
								var oTd = jQuery("<td/>").appendTo(oTr);
								oTd.data({
									"row" : i
									, "col" : j
								})
								.css(This.defaultCss)
								.css({
									"width" : "12px"
									, "height" : "12px"
									, "font-size" : "0px"
									, "border" : "1px solid #DFDFDF"
									, "background" : "#FFFFFF"
								})
								.click(function() {
									jQuery("td", oTbody).css("background", "#FFFFFF");
									
									var iTd = jQuery(this).data("row");
									var jTd = jQuery(this).data("col");
									
									This.Html_Insert(property, { row : iTd + 1, col : jTd + 1 });
								})
								.mouseover(function() {
									jQuery("td", oTbody).css("background", "#FFFFFF");
									
									var iTd = jQuery(this).data("row");
									var jTd = jQuery(this).data("col");
									oP.html("표 삽입 : <b>" + (iTd + 1) + " X " + (jTd + 1) + "</b>");
									
									for(i = 0; i <= iTd; i++) {
										for(j = 0; j <= jTd; j++) {
											var oTd = jQuery("> tr:eq(" + i + ") > td:eq(" + j + ")", oTbody);
											oTd.css({
												"background" : "#ff7428"
											});
										}
									}
								});
							}
						}
						
						oDd.append(oDiv);
					break;
					case "CreateLink":
						var protocol = 	jQuery("<select/>")
						.css({
							"font-size" : "8pt"
						})
						.append(jQuery("<option/>").attr("value", "http://").text("http://"))
						.append(jQuery("<option/>").attr("value", "https://").text("https://"))
						.append(jQuery("<option/>").attr("value", "mms://").text("mms://"))
						.append(jQuery("<option/>").attr("value", "ftp://").text("ftp://"));
						
						var href = jQuery("<input/>")
						.attr({
							"type" : "text"
							, "value" : ""
						})
						.css(This.defaultCss)
						.css({
							"border" : "1px solid #DFDFDF"
							, "width" : "150px"
							, "font-size" : "8pt"
						});
						
						var target = jQuery("<select/>")
						.css(This.defaultCss)
						.css({
							"font-size" : "8pt"
						})
						.append(jQuery("<option/>").attr("value", "_blank").text("_blank"))
						.append(jQuery("<option/>").attr("value", "_self").text("_self"))
						.append(jQuery("<option/>").attr("value", "_top").text("_top"))


						
						oDd.append(
							jQuery("<div/>")
							.attr({
								"popup" : "_" + property
								, "class" : "_popup"
							})
							.mouseleave(function() {
								This.Html_Popup();
							})
							.css(This.defaultCss)
							.css({
								"display" : "none"
								, "position" : "absolute"
								, "border" : "1px solid #DDDDDD"
								, "background" : "#f6f6f6"
								, "padding" : "10px"
								, "white-space" : "nowrap"
							})
							.append(protocol)
							.append(href)
							.append(target)
							.append(
								jQuery("<img/>")
								.attr({
									"src" : This.dirImage + "btnInput.gif"
									, "alt" : "입력"
								})
								.click(function() {
									var pIndex = protocol.get(0).selectedIndex;
									var tIndex = target.get(0).selectedIndex;
									var hrefText = href.get(0).value;
									var protocolText = protocol.get(0).options[pIndex].value;
									var targetText = target.get(0).options[tIndex].value;
									
									This.Html_Insert(property, { protocol : protocolText, href : hrefText, target : targetText });
								})
								.css(This.defaultCss)
								.css({
									"display" : "inline-block"
									, "height" : "16px"
									, "font-size" : "9pt"
									, "border" : "1px solid #333333"
									, "vertical-align" : "middle"
									, "cursor" : "pointer"
								})
							)
						);
						
					break;
					default:
				}
				//alert(this.target + " = " + property + " : " + img.width());
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
		
		, Html_Color_Panel : function(oDd) {
			var This, oA, oDd, property, i, j, count, jCount, oUl, oLi;
			This = this;
			
			oA = jQuery("> a", oDd);
			property = oDd.data("ddData").property;
			oUl = jQuery("<dl/>")
			.attr({
				"popup" : "_" + property
				, "class" : "_popup"
			})
			.css(This.defaultCss)
			.mouseleave(function() {
				This.Html_Popup();
			})
			.css({
				"display" : "none"
				, "position" : "absolute"
				, "left" : "-50px"
				, "top" : oA.height() + "px"
				, "width" : "200px"
				, "height" : "auto"
				, "border" : "1px solid #DDDDDD"
				, "background" : "#f6f6f6"
				, "padding" : "7px 0"
				, "z-index" : "100"
			});
			
			count = This.colorPanel.length;
			for(i = 0; i < count; i++) {
				jCount = This.colorPanel[i].length;
				oLi = jQuery("<dd/>")
				.appendTo(oUl)
				.css(This.defaultCss)
				.css({
					"display" : "block"
					, "width" : "184px"
					, "margin" : "0 auto"
					, "padding" : "0"
					, "list-style" : "none"
					, "overflow" : "hidden"
					, "white-space" : "nowrap"
				});
				for(j = 0; j < jCount; j++) {
					var cc = This.colorPanel[i][j];
					jQuery("<a/>")
					.attr({
						"href" : "javascript:;"
					})
					.css(This.defaultCss)
					.css({
						"display" : "block"
						, "float" : "left"
						, "width" : "12px"
						, "height" : "12px"
						, "background" : "#" + cc
						, "font-size" : "0px"
						, "margin" : "1px 0 0 1px"
					})
					.html("")
					.click(function() {
						This.Html_Insert(property, jQuery(this).css("background-color"));
					})
					.appendTo(oLi);
				}
			}
			oLi = jQuery("<dd/>")
			.appendTo(oUl)
			.css(This.defaultCss)
			.css({
				"display" : "block"
				, "position" : "relative"
				, "width" : "184px"
				, "margin" : "0 auto"
				, "padding" : "2px"
				, "list-style" : "none"
				, "overflow" : "hidden"
				, "white-space" : "nowrap"
			})
			.append(
				jQuery("<span/>")
				.css(This.defaultCss)
				.css({
					"display" : "inline-block"
					, "width" : "60px"
					, "height" : "26px"
					, "background" : "#333333"
					, "margin" : "0 2px 0 0px"
					, "vertical-align" : "middle"
				})
			)
			.append(
				jQuery("<input/>")
				.attr({
					"type" : "text"
					, "value" : "#333333"
				})
				.css(This.defaultCss)
				.css({
					"display" : "inline-block"
					, "width" : "50px"
					, "height" : "10px"
					, "font-size" : "9pt"
					, "margin-right" : "2px"
					, "padding-top" : "1px !important"
					, "border" : "1px solid #333333"
					, "vertical-align" : "middle"
				})
			)
			.append(
				jQuery("<img/>")
				.attr({
					"src" : This.dirImage + "btnInput.gif"
					, "alt" : "입력"
				})
				.click(function() {
					This.Html_Insert(property, jQuery(this).prev().get(0).value);
				})
				.css(This.defaultCss)
				.css({
					"display" : "inline-block"
					, "position" : "absolute"
					, "right" : "4px"
					, "font-size" : "9pt"
					, "vertical-align" : "middle"
					, "cursor" : "pointer"
				})
			);
			return oUl;
		}
		
		, Html_Insert : function(property, value) {
			var This = this, html, tagName = "FONT", fontStyle = "", oRange = null, editObject, oElement, oNodeS, oNodeE, oRangeTemp, 
			oTable, oTbody, oTr, oTd, oVs, 
			i, count;
			
			This.Html_Focus();
			switch(property) {
				case "FileInsert":
					oVs = jQuery("<div/>")
					switch(value.ext.toLowerCase()) {
						case "jpg":
						case "jpeg":
						case "png":
						case "bmp":
						case "gif":
							//alert(jQuery(This.editorDoc.body).innerWidth());
							oVs
							.append(
								jQuery("<img/>")
								.attr({
									"src" : value.url
									, "alt" : ""
									, "display" : "block"
									//, "width" : (jQuery("body", This.editorDoc).innerWidth() - 20) + "px"
								})
								/*.css({
									"max-width" : (jQuery("body", This.editorDoc).innerWidth() - 20) + "px"
								})*/
							);
						break;
						default:
							oVs.append(
								jQuery("<a/>")
								.attr({
									"href" : $_yhyh_web + "/?_module=download_proc&_file_name=" + value.save
									, "title" : value.name + "(" + This.Html_Byte_Set(value.size, 2) + ") 다운로드"
								})
								.html(value.name + "(" + This.Html_Byte_Set(value.size, 2) + ")")
							);
					}
				break;
				case "CreateImage":
					oVs = jQuery("<div/>")
					count = value.length;
					
					for(i = 0 ; i < count; i++) {
						if(i > 0) {
							oVs.html(oVs.html() + "<br><br>");
						}
						oVs
						.append(
							jQuery("<img/>")
							.attr({
								"src" : value[i].src
								, "alt" : ""
								, "display" : "block"
								//, "width" : "100%"
							})
							.css({
							})
						);
					}
				break;
				case "CreateTable":
					oTable = jQuery("<table/>")
					.attr({
						"width" : "100%"
					})
					.css({
						"border" : "1"
						, "border-spacing" : "0"
						, "border-collapse" : "collapse"
					})
					
					oTbody = jQuery("<tbody/>").appendTo(oTable);
					
					for(i = 0; i < value.row; i++) {
						oTr = jQuery("<tr/>").appendTo(oTbody);
						for(j = 0; j < value.col; j++) {
							oTd = jQuery("<td/>")
							.appendTo(oTr)
							.css({
								"font-size" : "9pt"
								, "padding" : "3px"
								, "border" : "1px solid #BDBDBD"
							})
							.append(jQuery("<p/>").html("&nbsp;"));
						}
					}
				break;
				case "CreateLink":
					tagName = "A";
					fontStyle = " href=\"" + value.protocol + "" + value.href + "\" title=\"" + value.target + "\"";
				break;
				case "FontName":
					tagName = "FONT";
					fontStyle = " style=\"font-family:" + value + ";\"";
				break;
				case "FontSize":
					tagName = "FONT";
					fontStyle = " style=\"font-size:" + value + ";\"";
				break;
				case "ForeColorChoice":
				case "ForeColor":
					tagName = "FONT";
					fontStyle = " style=\"color:" + value + ";\"";
				break;
				case "BackColorChoice":
				case "BackColor":
					tagName = "FONT";
					fontStyle = " style=\"background-color:" + value + ";\"";
				break;
			}
			
			if(This.editorDoc.selection) {
				oRange = This.oRange != null ? This.oRange : This.editorDoc.selection.createRange();
				editObject = (oRange.parentElement) ? oRange.parentElement() : null;
				oElement = jQuery(editObject);
				
				if(editObject != null && editObject.tagName.toUpperCase() == tagName) {
					if(oElement.text() == oRange.text && oRange.htmlText.length > 0) {
						switch(property) {
							case "CreateLink":
								oElement.attr({
									"href" : value.protocol + "" + value.href
									, "target" : value.target
									, "title" : value.href + "로(으로) 바로가기"
								});
							break;
							case "FontName":
								oElement.css("font-family", value);
							break;
							case "FontSize":
								oElement.css("font-size", value);
							break;
							case "ForeColorChoice":
							case "ForeColor":
								oElement.css("color", value);
							break;
							case "BackColorChoice":
							case "BackColor":
								oElement.css("background-color", value);
							break;
						}
						oRange.select();
						This.Html_Popup();
						This.editorDiv.focus();
						return;
					}
				}
				switch(property) {
					case "CreateLink":
						html = (oRange.htmlText) ? oRange.htmlText : value.href;
					break;
					default:
						html = (oRange.htmlText) ? oRange.htmlText : "﻿";
				}
				switch(property) {
					case "FileInsert":
						html = oVs.get(0).innerHTML + html;
						oRange.pasteHTML(html);
						oRange.select();
					break;
					case "CreateImage":
						html = oVs.get(0).innerHTML + html;
						oRange.pasteHTML(html);
						oRange.select();
					break;
					case "CreateTable":
						html = oTable.get(0).outerHTML + html;
						oRange.pasteHTML(html);
						oRange.select();
					break;
					default:
						html = "<" + tagName.toLowerCase() + "" + fontStyle + "><span id=\"fontAddStart\"></span>" + html + "<span id=\"fontAddEnd\"></span></" + tagName.toLowerCase() + ">";
							
						if(oRange.pasteHTML) oRange.pasteHTML(html);
						
						oNodeS = jQuery("#fontAddStart", This.editorDiv);
						oNodeE = jQuery("#fontAddEnd", This.editorDiv);
						
						// 시작점을 오브젝트로 이동
						if(oNodeS.get(0)) oRange.moveToElementText(oNodeS.get(0));
						
						// 선택영역 끝지점을 oNodeE로 이동
						oRangeTemp = This.editorBody.createTextRange();
						if(oNodeE.get(0)) oRangeTemp.moveToElementText(oNodeE.get(0));
						if(oRange.setEndPoint) oRange.setEndPoint("EndToEnd", oRangeTemp);
						
						oRange.select();
					
						oNodeS.remove();
						oNodeE.remove();
				}
				
				This.Html_Popup();
				
				This.editorDiv.focus();
			} else {
				// 선택영역 구하기
				oRange = This.editorDoc.getSelection().getRangeAt(0);
				oElement = jQuery(oRange.endContainer).parents(tagName.toLowerCase());
				
				// 선택영역 바꾸기
				var tmp = This.editorDoc.createElement("p");
				tmp.appendChild(oRange.cloneContents());
				if(oElement.html() != null && oElement.get(0).tagName.toString().toUpperCase() == tagName) {
					if(oElement.text() == oRange && oRange.rangeCount > 0) {
						switch(property) {
							case "CreateLink":
								oElement.attr({
									"href" : value.protocol + "" + value.href
									, "target" : value.target
									, "title" : value.href + "로(으로) 바로가기"
								});
							break;
							case "FontName":
								oElement.css("font-family", value);
							break;
							case "FontSize":
								oElement.css("font-size", value);
							break;
							case "ForeColorChoice":
							case "ForeColor":
								oElement.css("color", value);
							break;
							case "BackColorChoice":
							case "BackColor":
								oElement.css("background-color", value);
							break;
						}
						var selection = This.editorDoc.getSelection();
						selection.removeAllRanges();
						selection.addRange(oRange);
						
						This.Html_Popup();
						
						This.editorDiv.focus();
						This.oIframe.focus();
						return;
					}
				}
				switch(property) {
					case "CreateLink":
						html = (tmp.innerHTML) ? tmp.innerHTML : value.href;
					break;
					default:
						html = (tmp.innerHTML) ? tmp.innerHTML : "﻿";
				}
				switch(property) {
					case "FileInsert":
						html = oVs.get(0).innerHTML + html;
					break;
					case "CreateImage":
						html = oVs.get(0).innerHTML + html;
					break;
					case "CreateTable":
						html = oTable.get(0).outerHTML + html;
					break;
					default:
						html = "<" + tagName.toLowerCase() + "" + fontStyle + "><span id=\"fontAddStart\"></span>" + html + "<span id=\"fontAddEnd\"></span></" + tagName.toLowerCase() + ">";
				}
				
				oRange.deleteContents();
				oRange.insertNode(oRange.createContextualFragment(html));
				
				// 시작점을 오브젝트로 이동
				oNodeS = jQuery("#fontAddStart", This.editorDoc);
				oNodeE = jQuery("#fontAddEnd", This.editorDoc);
				if(oNodeS.get(0)) oRange.setStartAfter(oNodeS.get(0));
				if(oNodeE.get(0)) oRange.setEndBefore(oNodeE.get(0));
				
				var selection = This.editorDoc.getSelection();
				selection.removeAllRanges();
				selection.addRange(oRange);
				
				oNodeS.remove();
				oNodeE.remove();
				
				This.Html_Popup();
				
				This.editorDiv.focus();
				This.oIframe.focus();
			}
		}
		
		, Html_TestLoad : function(str) {
			var This = this;
				jQuery("textarea", This.oLayout).get(0).value = "{{{" + str + "}}}" + jQuery("textarea", This.oLayout).get(0).value;
		}
		
		, Html_QueryCommandValue : function(property) {
			var This = this, oRange, editObject, tagName, oElement, value;
			
			tagName = "FONT";
			switch(property) {
				case "JustifyLeft":
				case "JustifyCenter":
				case "JustifyRight":
				case "JustifyFull":
					tagName = "P";
				break;
				case "CreateImage":
					tagName = "IMG";
				break;
				case "CreateTable":
					tagName = "TABLE";
				break;
				case "CreateLink":
					tagName = "A";
				break;
				default:
					tagName = "FONT";
			}
			
			if(This.editorDoc.selection) {
				if(This.oElement == null) {
					oRange = This.editorDoc.selection.createRange();
					editObject = (oRange.parentElement) ? oRange.parentElement() : null;
					oElement = jQuery(editObject);
					
					if(editObject != null && editObject.tagName.toString().toUpperCase() != tagName) {
						oElement = oElement.parents(tagName.toLowerCase());
					}
				} else {
					oElement = This.oElement;
				}
			} else {
				if(This.oElement == null) {
					oRange = This.editorDoc.getSelection().getRangeAt(0);
					oElement = jQuery(oRange.endContainer).parents(tagName.toLowerCase());
					
					if(oElement.html() == null || oElement.get(0).tagName.toString().toUpperCase() != tagName) {
						oElement = oElement.parents(tagName.toLowerCase());
					}
				} else {
					oElement = This.oElement;
				}
			}
			if(oElement.get(0) && oElement.get(0).tagName.toString().toUpperCase() == tagName) {
				switch(property) {
					case "CreateImage":
						//alert(oElement.attr("src"));
					break;
					case "CreateLink":
						return (oElement.attr("href")) ? { href : oElement.attr("href"), target : oElement.attr("target") } : false;
					break;
					case "ForeColor":
						return This.Html_Color_Dec_Get((jQuery.browser.safari) ? String(This.editorDoc.queryCommandValue(property)) : oElement.css("color"));
					break;
					case "BackColor":
						return This.Html_Color_Dec_Get((jQuery.browser.safari) ? String(This.editorDoc.queryCommandValue(property)) : oElement.css("background-color"));
					break;
					case "FontSize":
						return This.fontPx[oElement.css("font-size")];
					break;
					case "FontName":
						return oElement.css("font-family");
					break;
					case "JustifyLeft":
					case "JustifyCenter":
					case "JustifyRight":
					case "JustifyFull":
						if(jQuery.browser.msie) {
							value = oElement.css("text-align");
							switch(property) {
								case "JustifyLeft":
									return (value == "left") ? true : false;
								break;
								case "JustifyCenter":
									return (value == "center") ? true : false;
								break;
								case "JustifyRight":
									return (value == "right") ? true : false;
								break;
								case "JustifyFull":
									return (value == "justify") ? true : false;
								break;
								default:
									return false;
							}
						} else {
							return This.editorDoc.queryCommandState(property.toLowerCase());
						}
					break;
					default:
						value = "";
				}
			} else {
				switch(property) {
					case "FontSize":
						return This.toolbar.def[1][0].select[This.fontSize].value;
					break;
					case "FontName":
						return This.toolbar.def[0][0].select[This.fontName].name;
					break;
					case "CreateLink":
						return false;
					break;
					default:
						value = "";
				}
				
			}
			return "";
		}
		
		, Html_Color_Dec_Get : function(style) {
			var index = style.toUpperCase().indexOf("RGB");
			if(index > -1) {
				return style.RgbToHex().HexToDec()
			} else {
				if(style.toString().toUpperCase() != "TRANSPARENT") {
					return style.substr(style.indexOf("#") + 1).HexToDec();
				}
			}
			return "";
		}
		
		, Html_QueryCommandState : function(property) {
			var This = this;
			
			switch(property.toUpperCase()) {
				case "CREATELINK":
					return (jQuery("[popup=_" + property + "]", This.oLayout).css("display") == "none") ? This.Html_QueryCommandValue(property) : true;
				break;
				case "CREATETABLE":
				case "CREATEIMAGE":
					return This.Html_QueryCommandValue(property);
				break;
				case "FORECOLORCHOICE":
				case "BACKCOLORCHOICE":
				case "FONTNAME":
				case "FONTSIZE":
					return (jQuery("[popup=_" + property + "]", This.oLayout).css("display") == "none") ? false : true;
				break;
				case "FONTNAME":
				case "FONTSIZE":
					return This.Html_QueryCommandValue(property);
				break;
				case "JUSTIFYLEFT":
				case "JUSTIFYCENTER":
				case "JUSTIFYRIGHT":
				case "JUSTIFYFULL":
					return This.Html_QueryCommandValue(property);
				break;
			}
			
			if(This.Html_QueryCommand(property)
			 && property != "Undo"
			 && property != "Redo"
			 ) {
				if(((jQuery.browser.mozilla || jQuery.browser.safari)
				 && property != "Indent"
				 && property != "OutDent"
				 && property != "JustifyLeft"
				 && property != "ForeColor"
				 && property != "BackColor"
				 && property != "FontName"
				 && property != "FontSize") || jQuery.browser.msie
				) {
					if(This.editorDoc.queryCommandState(property.toLowerCase())) {
						return This.editorDoc.queryCommandState(property.toLowerCase());
					}
				}
			}
			return false;
		}
		
		, Html_QueryCommand : function(property) {
			var This = this;
			if(property && property != "ForeColorChoice"
			 && property != "BackColorChoice" 
			 && property != "LineHeight"
			 && property != "CreateLayout"
			 && property != "CellCell"
			 && property != "CellInsert"
			 && property != "CellDel"
			 && property != "CellLineColor"
			 && property != "CellLineHeight"
			 && property != "CellLineStyle"
			 && property != "CellLineApply"
			 && property != "TableColor"
			 && property != "CellColor"
			 ) {
				return true;
			 }
			return false;
		}
		
		, Html_Toolbar_Update : function() {
			var activeType, oDd, oA, property, This;
			
			This = this;
			jQuery("> div:eq(0) > div:eq(1) > div", this.oLayout).each(function(i) {
				jQuery("> dl", this).each(function(j) {
					jQuery("> dd", this).each(function(k) {
						property = jQuery(this).data("ddData").property;
						
						if(This.Html_QueryCommand(property)) {
							oDd = jQuery(this);
							oA = jQuery("> a", oDd);
							switch(property) {
								case "FontName":
									jQuery("a[class=_a" + property + "] > span", This.oLayout).html(This.Html_QueryCommandValue(property));
								break;
								case "FontSize":
									jQuery("a[class=_a" + property + "] > span", This.oLayout).html(This.Html_QueryCommandValue(property));
								break;
								case "ForeColor":
									colorDec = This.Html_QueryCommandValue(property);
									
									jQuery("a[class=_a" + property + "] > span", This.oLayout)
									.css("color", "#" + String(colorDec).DecToHex());
								break;
								case "BackColor":
									colorDec = This.Html_QueryCommandValue(property);
									if(colorDec) {
										jQuery("a[class=_a" + property + "] > span", This.oLayout)
										.css("background-color", "#" + String(colorDec).DecToHex());
										if(colorDec > 0x9F9F9F) {
											jQuery("a[class=_a" + property + "] > span", This.oLayout)
											.css("color", "#000000");
										} else {
											jQuery("a[class=_a" + property + "] > span", This.oLayout)
											.css("color", "#FFFFFF");
										}
									}
									
								break;
								case "CreateLink":
									activeType = This.Html_QueryCommandState(property);
									var oPopup = jQuery("[popup*=_" + property + "]", This.oLayout);
									var oHref = jQuery("input[type=text]", oPopup);
									var oProtocol = jQuery("select:eq(0)", oPopup);
									var oTarget = jQuery("select:eq(1)", oPopup);
									var pIndex = 0;
									var tIndex = 0;
									var protocolText = "http://";
									var hrefText = "";
									//alert(activeType);
									if(activeType != false) {
										count = oTarget.get(0).length;
										for(i = 0; i < count; i++) {
											if(oTarget.get(0).options[i].value == activeType.target) {
												tIndex = i;
											}
										}
										
										var hIndexStart = (activeType.href) ? activeType.href.indexOf("://") : -1;
										if(hIndexStart > -1 && activeType.href) {
											hIndexStart += 3;
											protocolText = activeType.href.substr(0, hIndexStart);
											hrefText = activeType.href.substr(hIndexStart);
										}
										
										count = oProtocol.get(0).length;
										for(i = 0; i < count; i++) {
											if(oProtocol.get(0).options[i].value == protocolText) {
												pIndex = i;
											}
										}
									}
									
									oHref.get(0).value = hrefText;
									oProtocol.get(0).selectedIndex = pIndex;
									oTarget.get(0).selectedIndex = tIndex;
								default:
									activeType = This.Html_QueryCommandState(property);
									//alert(property);
									if(property == "JustifyLeft") {
										//alert(property);
									}
									oA
									.css({
										"background" : (activeType) ? "url(" + This.dirImage + "btn" + property + "Choice.gif) no-repeat" : "url(" + This.dirImage + "btn" + property + "Out.gif) no-repeat"
									});
							}
						}
					});
				});
			});
		}
		
		, Html_Popup : function(_action) {
			var This, oDd, ddData, property, activeType;
			This = this;
			property = _action;
			switch(_action) {
				case "CreateImage":
					var nowDate = new Date();
					
					var iDl = jQuery("<dl/>")
					.css({
						"overflow" : "hidden"
					})
					
					var FileUploadCallBack = function(responseText, statusText, xhr, $form) {
						var pd = jQuery("<div/>").html(responseText);
						var oDd = jQuery("<dd/>")
						.css({
							"display" : "block"
							, "float" : "left"
						})
						.append(
							jQuery("<img/>")
							.attr({
								"src" : jQuery("p:eq(0)", pd).html()
								, "alt" : ""
								, "addtype" : "false"
							})
							.css({
								"display" : "block"
								, "width" : "50px"
								, "height" : "50px"
								, "cursor" : "pointer"
								, "border" : "2px solid #777777"
							})
							.click(function() {
								jQuery(this)
								.css({
									"border" : (jQuery(this).attr("addtype") == "false") ? "2px solid #FF0000" : "2px solid #777777"
								})
								.attr("addtype", (jQuery(this).attr("addtype") == "false") ? "true" : "false");
							})
						)
						.append(
							jQuery("<a/>")
							.attr({
								"title" : "파일 삭제"
							})
							.css({
								"cursor" : "pointer"
							})
							.click(function() {
								oDd.remove();
								jQuery.get(This.dirDefault + "/module/fileDelete.php?del_file=" + jQuery(this).prev().attr("src"), function() { } );
							})
							.html(jQuery("p:eq(1)", pd).html())
						)
						.appendTo(iDl);
						//alert("$form : " + $form.html());
					};
					
					var oDiv = jQuery("<div/>")
					.attr({
						//"popup" : "_" + _action
						//, "class" : "_popup"
					})
					.css(This.defaultCss)
					.css({
						"display" : "block"
						, "width" : "400px"
						, "height" : "350px"
						, "margin" : (This.oLayout.height() > 0) ? ((This.oLayout.height() - 350) * 0.5) + "px auto" : "0px auto"
						, "position" : "relative"
						, "border" : "1px solid #DDDDDD"
						, "background" : "#f6f6f6"
						, "padding" : "10px"
						, "white-space" : "nowrap"
					});
					var oP = jQuery("<p/>")
					.html("이미지 삽입")
					.css(This.defaultCss)
					.css({
						"margin" : "0px"
						, "padding" : "0px 0px 5px 3px"
						, "line-height" : "1em"
					})
					.appendTo(oDiv);
					
					var fDiv = jQuery("<div/>")
					.appendTo(oDiv);
					
					iDl.appendTo(oDiv);
					
					var bDiv = jQuery("<div/>")
					.append(
						jQuery("<img/>")
						.attr({
							"src" : ""
							, "alt" : "적용하기"
						})
						.css({
							"cursor" : "pointer"
						})
						.click(function() {
							var fileArr = new Array();
							jQuery("> dd > img", iDl).each(function(i) {
								if(jQuery(this).attr("addtype") == "true") {
									fileArr.push({ src : jQuery(this).attr("src"), ext : String(jQuery(this).attr("src")).File_Ext() });
								} else {
									jQuery.get(This.dirDefault + "/module/fileDelete.php?del_file=" + jQuery(this).attr("src"), function() { } );
								}
							});
							
							This.Html_Insert(property, fileArr);
							jQuery("[big_popup=_LayoutPopup]").remove();
						})
					)
					.append(
						jQuery("<img/>")
						.attr({
							"src" : ""
							, "alt" : "닫기"
						})
						.css({
							"cursor" : "pointer"
						})
						.click(function() {
							jQuery("> dd > img", iDl).each(function(i) {
								jQuery.get(This.dirDefault + "/module/fileDelete.php?del_file=" + jQuery(this).attr("src"), function() { } );
							});
							jQuery("[big_popup=_LayoutPopup]").remove();
						})
					)
					.appendTo(oDiv);

					
					var iUl = jQuery("<dl/>")
					.appendTo(oDiv);
					
					var oForm = jQuery("<form/>")
					.attr({
						"name" : "forms"
						, "action" : This.dirDefault + "/module/fileUpload.php"
						, "method" : "POST"
						, "enctype" : "multipart/form-data"
					})
					.ajaxForm(FileUploadCallBack)
					.submit(function() {
						return false;
					})
					.appendTo(fDiv);
					
					var oFieldset = jQuery("<fieldset/>")
					.appendTo(oForm);
					
					var oFile = jQuery("<input/>")
					.attr({
						"type" : "file"
						, "name" : "upload"
					})
					.css(This.defaultCss)
					.css({
						"border" : "1px solid #DFDFDF"
					});
					
					oFieldset
					.append(
						jQuery("<input/>")
						.attr({
							"type" : "hidden"
							, "name" : "form_id"
							, "value" : nowDate.getTime()
						})
						.css(This.defaultCss)
						.css({
							"border" : "1px solid #DFDFDF"
						})
					)
					.append(oFile);
					
					var oImg = jQuery("<img/>")
					.attr({
						"src" : This.dirImage + "btnUpload.gif"
						, "alt" : "업로드"
					})
					.click(function() {
						if(!oFile.val()) {
							alert("파일을 선택하세요.");
							oFile.focus();
							return;
						}
						oForm.submit();
						//This.Html_Insert(_action, "");
					})
					.css(This.defaultCss)
					.css({
						"display" : "inline-block"
						, "height" : "16px"
						, "font-size" : "9pt"
						, "border" : "1px solid #333333"
						, "vertical-align" : "middle"
						, "cursor" : "pointer"
					})
					.appendTo(oFieldset);
					
					
					var pDiv = jQuery("<div/>")
					.attr({
						"big_popup" : "_LayoutPopup"
						, "class" : "_big"
					})
					.css({
						"display" : "block"
						, "width" : This.oLayout.width() + "px"
						, "height" : This.oLayout.height() + "px"
						, "background" : "url(" + This.dirImage + "popup_bg.png)"
						, "position" : "absolute"
						, "top" : "0px"
						, "left" : "0px"
						, "z-index" : "300"
					}).appendTo(This.oLayout);
					
					var vDiv = jQuery("<div/>")
					.attr({
						"big_popup" : "_LayoutPopup"
						, "class" : "_big"
					})
					.css({
						"display" : "block"
						, "width" : This.oLayout.width() + "px"
						, "height" : This.oLayout.height() + "px"
						, "position" : "absolute"
						, "top" : "0px"
						, "left" : "0px"
						, "z-index" : "300"
					}).appendTo(This.oLayout);
					
					oDiv.appendTo(vDiv);
					
				break;
			}
			
			jQuery("[class*=_popup]", This.oLayout).each(function(i) {
				//alert(jQuery(this).attr("popup"));
				oDd = jQuery(this).parent();
				if(oDd.length > 0) {
					ddData = oDd.data("ddData");
					property = ddData.property;
					
					if("_" + _action == jQuery(this).attr("popup") && jQuery(this).css("display") == "none") {
						jQuery(this).css({
							"display" : "block"
							, "z-index" : "200"
						})
						.find("*")
						.css({
							"position" : "relative"
							, "z-index" : "201"
						});
						jQuery("> a", oDd)
						.css({
							"background" : "url(" + This.dirImage + "btn" + property + "Choice.gif) no-repeat"
						});
						switch(property) {
							case "ForeColorChoice":
							case "BackColorChoice":
								oDd = oDd.prev();
								property = oDd.data("ddData").property;
								jQuery("> a", oDd)
								.css({
									"background" : "url(" + This.dirImage + "btn" + property + "Choice.gif) no-repeat"
								});
							break;
						}
					} else {
						jQuery(this).css({
							"display" : "none"
							, "z-index" : ""
						})
						.find("*")
						.css({
							"position" : ""
							, "z-index" : ""
						});
						switch(property) {
							case "CreateLink":
								activeType = This.Html_QueryCommandState(property);
								jQuery("> a", oDd)
								.css({
									"background" : (activeType) ? "url(" + This.dirImage + "btn" + property + "Choice.gif) no-repeat" : "url(" + This.dirImage + "btn" + property + "Out.gif) no-repeat"
								});
							break;
							default:
								jQuery("> a", oDd)
								.css({
									"background" : "url(" + This.dirImage + "btn" + property + "Out.gif) no-repeat"
								});
						}
						switch(property) {
							case "ForeColorChoice":
							case "BackColorChoice":
								oDd = oDd.prev();
								property = oDd.data("ddData").property;
								jQuery("> a", oDd)
								.css({
									"background" : "url(" + This.dirImage + "btn" + property + "Out.gif) no-repeat"
								});
							break;
						}
					}
				}
			});
		}
		
		, Html_Action : function(_action) {
			var This;
			This = this;
			This.Html_Focus();
			switch(_action.toUpperCase()) {
				case "CREATEIMAGE":
				case "CREATETABLE":
				case "CREATELINK":
				case "FONTNAME":
				case "FONTSIZE":
				case "FORECOLORCHOICE":
				case "BACKCOLORCHOICE":
					This.Html_Popup(_action);
				break;
				case "FORECOLOR":
					This.Html_Popup();
					var colors = jQuery("a[class=_a" + _action + "] > span", This.oLayout).css("color");
					This.Html_Insert(_action, colors);
				break;
				case "BACKCOLOR":
					This.Html_Popup();
					var colors = jQuery("a[class=_a" + _action + "] > span", This.oLayout).css("background-color");
					This.Html_Insert(_action, colors);
				break;
				case "JUSTIFYLEFT":
					This.Html_Popup();
					This.Html_Justify(_action, "left");
				break;
				case "JUSTIFYCENTER":
					This.Html_Popup();
					This.Html_Justify(_action, "center");
				break;
				case "JUSTIFYRIGHT":
					This.Html_Popup();
					This.Html_Justify(_action, "right");
				break;
				case "JUSTIFYFULL":
					This.Html_Popup();
					This.Html_Justify(_action, "justify");
				break;
				case "INDENT":
				case "OUTDENT":
				default:
					This.Html_Popup();
					This.editorDoc.execCommand(_action.toLowerCase(), null, null);
			}
			This.Html_Toolbar_Update();
		}
		, Html_Justify : function(_property, _justify) {
			var This = this;
			if(This.editorDoc.selection) {
				var oRange = This.editorDoc.selection.createRange();
				var editObject = (oRange.parentElement) ? oRange.parentElement() : null;
				var oFont = jQuery(editObject);
				
				if(editObject != null && editObject.tagName.toString().toUpperCase() == "P") {
					oFont.css("text-align", _justify);
				} else {
					oFont = oFont.parents("p");
					if(oFont.get(0) && oFont.get(0).tagName.toString().toUpperCase() == "P") {
						oFont.css("text-align", _justify);
					}
				}
			} else {
				This.editorDoc.execCommand(_property.toLowerCase(), null, null);
			}
		}
		
		, Html_Focus : function() {
			var This;
			This = this;
			if(This.editorDoc.selection) {
				var EditorTotalRange = This.editorBody.createTextRange();
				var oRange = This.editorDoc.selection.createRange();
				//alert(This.oRange);
				if(!oRange.htmlText || oRange.htmlText.length == 0) {
					if(oRange.htmlText.length == 0) {
						EditorTotalRange.collapse(); // 맨 앞으로 보냄
					} else {
						oRange.select();
					}
				}
				//This.oRange.select();
				this.editorDiv.focus();
			} else {
				var oRange = This.editorDoc.getSelection();
				This.editorDiv.focus();
				This.oIframe.focus();
			}
		}
		
		// Html 편집기 기본 프래임 세팅
		, Html_Init : function() {
			
			this.Html_Layout();
			var This = this;
			var emptyHtml = "";
			This.oIframe = jQuery("iframe", This.oLayout).get(0);
			This.editorDoc = This.oIframe.contentWindow.document;
			This.editorDoc.designMode = 'On';
			This.editorDoc.open();
			This.editorDoc.write("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html>\n<head>\n<title></title>\n</head>\n<body>\n</body>\n</html>");
			
			var oLink = This.$C("link");
			oLink.setAttribute("href", This.dirDefault + "/css/cssReset.css");
			oLink.setAttribute("rel", "stylesheet");
			oLink.setAttribute("type", "text/css");
			jQuery("head", This.editorDoc).get(0).appendChild(oLink);
			
			oLink = This.$C("link");
			oLink.setAttribute("href", This.dirDefault + "/css/htmlView.css");
			oLink.setAttribute("rel", "stylesheet");
			oLink.setAttribute("type", "text/css");
			jQuery("head", This.editorDoc).get(0).appendChild(oLink);
			jQuery("html", This.editorDoc)
			.css({
				"text-align" : "center"
			});
			This.editorBody = This.editorDoc.body;
			jQuery(This.editorBody)
			.attr({
				"contenteditable" : "true"
			})
			.css({
				"width" : (This.frameOuterWidth > 0) ? (This.frameOuterWidth - 22) + "px" : "628px"
				,"max-width" : (This.frameOuterWidth > 0) ? (This.frameOuterWidth - 22) + "px" : "628px"
				, "max-height" : (This.frameOuterHeight > 0) ? (This.frameOuterHeight - 72) + "px" : "350px"
				, "padding" : "10px 10px"
				, "margin" : "0px auto"
				, "text-align" : "left"
				, "background" : "transparent"
			});
			This.editorDiv = This.editorBody
			
			This.editorDiv.innerHTML = This.firstHtml;
			This.editorDoc.close();
			
			var resizeBody = function() {
				var w = ((jQuery(This.oIframe).innerWidth() - This.frameOuterWidth) * 0.5);
				jQuery(This.editorBody).css({
					"margin-left" : Math.ceil(w) + "px"
					, "margin-right" : Math.floor(w) + "px"
				});
				if(jQuery(This.oIframe).innerWidth() > 0) {
					jQuery("textarea", This.oLayout).css({
						"width" : (jQuery(This.oIframe).innerWidth() - 22) + "px"
					});
				}
			};
			
			jQuery(window).resize(function() {
				resizeBody();
				
				jQuery("[big_popup=_LayoutPopup]", This.oLayout)
				.attr("resize", This.oLayout.find("> div:eq(0)").width())
				.css({
					"width" : This.oLayout.find("> div:eq(0)").width() + "px"
				});
			});
			
			resizeBody();
	
			jQuery(This.editorDiv)
			.click(function(e) {
				var oElement = jQuery(e.target);
				if(oElement.length > 0) {
					var tagName = oElement.get(0).tagName;
					switch(tagName.toLowerCase()) {
						case "img":
						case "table":
							This.oElement = oElement;
							This.oRange = null;
						break;
						default:
					}
				} else {
					This.oElement = null;
				}
				This.Html_Outer_Set();
				This.Html_Toolbar_Update();
				This.Html_Popup();
			})
			.mouseup(function(e) {
				var oRange;
				var oElement = jQuery(e.target);
				if(oElement.length > 0) {
					var tagName = oElement.get(0).tagName;
					switch(tagName.toLowerCase()) {
						case "img":
						case "table":
							This.oElement = oElement;
							This.oRange = null;
						break;
						default:
					}
				} else {
					This.oElement = null;
					oRange = This.editorDoc.selection.createRange();
					This.oRange = oRange.duplicate();
				}
				This.Html_Outer_Set();
				This.Html_Toolbar_Update();
			})
			.keypress(function() {
				if(jQuery.browser.msie) {
					var oRange = This.editorDoc.selection.createRange();
					This.oRange = oRange.duplicate();
				}
				This.Html_Outer_Set();
			})
			.focus(function(e) {
				var oElement = jQuery(e.target);
				if(oElement.length > 0) {
					var tagName = oElement.get(0).tagName;
					switch(tagName.toLowerCase()) {
						case "img":
						case "table":
							This.oElement = oElement;
							This.oRange = null;
						break;
						default:
					}
				} else {
					This.oElement = null;
				}
				This.Html_Outer_Set();
				This.Html_Toolbar_Update();
				return;
			})
			.blur(function() {
				//var oRange = This.editorDoc.selection.createRange();
				//This.oRange = oRange.duplicate();
				
				This.Html_Outer_Set();
				//This.Html_Focus();
			})
			.change(function() {
				This.Html_Outer_Set();
				This.Html_Toolbar_Update();
			})
			.select(function() {
				var oRange = This.editorDoc.selection.createRange();
				This.oRange = oRange.duplicate();
				This.Html_Outer_Set();
				This.Html_Toolbar_Update();
			});
			
			jQuery("textarea", This.oLayout).keyup(function() {
				This.Html_Outer_Set("html");
			});
		}
		
		, Html_Outer_Set : function(type) {
			var This;
			This = this;
			
			switch(type) {
				case "html":
					This.editorDiv.innerHTML = jQuery("textarea", This.oLayout).get(0).value;
				break;
				default:
					if(This.editorDiv.innerHTML == "" || This.editorDiv.innerHTML == "<br>") {
						This.editorDiv.innerHTML = "";
						var oP = jQuery("<p/>").html("&nbsp;");
						jQuery(This.editorDiv).append(oP); //.innerHTML = "<p>&nbsp;</p>"; //This.firstHtml;
						
						
						var oRange;
						if(This.editorDoc.selection) {
							oRange = This.editorDoc.selection.createRange();
							oRange.moveToElementText(oP.get(0));
							oRange.select();
						} else {
							oRange = This.editorDoc.getSelection().getRangeAt(0);
							oRange.setStartAfter(oP.get(0));
							oRange.setEndBefore(oP.get(0));
							var selection = This.editorDoc.getSelection();
							selection.removeAllRanges();
							selection.addRange(oRange);
						}
						oP.html("");
						
					}
					jQuery("textarea", This.oLayout).get(0).value = This.editorDiv.innerHTML;
			}
			
			if(This.outer != null && This.outer.length > 0) {
				switch(This.outer.get(0).tagName.toLowerCase()) {
					case "div":
					case "p":
						This.outer.get(0).innerHTML = This.editorDiv.innerHTML;
					break;
					case "span":
						This.outer.get(0).innerText = This.editorDiv.innerHTML;
					break;
					case "textarea":
					case "input":
					default:
						This.outer.get(0).value = This.editorDiv.innerHTML;
				}
			}
		}
		
		,$E : function(_target, _type, _func) {
			if(!_target) return;
			if(_target.attachEvent) {
				_target.attachEvent("on" + _type, _func);
			} else {
				_target.addEventListener(_type, _func, false);
			}
		}
		
		,$ : function(_selector) {
			if(!_selector) return null;
			return document.getElementById(_selector);
		}
		
		, $C : function(_tag) {
			if(!_tag) return null;
			return document.createElement(_tag);
		}
	}
	
//})();