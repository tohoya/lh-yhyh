// JavaScript Document

/**
 * 이름 : 메뉴 스타일링 및 액션 적용 클래스
 * 필요소스 : jQuery.1.7, jQuery.easeing.1.3
 * 마크업 형태 1
	<tag class="_menu_body">
		<tag class="_button_body">
			<a class="_button_link"><img class="_button_out"></a>
			<img class="_button_over" style="display:none; visibility:hidden;">
		</tag>
		<tag class="_button_body"><a class="_button_link"><img class="_button_out"></a><img class="_button_over" style="display:none; visibility:hidden;"></tag>
		<tag class="_button_body"><a class="_button_link"><img class="_button_out"></a><img class="_button_over" style="display:none; visibility:hidden;"></tag>
	</tab>
 * 마크업 형태 2
	<tag class="_menu_body">
		<tag class="_button_body">
			<a class="_button_link">
				<img class="_button_out">
				<img class="_button_over">
			</a>
		</tag>
		<tag class="_button_body"><a class="_button_link"><img class="_button_out"><img class="_button_over"></a></tag>
		<tag class="_button_body"><a class="_button_link"><img class="_button_out"><img class="_button_over"></a></tag>
	</tab>
 * 마크업 서브 추가 형태(1,2 공통) : 
	<tag class="_menu_body">
		<!-- 1 형태 -->
		<tag class="_button_body"><a class="_button_link"><img class="_button_out"></a><img class="_button_over" style="display:none; visibility:hidden;">
			<tag class="_menu_body" style="display:none; visibility:hidden; height:0px; overflow:hidden;">
				<tag class="_button_body"><a class="_button_link"><img class="_button_out"></a><img class="_button_over" style="display:none; visibility:hidden;"></tag>
				<tag class="_button_body"><a class="_button_link"><img class="_button_out"></a><img class="_button_over" style="display:none; visibility:hidden;"></tag>
			</tag>
		</tag>
		<!-- 2 형태 -->
		<tag class="_button_body"><a class="_button_link"><img class="_button_out"><img class="_button_over"></a>
			<tag class="_menu_body" style="display:none; visibility:hidden; height:0px; overflow:hidden;">
				<tag class="_button_body"><a class="_button_link"><img class="_button_out"><img class="_button_over"></a></tag>
				<tag class="_button_body"><a class="_button_link"><img class="_button_out"><img class="_button_over"></a></tag>
			</tag>
		</tag>
	</tab>
 * 입력 값 설명 : 
	$target : 메뉴가 시작되어야할 셀렉트 값
	$naviName : 메뉴 이름(현재 사용안함)
	$depth : 뎁스 깊이로 1 이상일 경우 pgCode 값에 앞 부분을 Depth 깊이 만큼 무시한다.
	$pg_code : 페이지 코드로 형태는 1/2 와 같은 형식으로 만듬 - 1뎁스는 1페이지에 속하는 두번째 서브 페이지
 * 작성자 : Laintohoya(redhoya@gmail.com)
 * 작성일 : 2013. 01. 14(월)
 * 업그레이드 : 2013. 01. 15(화)
 */
 var Lh_Navi_Show = function($target, $naviName, $depth, $pg_code) {
	if($target && $naveName && $depth && $pg_code) this.Init($target, $naviName, $depth, $pg_code);
};
Lh_Navi_Show.prototype = {
	target_object : ""
	, navi_name : ""
	, depth_count : 1
	, url_request : ""
	, pg_code : ""
	, pg_settimeout : 0
	, duration : 500
	, pattern : /pgCode\=/
	, pg_array : new Array
	, location_select : "._location_path"
	, location_object : null
	
	/**
	 * 설명 : 메뉴 초기화 시작하는 역할
	 * 작성자 : LaintoHoya(redhoya@gmail.com)
	 * 작성일 : 2013. 01. 15(화)
	 */
	, Location_Insert : function() {
		var this_Class = this;
	
		if(this_Class.location_object == null) {
			this_Class.location_object = $(this_Class.location_select);
		}
		
		if(this_Class.location_object.length == 0) return;
		
		$("<a/>").attr("href", "/").html("홈").stop().fadeTo(0, 0).appendTo(this_Class.location_object).stop().fadeTo(500, 1);
		
		var Location_Set = function($parent, $depth_count, $parent_choice) {
			var location_span;
			$("> ._button_body", $parent).each(function(i) {
				var choice_object = false;
				var oA = $("> ._button_link", this);
				var oSub = $("._menu_body:first", this);
				var oOut = $("> ._button_out", oA);
				if(($parent_choice || $depth_count == 1) && i + 1 == this_Class.pg_array[$depth_count - 1]) {
					choice_object = true;
				}
				
				if(oSub.length > 0) { // 서브 body 가 있으면
					if($("> ._button_body", oSub).length > 0) { // 서브 body 하위에 메뉴 body가 있으면
						if(choice_object) location_span = $("<a/>").attr("href", oA.attr("href")).html(" &gt; " + oOut.attr("alt")).stop().fadeTo(0, 0).appendTo(this_Class.location_object).stop().delay(200 * Number($depth_count)).fadeTo(500, 1);
						Location_Set(oSub, $depth_count + 1, choice_object);
					} else {
						if(choice_object) location_span = $("<span/>").html(" &gt; <b>" + oOut.attr("alt") + "</b>").stop().fadeTo(0, 0).appendTo(this_Class.location_object).stop().delay(200 * Number($depth_count)).fadeTo(500, 1);
					}
				} else {
					if(choice_object) location_span = $("<span/>").html(" &gt; <b>" + oOut.attr("alt") + "</b>").stop().fadeTo(0, 0).appendTo(this_Class.location_object).stop().delay(200 * Number($depth_count)).fadeTo(500, 1);
				}
			});
		};
		
		Location_Set($("._menu_body:first", this_Class.target_object), 1);
	}
	, Init : function($target, $naviName, $depth, $pg_code) {
		var this_Class = this;
		this_Class.url_request = location.href;
		
		if(!$target) { // 타겟 변수값이 없으면 경고 메세지
			alert("타겟 값이 적용되지 않았습니다.");
			return;
		}
		
		this_Class.target_object = $($target);
		
		if(this_Class.target_object.length == 0) { // 타겟에 대한 Element가 존재하지 않으면 경고 메세지
			alert($target + "으로 된 Element를 찾지 못했습니다.");
			return;
		}
		
		if($pg_code) {
			this_Class.pg_code = $pg_code;
		} else {
			var url = this_Class.url_request;
			if(url.match(this_Class.pattern)) {
				this_Class.pg_code = String(url.split("pgCode=")[1]).split("&")[0];
			}
		}
		
		if($depth) this_Class.depth_count = Number($depth); // 시작해야할 뎁스
		if($naviName) this_Class.navi_name = $naviName.toString(); // 기본 네비에 대한 이름(현재 사용안함)
		
		if(this_Class.pg_code) { // 코드를 뎁스별(배열 형태로 변환
			this_Class.pg_array = this_Class.pg_code.split("/");
			if(this_Class.depth_count > 1) { // 제외할 뎁스가 있으면 제외 시킴
				this_Class.pg_array.splice(0, this_Class.depth_count - 1);
			}
		}
		
		// 메뉴 세팅
		this_Class.Menu_Init($("._menu_body:first", this_Class.target_object), 1);
		
		// 로케이션 세팅
		this_Class.Location_Insert();
		
	}
	
	/**
	 * 설명 : 기본 메뉴를 세팅해주는 역할
	 * 작성자 : LaintoHoya(redhoya@gmail.com)
	 * 작성일 : 2013. 01. 15(화)
	 */
	, Menu_Init : function($parent, $depth_count, $parent_choice) {
		var this_Class = this;
		$("> ._button_body", $parent).each(function(i) {
			var choice_object = false;
			var oA = $("> ._button_link", this);
			var oOut = $("> ._button_link > ._button_out", this);
			var oOver = $("> ._button_link > ._button_over", this);
			var oBg = $("> ._button_over", this);
			var oSub = $("._menu_body:first", this);
			
			if(oOver.length > 0) {
				oA.css({
					"display" : "block"
					, "position" : "relative"
				}); // 메뉴 링크 태그 스타일링
				oOut.stop()
				.fadeTo(0, 0)
				.css({
					"display" : "block"
					, "position" : "absolute"
				}); // 메뉴 아웃이미지 태그 스타일링
				oOver.stop()
				.fadeTo(0, 1)
				.css({
					"display" : "block"
					, "position" : "absolute"
				}); // 메뉴 오버이미지 태그 스타일링
				
			} else {
				oA.css({
					"display" : "block"
				}); // 메뉴 링크 태그 스타일링
				
				if(oBg.length > 0 && oBg.attr("src")) {
					oA.css({
						"background" : "url(" + oBg.attr("src") + ")"
					}); // 메뉴 링크 태그 스타일링
				}
			}
			oA.mouseenter(function() {
				clearTimeout(this_Class.pg_settimeout);
				this_Class.Over_Menu(oSub, 0, "button");
			});
			$(this).css({
				"display" : "block"
				, "position" : "relative"
			}) // 메뉴 body 스타일링
			.hover(function() {
				this_Class.Over_Menu($parent, (i + 1));
			}, function() {
				this_Class.Over_Menu($parent, 0);
			}); // 메뉴 body에 오버 아웃 이벤트 부여
			
			oBg.remove(); // span 삭제
			
			var h = $parent.data("dh") ? Number($parent.data("dh")) : 0;
			h += $(this).height();
			$parent.data("dh", h);
			
			if(($parent_choice || $depth_count == 1) && i + 1 == this_Class.pg_array[$depth_count - 1]) {
				choice_object = true;
			}
			
			if(oSub.length > 0) { // 서브 body 가 있으면
				//alert(i);
				oSub
				.stop().animate({ height : 0, opacity : 0 }, 0)
				.css({
					"display" : "block"
					, "position" : "relative"
					, "visibility" : "visible"
					, "height" : "0px"
					, "overflow" : "hidden"
				}); // 서브 body 스타일링 및 초기 에니메이션
				
				if($("> ._button_body", oSub).length > 0) { // 서브 body 하위에 메뉴 body가 있으면
					this_Class.Menu_Init(oSub, $depth_count + 1, choice_object);
				}
			}
			if(choice_object) {
				this_Class.Over_Menu($parent, (i + 1));
			}
			
		});
		//this_Class.Menu_Select(0);
	}
	
	/**
	 * 설명 : 메뉴 오버 아웃시 효과를 구분 해주는 역할
	 * 작성자 : LaintoHoya(redhoya@gmail.com)
	 * 작성일 : 2013. 01. 15(화)
	 */
	, Over_Menu : function($parent, $no, $type) {
		var this_Class = this;
		clearTimeout(this_Class.pg_settimeout);
		if($no == 0 && !$type) {
			this_Class.Menu_Select(200);
			return;
		}
		$("> ._button_body", $parent).each(function(i) {
			if(($no - 1) == i) { // 선택된 번호와 인덱스 번호가 같으면 혹은 다르면
				this_Class.Button_Effect($(this), "over", $type);
			} else {
				this_Class.Button_Effect($(this), "out", $type);
			}
		});
	}
	
	/**
	 * 설명 : 타임 시간 이후 셀렉트 될 함수 실행하는 역할
	 * 작성자 : LaintoHoya(redhoya@gmail.com)
	 * 작성일 : 2013. 01. 15(화)
	 */
	, Menu_Select : function($time) {
		var this_Class = this;
		
		//alert($time);
		var Menu_Select_Time = function() {
			this_Class.Menu_Select_Action($("._menu_body:first", this_Class.target_object), 1);
		};
		
		this_Class.pg_settimeout = setTimeout(Menu_Select_Time, $time);
	}
	
	/**
	 * 설명 : 메뉴 pgCode 값에 따라 메뉴 셀렉터 역할
	 * 작성자 : LaintoHoya(redhoya@gmail.com)
	 * 작성일 : 2013. 01. 15(화)
	 */
	, Menu_Select_Action : function($parent, $depth_count, $parent_choice) {
		var this_Class = this;
		$("> ._button_body", $parent).each(function(i) {
			var choice_object = false;
			var oSub = $("._menu_body:first", this);
			if(($parent_choice || $depth_count == 1) && i + 1 == this_Class.pg_array[$depth_count - 1]) {
				this_Class.Button_Effect($(this), "over");
				choice_object = true;
			} else {
				this_Class.Button_Effect($(this), "out");
			}
			
			if(oSub.length > 0) { // 서브 body 가 있으면
				if($("> ._button_body", oSub).length > 0) { // 서브 body 하위에 메뉴 body가 있으면
					this_Class.Menu_Select_Action(oSub, $depth_count + 1, choice_object);
				}
			}
		});
	}
	
	/**
	 * 설명 : 메뉴 오버 아웃시 효과를 주는 역할
	 * 작성자 : LaintoHoya(redhoya@gmail.com)
	 * 작성일 : 2013. 01. 15(화)
	 */
	, Button_Effect : function($button_body, $type, $button_type) {
		var this_Class = this;
		var oSub = $("._menu_body:first", $button_body);
		var oOut = $("> ._button_link > ._button_out", $button_body);
		var oOver = $("> ._button_link > ._button_over", $button_body);
		var oParent = oSub.parents("._menu_body");
		
		switch($type) {
			case "over":
				if(!$button_type) {
					oSub.css({
						"display" : "block"
						, "position" : "relative"
						, "visibility" : "visible"
					}).stop().animate({
						height : Number(oSub.data("dh"))
						, opacity : 1
					}, this_Class.duration, "easeInOutCubic");
					if(oParent.length > 0) {
						oParent.css("height", "auto");
					}
				}
				if(oOver.length > 0) {
					oOver.stop().fadeTo(this_Class.duration, 1);
					oOut.stop().fadeTo(this_Class.duration, 0);
				} else {
					$("> a > img", $button_body).stop().fadeTo(this_Class.duration, 0);
				}
			break;
			case "out":
				if(!$button_type) {
					if(oSub.outerHeight() > 0) {
						oSub.stop().animate({
							height : 0
							, opacity : 0
						}, this_Class.duration, "easeInOutCubic");
					}
				}
				if(oOver.length > 0) {
					oOver.stop().fadeTo(this_Class.duration, 0);
					oOut.stop().fadeTo(this_Class.duration, 1);
				} else {
					$("> a > img", $button_body).stop().fadeTo(this_Class.duration, 1);
				}
			break;
		}
	}
}
