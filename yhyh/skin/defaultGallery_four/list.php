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
<script src="../../common/js/jquery.lh.popup.1.1.js"></script>
<script src="../../common/js/common.js"></script>
<? } ?>
<script>
$(window).load(function() {
});
$(document).ready(function() {
	$(".lh-board-list > ul > li > dl > dd > a").RowsEllipsis_LH();
	$(".lh-board-list > ul > li > dl > dd > .lh-board-list-date").RowsEllipsis_LH();
	$(".lh-board-list > ul > li > dl > dd > .lh-board-list-name").RowsEllipsis_LH();
	
	$("#List_Check_Main").Checkbox_All_Check_LH(".lh-board-list-check", true);
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
			<? if($_LhDb->Get_Admin() == "s") { ?>
			<a href="<?=_lh_yhyh_web?>/admin/index.php?_admin=board_register&_id=<?=$_config->yhb_name?>" class="a_button numberFont" target="_blank">Admin</a>
			<? } ?>
			<? } else { ?>
			<a href="<?=$_login_link?>&_returnType=return" class="a_button numberFont">Login</a>
			<a href="<?=$_register_link?>&_returnType=return" class="a_button numberFont">Join</a>
			<? } ?>
		</div>
	</div>
	<div>
		<div class="lh-board-head">
			<? if(eregi("s|g|b", $_LhDb->Get_Admin())) { ?>
			<input type="checkbox" id="List_Check_Main"><label for="List_Check_Main">전체선택</label>
			<? } ?>
		</div>
		<?
		$count = sizeof($_result);
		if($count > 0) { ?>
		<div class="lh-board-list">
			<ul>
		<?
		for($i = 0; $i < $count; $i++) {
			$_bb = $_result[$i];
		?>
				<li>
					<dl>
						<dt>
							<a href="<?=$_bb->file[0]->url?>" onClick="String('<?=$_bb->file[0]->url?>').lh_Block_Popup({action : 'next'}); return false;"><img src="<?=$_bb->file[0]->url?>" alt="<?=$_bb->title?>"></a>
							<? if(eregi("s|g|b", $_LhDb->Get_Admin())) { ?>
							<input type="checkbox" name="" class="lh-board-list-check" value="<?=$_bb->number?>">
							<? } ?>
							<?=$_bb->icon_viewer?>
						</dt>
						<dd>
							<a href="<?=$_bb->title_link?>" class="ellipsis_title" title="<?=htmlspecialchars($_bb->title)?>"><?=$_bb->icon_reply?><?=$_bb->icon_notice?><?=$_bb->icon_secret?><?=$_bb->icon_new?><?=$_bb->title?></a>
						</dd>
						<dd>
							<span class="lh-board-list-date"><?=date("Y.m.d", $_bb->date)?></span>
						</dd>
						<dd>
							<span class="lh-board-list-name"><?=$_bb->name?></span>
						</dd>
					</dl>
				</li>
		<? } ?>
			</ul>
		</div>
		<? } else  { ?>
		<div class="lh-board-no-list">
			<p class="no_list_rows">현재 등록되어 있는 게시물이 없습니다.<br>새로 글 작성하시려면 아래에 있는 글쓰기 버튼을 클릭 후 작성하시기 바랍니다.</p>
		</div>
		<? } ?>
		<div class="footer">
			<div class="yhyh_list_bottom">
				<? if(eregi("s|g|b", $_LhDb->Get_Admin())) { ?>
				<span class="yhyh_button_footer_left"><a href="#delete-check" class="a_button" onClick="Select_Rows_Delete_Check('.lh-board-list-check'); return false;">선택항목삭제</a></span>
				<? } ?>
				<div class="yhyh_list_paging">
					<?=$_LhDb->Paging($link, 5, "_self");?>
				</div>
				<span class="yhyh_button_footer_right"><a href="<?=$_write_link?>" class="a_button">글쓰기</a></span>
			</div>
		</div>
	</div>
	
	
	<!--<a href="" class="a_button">다시보기</a>-->
</div>