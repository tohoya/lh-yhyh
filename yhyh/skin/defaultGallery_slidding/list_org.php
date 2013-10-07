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
<link href="<?=_lh_yhyh_web?>/skin/defaultGallery_slidding/css/default.css" rel="stylesheet" type="text/css">
<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/common.js"></script>
<script>

var _width = 110;
var _height = 120;
var bPhoto_width = 700;
var bPhoto_height = 450;
var choice_no = 0;
var slide_show_count = 6;

var page = Number("<?=$_REQUEST[_page] ? $_REQUEST[_page] : 1?>");
var total_count = Number("<?=$_total_rows?>");
var start_old_idx = 0;

var Choice_Photo_View = function(no, scroll_type) {
	//alert(no);
	if(choice_no == no) return;
	var type = (choice_no > no) ? 1 : -1;
	if(Math.ceil(no / slide_show_count) != Math.ceil(choice_no / slide_show_count)) {
		switch(type) {
			case 1:
				Page_Change_Action("prev", "no_choice");
			break;
			case -1:
				Page_Change_Action("next", "no_choice");
			break;
		}
	}
	choice_no = no;
	
	var oThum = $("> .thum_container > div:eq(" + (no - 1) + ")", ".yhyh_thum_slider");
	
	var oSelecter = $(".yhyh_thum_selecter");
	
	var big_photo = $(".yhyh_detail_photo");
	
	$("> img", big_photo).each(function() {
		$(this).stop().animate({
			opacity : 0
			, left : 100 * type
		}, 500, "easeOutCubic", function() {
			$(this).remove();
		});
	});
	
	var vImg = $("<img/>");
	
	vImg
	.attr("src", oThum.data("url"))
	.css({
		"display" : "block"
		, "position" : "absolute"
	})
	.fadeTo(0, 0)
	.appendTo(big_photo);
	
	if($.browser.msie) {
		var size = vImg.height() > bPhoto_height ? bPhoto_height : vImg.height()
		vImg.attr({
			"width" : (vImg.width() / (vImg.height() / size) ) + "px"
			, "height" : size + "px"
		})
		.css({
			"max-width" : bPhoto_width + "px"
		});
	} else {
		vImg.css({
			"max-width" : bPhoto_width + "px"
			, "max-height" : bPhoto_height + "px"
		});
	}
	
	var Image_Loader = function() {
		big_photo.css({
			"display" : "block"
			//, "border" : "1px solid #FF0000"
			, "height" : bPhoto_height + "px"
		})
		.stop()
		.animate({
			width : vImg.width()
		}, 500, "easeOutCubic");
		
		vImg
		.css({
			"top" : ((bPhoto_height - vImg.height()) * 0.5) + "px"
		, "left" : ((vImg.width() * 0.25) * type * -1) + "px"
		})
		.animate({
			opacity : 1
			, left : 0
		}, 500, "easeOutCubic", function() {
		});
		
		if(oSelecter.css("visibility") == "hidden") {
			oSelecter.stop().fadeTo(0, 0).css({
				"visibility" : "visible"
				, "width" : _width
				, "height" : _height
				, "top" : (oThum.position().top + 1) + "px"
			});
		}
		oSelecter
		.stop()
		.animate({
			opacity : 1
			, left : oThum.position().left + 1
		}, 300, "easeOutCubic");
	};
	
	if($.browser.msie == true) {
		Image_Loader();
	} else {
		vImg.load(function() {
			Image_Loader();
		});
	}
	
};

var Page_Gallery_Loader = function(start_idx, type, choice_idx) {
	
	if(start_old_idx == start_idx && start_idx != 0) return;
	
	var bCount = $("> .thum_container > div", ".yhyh_thum_slider").length;
	
	if(start_idx >= total_count - 1) return;
	
	var limit = (slide_show_count + start_idx < total_count) ? slide_show_count : total_count - start_idx;
	
	start_old_idx = start_idx
	
	$.post("<?=_lh_yhyh_web?>/", { _module : "gallery_list_proc", _id : "<?=$_REQUEST["_id"]?>", _width : _width, _height : _height, _start_idx : start_idx, _limit : slide_show_count }, function(data) {
		var outDiv = $("<div/>").html(data);
		var vDiv = $("> div", outDiv);
		
		
		$("> ul", vDiv).each(function(i) {
			var oFile_data = $("> ._yhb_file", this);
			var oThum = $("<div/>")
			.data({
				"f_name" : $("> ul:eq(0) > ._f_naem", oFile_data).html()
				, "s_naem" : $("> ul:eq(0) > ._s_naem", oFile_data).html()
				, "url" : $("> ul:eq(0) > ._url", oFile_data).html()
				, "f_size" : $("> ul:eq(0) > ._f_size", oFile_data).html()
				, "f_download" : $("> ul:eq(0) > ._f_download", oFile_data).html()
				, "f_ext" : $("> ul:eq(0) > ._f_ext", oFile_data).html()
				, "f_order" : $("> ul:eq(0) > ._f_order", oFile_data).html()
				, "f_width" : $("> ul:eq(0) > ._f_width", oFile_data).html()
				, "f_height" : $("> ul:eq(0) > ._f_height", oFile_data).html()
			});
			
			var oThum_img = $("<img/>");
			oThum_img
			.attr({
				"src" : oThum.data("url")
			})
			.css({
				"display" : "block"
				, "max-width" : _width
				, "max-height" : _height
				//, "width" : oThum.data("f_width") + "px"
				//, "height" : oThum.data("f_height") + "px"
				//, "margin" : ((_height - Number(oThum.data("f_height"))) * 0.5) + "px auto"
			})
			.fadeTo(0, 0)
			.appendTo(oThum)
			.load(function() {
				oThum_img
				.css({
					"margin" : ((_height - oThum_img.height()) * 0.5) + "px auto"
				})
				.fadeTo(500, 1);
			});
			
			oThum.addClass("yhyh_thum")
			.css({
				"display" : "block"
				, "width" : _width + "px"
				, "height" : _height + "px"
				, "position" : "absolute"
				, "left" : ((_width + 12) * (i + start_old_idx) + 50) + "px"
				, "cursor" : "pointer"
			})
			.stop()
			.click(function() {
				Choice_Photo_View(i + 1 + bCount);
			})
			.fadeTo(0, 0)
			.delay(50 * i)
			.animate({
				left : ((_width + 12) * (i + start_old_idx))
				, opacity : 1
			}, 300, "easeOutCubic", function() {
				if(i == 0 && type != "no_slide") {
					//alert(choice_idx);
					if(choice_idx) {
						Choice_Photo_View((choice_idx) ? choice_idx : i + 1 + start_old_idx);
					}
				}
			})
			.appendTo(".yhyh_thum_slider > .thum_container");
			
		});
	});
};

var slide_eq = 0;

function Gallery_List_Resize() {
	var load_count = $("> .thum_container > div", ".yhyh_thum_slider").length;
	var slide_w = 0;
	var start_no = slide_eq;
	var end_no = (load_count > 0) ? load_count - 1 : 0;
	var start_thum = $("> .thum_container > div:eq(" + (start_no) + ")", ".yhyh_thum_slider");
	var end_thum = $("> .thum_container > div:eq(" + (end_no) + ")", ".yhyh_thum_slider");
	var start_left = start_thum.length > 0 ? start_thum.position().left : 0;
	var end_left = end_thum.length > 0 ? end_thum.position().left : 0;
	var end_width = end_thum.length > 0 ? end_thum.outerWidth() : 0;
	//alert(end_no);
	var dw = Number($(".yhyh_thum_body").data("dw"));
	if(!dw) {
		dw = Number($(".yhyh_thum_body").width());
		$(".yhyh_thum_body").data("dw", dw);
	}
	var w = $(".yhyh_list_thum").width() - 65;
	var sw = w;
	if(w <= dw) {
		sw = dw
	}
	slide_show_count = Math.ceil(sw / (_width + 12));
	$(".yhyh_thum_body").width(sw);
	
	var space = (end_left - start_left <= 0) ? sw : end_left - start_left + end_width + 50;
	
	$("body").attr("test", space + " = " + sw + " : " + slide_show_count + "/" + start_left + " - " + end_left);
	if(sw > space || load_count == 0) {
		//alert(load_count);
		Page_Gallery_Loader(load_count, load_count == 0 ? "" : "no_slide", load_count == 0 ? 1 : "");
	}
}
var Page_Change_Action = function(type, style) {
	var load_count = $("> .thum_container > div", ".yhyh_thum_slider").length, eq = 0, choice_idx = 1, start_idx, oThum, slide_left = 0, page;
	
	switch(type) {
		case "next":
			page = Math.ceil(choice_no / slide_show_count);
			start_idx = load_count;
			eq = page * slide_show_count;
			slide_eq = (eq < load_count) ? eq : eq - 1;
			//slide_eq = eq - 1;
			oThum = $("> .thum_container > div:eq(" + slide_eq + ")", ".yhyh_thum_slider");
			
			if(oThum.length == 0) return;
			
			slide_left = oThum.position().left;
			slide_left += (eq < load_count) ? 0 : oThum.width() + 12;
			choice_idx = (eq < load_count) ? eq + 1 : load_count + 1;
			
			if(load_count == total_count && total_count < choice_idx) return;
		break;
		case "prev":
			page = Math.ceil(choice_no / slide_show_count);
			choice_idx = ((page - 1) * slide_show_count > 0) ? (page - 1) * slide_show_count : slide_show_count;
			slide_eq = ((page - 2) * slide_show_count >= 0) ? (page - 2) * slide_show_count : 0;
			oThum = $("> .thum_container > div:eq(" + slide_eq + ")", ".yhyh_thum_slider");
			slide_left = oThum.position().left;
		break;
	}
	
	slide_left = slide_left * -1;
	
	$(".yhyh_thum_slider").stop().animate({
		left : slide_left
	}, 500, "easeInOutCubic", function() {
		if(type == "next") {
			if(eq + slide_show_count < load_count || load_count == total_count) {
				if(style != "no_choice") Choice_Photo_View(choice_idx);
			} else {
				Page_Gallery_Loader(start_idx, "", style != "no_choice" ? choice_idx : "");
			}
		} else {
			//alert(Math.ceil(choice_idx / slide_show_count) + " : " + page);
			if(Math.ceil(choice_idx / slide_show_count) != page) {
				if(style != "no_choice") Choice_Photo_View(choice_idx);
			}
		}
	});
};

$(window).load(function() {
	
	$(".cut_prev").click(function() {
		if(choice_no - 1 > 0) {
			Choice_Photo_View(choice_no - 1);
		}
	});
	
	$(".cut_next").click(function() {
		var count = $("> .thum_container > div", ".yhyh_thum_slider").length;
		if(choice_no + 1 <= count) {
			Choice_Photo_View(choice_no + 1);
		}
	});
	
	$(".page_prev").click(function() {
		Page_Change_Action("prev");
	});
	
	$(".page_next").click(function() {
		Page_Change_Action("next");
	});
	
	$(window).resize(function() {
		Gallery_List_Resize();
	});
	Gallery_List_Resize();
	//Page_Gallery_Loader(0);
});

function Grant_No_View(msg) {
	alert(msg);
}
</script>
<div class="yhyh_list">
	<div class="yhyh_list_header">
		<p class="numberFont">Article : <?=number_format($_total_rows)?></p>
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
	<div class="yhyh_list_body">
		<div class="yhyh_photo_body">
			<div class="yhyh_detail_photo">
			</div>
		</div>
		<a class="cut_prev" href="javascript:;">PREV</a>
		<a class="cut_next" href="javascript:;">NEXT</a>
	</div>
	<div class="yhyh_rows_thum">
		<div class="yhyh_rows_body">
			<div class="yhyh_rows_slider"></div>
		</div>
		<a class="rows_prev" href="javascript:;">PREV</a>
		<a class="rows_next" href="javascript:;">NEXT</a>
	</div>
	<div class="yhyh_list_thum">
		<div class="yhyh_thum_body">
			<div class="yhyh_thum_slider">
				<div class="yhyh_thum_selecter">
				</div>
				<div class="thum_container">
				</div>
			</div>
		</div>
		<a class="page_prev" href="javascript:;">PREV</a>
		<a class="page_next" href="javascript:;">NEXT</a>
	</div>
	<div class="yhyh_list_footer">
		<div class="yhyh_list_paging">
			<?=$_LhDb->Paging($link, 5, "_self");?>
		</div>
		<span class="yhyh_button_footer_left"><a href="<?=$_write_link?>" class="a_button">선택항목삭제</a></span>
		<span class="yhyh_button_footer_right"><a href="<?=$_write_link?>" class="a_button">글쓰기</a></span>
	</div>
</div>
<? if($_solo_mode) { ?>
</body>
</html>
<? } ?>