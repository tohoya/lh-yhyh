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
var total_page = Math.ceil(total_count / slide_show_count);
var start_old_idx = 0;

var Choice_Photo_View = function(no, scroll_type) {
	if(choice_no == no) return;
	var type = (choice_no > no) ? 1 : -1;
	choice_no = no;
	
	var oThum = $("> .thum_container > div:eq(" + (no - 1) + ")", ".yhyh_thum_slider");
	var load_count = $("> .thum_container > div", ".yhyh_thum_slider").length;
	
	var oSelecter = $(".yhyh_thum_selecter");
	
	page = Math.ceil(choice_no / slide_show_count);
	if(load_count - choice_no < slide_show_count - 1) {
		if(page + 1 <= total_page && choice_no + slide_show_count - 1 < total_count) {
			page++;
			Page_Gallery_Loader(page, "no_slide");
		}
	}	
	if((type < 0 && choice_no % slide_show_count == 1) || (type > 0 && choice_no % slide_show_count == 0)) {
		if(!scroll_type) {
			var left = (type > 0 && choice_no % slide_show_count == 0) ? $("> .thum_container > div:eq(" + (no - slide_show_count) + ")", ".yhyh_thum_slider").position().left : oThum.position().left;
			$(".yhyh_thum_slider").stop().animate({
				left : left * -1
			}, 500, "easeInOutCubic", function() {
			});
		}
	}
	

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

var Page_Gallery_Loader = function(start_idx, type) {
	
	if(start_old_idx == start_idx && start_idx != 0) return;
	
	start_old_idx = start_idx
	
	var bCount = $("> .thum_container > div", ".yhyh_thum_slider").length;
	
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
					Choice_Photo_View(i + 1 + start_old_idx);
				}
			})
			.appendTo(".yhyh_thum_slider > .thum_container");
			
		});
	});
};

var Page_Change_Action = function(no, type) {
	page = no;
	var eq = (no - 1) * slide_show_count;
	eq += (type == "next") ? - 1 : 0;
	var count = $("> .thum_container > div", ".yhyh_thum_slider").length;
	var target = $("> .thum_container > div:eq(" + eq + ")", ".yhyh_thum_slider");
	var left = target.position().left;
	left += (type == "next") ? target.width() + 12 : 0;
	left = left * -1;
	
	var bCount = $("> .thum_container > div", ".yhyh_thum_slider").length;
	
	$(".yhyh_thum_slider").stop().animate({
		left : left
	}, 500, "easeInOutCubic", function() {
		if(type == "next") {
			if((no - 1) * slide_show_count >= count) {
				//alert(((no - 1) * slide_show_count) + " : " + count);
				Page_Gallery_Loader(bCount);
			} else {
				Choice_Photo_View(eq + 2);
			}
		} else {
			Choice_Photo_View(eq + slide_show_count);
		}
	});
};

function Gallery_List_Resize(type) {
	var dw = Number($(".yhyh_thum_body").data("dw"));
	if(!dw) {
		dw = Number($(".yhyh_thum_body").width());
		$(".yhyh_thum_body").data("dw", dw);
	}
	var w = $(".yhyh_list_thum").width() - 65;
	if(w > dw) {
		slide_show_count = Math.ceil(w / (_width + 12));
		$(".yhyh_thum_body").width(w);
	}
	if(w > dw) {
		slide_show_count = Math.ceil(w / (_width + 12));
		var load_count = $("> .thum_container > div", ".yhyh_thum_slider").length;
		if(load_count - choice_no < slide_show_count - 1) {
			if(choice_no + slide_show_count - 1 < total_count) {
				Page_Gallery_Loader(load_count, "no_slide");
			}
		}
	}
}

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
		var load_count = $("> .thum_container > div", ".yhyh_thum_slider").length;
		var page = Math.ceil(choice_no / slide_show_count);
		alert(1);
		if(choice_no - slide_show_count - 1 > 0) {
			Page_Change_Action(page - 1, "prev");
		}
	});
	
	$(".page_next").click(function() {
		if(page + 1 <= total_page) {
			Page_Change_Action(page + 1, "next");
		}
	});
	
	$(window).resize(function() {
		Gallery_List_Resize();
	});
	//Page_Gallery_Loader(page);
	Gallery_List_Resize("init");
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