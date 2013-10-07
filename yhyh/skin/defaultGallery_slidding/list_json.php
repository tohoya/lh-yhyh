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

var _original_data = new Array();

var page = 1;
var total_count = 0;
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
	
	var arr = _original_data[no - 1];
	var oSlider = $(".yhyh_rows_body");
	var oThum = $("> .thum_container > div:eq(" + (no - 1) + ")", ".yhyh_thum_slider");
	var oSelecter = $(".yhyh_thum_selecter");
	var big_photo = $(".yhyh_detail_photo");
	var f_count = arr.yhb_file.length;
	
	$(".yhyh_thum_title > a").attr("href", arr.yhb_title_link).html(arr.yhb_title);
	
	var Photo_Slide_Show = function(t_Img) {
		
		var url = t_Img.attr("src");
		t_Img.addClass("choice_s_thum");
		if(t_Img.siblings().length > 0) {
			t_Img.siblings().removeClass("choice_s_thum");
		}
		$("> a", big_photo).each(function() {
			$(this).stop().animate({
				opacity : 0
				, left : 100 * type
			}, 500, "easeOutCubic", function() {
				$(this).remove();
			});
		});
		
		var oA = $("<a/>")
		.attr("href", arr.yhb_title_link)
		.css({
			"display" : "block"
			, "position" : "absolute"
		})
		.fadeTo(0, 0)
		.appendTo(big_photo);
		
		var vImg = $("<img/>");
		
		vImg
		.attr("src", url)
		.css({
			"display" : "block"
		})
		.appendTo(oA);
		
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
				, "height" : bPhoto_height + "px"
			})
			.stop()
			.animate({
				width : vImg.width()
			}, 500, "easeOutCubic");
			
			oA
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
	
	$("> img", oSlider).each(function() {
		$(this).remove();
	});
	var bw = 0;
	
	var tmpDiv = $("<div/>");
	
	for(var f = 0; f < f_count; f++) {
		$("<p/>").appendTo(tmpDiv);
	}
	
	$("> p", tmpDiv).each(function(f) {
		var file_data = arr.yhb_file[f];
		var t_img = $("<img/>")
		.attr({
			"src" : file_data.url
		})
		.click(function() {
			Photo_Slide_Show($(this));
		})
		.fadeTo(0, 0)
		.stop()
		.delay(100 * f)
		.animate({
			opacity : 1
		}, 500, "easeOutCubic", function() {
		})
		.appendTo(oSlider);
		if(f == 0) {
			Photo_Slide_Show(t_img);
		}

		bw += t_img.width() + 4;
	});
	
	var oSlide_body = $(".yhyh_rows_body");
	bw = (bw < 600) ? bw : 600;
	oSlide_body.css({
		"width" : (bw) + "px"
	});
	
};

var Page_Gallery_Loader = function(start_idx, type, choice_idx) {
	
	if(start_old_idx == start_idx && start_idx != 0) return;
	
	var thum_container = $("> .thum_container", ".yhyh_thum_slider");
	var bCount = thum_container.children().length;
	
	if(start_idx >= total_count - 1 && total_count != 0) return;
	
	var limit = (slide_show_count + start_idx < total_count) ? slide_show_count : total_count - start_idx;
	
	start_old_idx = start_idx
	var params = {
		_module : "gallery_list_json"
		, _id : "<?=$_REQUEST["_id"]?>"
		, _width : _width
		, _height : _height
		, _start_idx : start_idx
		, _limit : slide_show_count
	};
	var url = "<?=_lh_yhyh_web?>/";
	$.getJSON(url, params, function(data, status) {
	//$.post(url,params, function(data) {
		
		var outDiv = $("<div/>");
		if(total_count == 0) total_count = data.total_count;
		for(var i in data.gallery) {
			if(data.gallery[i] && data.gallery[i].yhb_file && data.gallery[i].yhb_file.length > 0) {
				data.gallery[i].yhb_title_link = "<?=$PHP_SELF?>?<?=$query_string?>&_no=" + data.gallery[i].yhb_number;
				_original_data.push(data.gallery[i]);
				$("<p/>").appendTo(outDiv);
			} else {
				total_count--;
			}
		}
		
		$("> p", outDiv).each(function(i) {
			var arr = data.gallery[i];
			
			var oThum = $("<div/>");
			var oThum_img = $("<img/>");
			oThum_img
			.attr({
				"src" : arr.yhb_file[0].url
			})
			.css({
				"display" : "block"
			})
			.fadeTo(0, 0)
			.appendTo(oThum);
			
			var Thum_Image_Loader = function() {
				oThum_img
				.css({
					"margin" : ((_height - oThum_img.height()) * 0.5) + "px auto"
				})
				.fadeTo(500, 1);
			};
			
			if($.browser.msie) {
				var wsize = _width, hsize = _height, scale = 1;
				if(oThum_img.width() == 0) oThum_img.width(Number(arr.yhb_file[0].f_width));
				if(oThum_img.height() == 0) oThum_img.height(Number(arr.yhb_file[0].f_height));
				if(oThum_img.width() > oThum_img.height()) {
					wsize = oThum_img.width() > _width ? _width : oThum_img.width();
					scale = oThum_img.width() / _width;
					hsize = oThum_img.height() / scale;
				} else {
					hsize = oThum_img.height() > _height ? _height : oThum_img.height();
					scale = oThum_img.height() / _height;
					wsize = oThum_img.width() / scale;
				}
				oThum_img.attr({
					"width" : (wsize) + "px"
					, "height" : (hsize) + "px"
				});
				
				Thum_Image_Loader();
			} else {
				oThum_img.css({
					"max-width" : _width + "px"
					, "max-height" : _height + "px"
				});
				
				oThum_img
				.load(function() {
					Thum_Image_Loader();
				});
			}
			
			
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
				$("._load_count").html(thum_container.children().length + "/" + total_count);
				if(i == 0 && type != "no_slide") {
					if(choice_idx) {
						Choice_Photo_View((choice_idx) ? choice_idx : i + 1 + start_old_idx);
					}
				}
			})
			.appendTo(thum_container);
			
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
	<div class="yhyh_list_body">
		<div class="yhyh_photo_body">
			<div class="yhyh_detail_photo">
			</div>
		</div>
		<a class="a_button cut_prev" href="javascript:;">&lt;</a>
		<a class="a_button cut_next" href="javascript:;">&gt;</a>
	</div>
	<h2 class="yhyh_thum_title"><a></a></h2>
	<div class="yhyh_rows_thum">
		<div class="yhyh_rows_body">
		</div>
	</div>
	<div class="yhyh_list_header">
		<p class="numberFont">Gallery : <span class="_load_count">0/0</span></p>
		<div class="list_header_right">
			<? if($_member->yhb_number) { ?>
				<a href="<?=$_write_link?>" class="a_button numberFont">Uploaded Image</a>
				<a href="<?=$_logout_link?>" class="a_button numberFont">Logout</a>
			<? } else { ?>
			<a href="<?=$_admin_link?>&_returnType=return" class="a_button numberFont">Admin</a>
			<? } ?>
		</div>
	</div>
	<div class="yhyh_list_thum">
		<div class="yhyh_thum_body">
			<div class="yhyh_thum_slider">
				<div class="thum_container">
				</div>
				<div class="yhyh_thum_selecter">
				</div>
			</div>
		</div>
		<a class="a_button page_prev" href="javascript:;">&lt;</a>
		<a class="a_button page_next" href="javascript:;">&gt;</a>
	</div>
</div>
<? if($_solo_mode) { ?>
</body>
</html>
<? } ?>