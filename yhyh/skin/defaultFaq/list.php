<?php if($코딩시사용용) { ?>
<link href="../../common/css/default.css" rel="stylesheet" type="text/css">
<link href="../../common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="css/default.css" rel="stylesheet" type="text/css">
<script src="../../common/js/jquery.min.js"></script>
<script src="../../common/js/jquery.easing.1.3.js"></script>
<script src="../../common/js/jquery.form.js"></script>
<script src="../../common/js/jquery.lh.string1st.js"></script>
<script src="../../common/js/jquery.lh.calender1st.js"></script>
<script src="../../common/js/jquery.lh.fn.1.4.js"></script>
<script src="../../common/js/common.js"></script>
<? } ?>
<script>
$(window).load(function() {
	/*$("#List_Check_Main")
	.click(function() {
		$(this).get(0).checked = true;
	})
	.get(0).checked = true;
	
	ListRowsEllipsis_title(".ellipsis_title");*/
});
$(document).ready(function() {
	$(".ellipsis_title").RowsEllipsis_LH();
	$(".ellipsis").RowsEllipsis_LH();
	
	$("#List_Check_Main").Checkbox_All_Check_LH(".lh-board-list-check", true);
});

function Grant_No_View(msg) {
	alert(msg);
}

function List_Content_View(target) {
	var iframe = $("> iframe", target);
	if(!iframe.data("height_key")) {
		iframe.data("height_key", true);
		var iframe_body = iframe.get(0).contentWindow.document.body;
		$(target).css("display", "block");
		$("img", iframe_body).each(function() {
			if(iframe.width() < $(this).width()) {
				$(this).css("max-width", iframe.width() - 5);
			}
		});
		iframe.height($(iframe_body).height() + 18);
		$(target).css("display", "none");
	}
	$(".content_div_show").each(function() {
		if($(this).attr("id") != $(target).attr("id")) {
			$(this).slideUp(300);
		}
	});
	$(target).slideToggle(300);
}
</script>
<div class="yhyh_list">
	<div class="yhyh_list_header">
		<p class="numberFont">Article : <?=number_format($_total_rows)?></p>
		<div class="list_header_right">
			<? if($_member->yhb_number) { ?>
				<a href="<?=$_logout_link?>" class="a_button numberFont">Logout</a>
			<a href="<?=$_register_link?>&_auto=true" class="a_button numberFont">Info</a>
			<? if($_LhDb->Get_Admin() == "s") { ?>
			<a href="<?=_lh_yhyh_web?>/admin/index.php?_admin=board_register&_id=<?=$_config->yhb_name?>" class="a_button numberFont" target="_blank">Admin</a>
			<? } ?>
			<? } else { ?>
			<a href="<?=$_login_link?>&_returnType=return" class="a_button numberFont">Login</a>
			<a href="<?=$_register_link?>&_returnType=return" class="a_button numberFont">Join</a>
			<? } ?>
		</div>
	</div>
	<table>
		<colgroup>
			<col width="1px">
			<? if($_LhDb->Get_Admin()) { ?>
			<col width="40px">
			<? } ?>
			<col width="auto">
			<col width="1px">
		</colgroup>
		<thead>
			<tr>
				<th></th>
				<? if($_LhDb->Get_Admin()) { ?>
				<th><input type="checkbox" id="List_Check_Main"></th>
				<? } ?>
				<th><?=$_config->yhb_title_name ? $_config->yhb_title_name : "&nbsp;"?></th>
				<th class="last"></th>
			</tr>
		</thead>
		<tbody>
		<?
		$count = sizeof($_result);
		for($i = 0; $i < $count; $i++) {
			$_bb = $_result[$i];
		?>
			<tr class="yhyh_list_faq_title">
				<td></td>
				<? if($_LhDb->Get_Admin()) { ?>
				<td><input type="checkbox" name="" class="lh-board-list-check" value="<?=$_bb->number?>"></td>
				<? } ?>
				<td class="title"><?=$_bb->icon_viewer?><a onClick="List_Content_View('#content_div_<?=$_bb->number?>');" class="ellipsis_title" title="<?=htmlspecialchars($_bb->title)?>"><?=$_bb->icon_reply?><?=$_bb->icon_notice?><?=$_bb->icon_secret?><?=$_bb->icon_new?><?=$_bb->title?></a>
				<? if($_LhDb->Get_Admin()) { ?>
					<span>
						&nbsp;&nbsp;<a class="a_button" href="<?=$_modify_link?>">수 정</a><a class="a_button" href="<?=$_delete_link?>">삭 제</a>
					</span>
				<? } ?>
				</td>
				<td class="last"></td>
			</tr>
			<tr class="yhyh_list_content">
				<td></td>
				<? if($_LhDb->Get_Admin()) { ?>
				<td></td>
				<? } ?>
				<td>
					<div id="content_div_<?=$_bb->number?>" class="content_div_show">
						<iframe id="content_frame_<?=$_bb->number?>" src="<?=_lh_yhyh_web?>/?_module=view&_frame_height=0&_content_frame=content_frame_<?=$_bb->number?>&_no=<?=$_bb->number?>" frameborder="0"></iframe>
					</div>
				</td>
				<td class="last"></td>
			</tr>
		<? }
		if($count == 0) { ?>
			<tr>
				<td colspan="5">
					<p class="no_list_rows">현재 등록되어 있는 게시물이 없습니다.<br>새로 글 작성하시려면 아래에 있는 글쓰기 버튼을 클릭 후 작성하시기 바랍니다.</p>
				</td>
			</tr>
		<? } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" class="footer">
					<div class="yhyh_list_bottom">
						<? if(eregi("s|g|b", $_LhDb->Get_Admin())) { ?>
						<span class="yhyh_button_footer_left"><a href="#delete-check" class="a_button" onClick="Select_Rows_Delete_Check('.lh-board-list-check'); return false;">선택항목삭제</a></span>
						<? } ?>
						<div class="yhyh_list_paging">
							<?=$_LhDb->Paging($link, 5, "_self");?>
						</div>
						<!--span class="yhyh_button_footer_left"><a href="<?=$_write_link?>" class="a_button">선택항목삭제</a></span-->
						<span class="yhyh_button_footer_right"><a href="<?=$_write_link?>" class="a_button">글쓰기</a></span>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<!--<a href="" class="a_button">다시보기</a>-->
</div>