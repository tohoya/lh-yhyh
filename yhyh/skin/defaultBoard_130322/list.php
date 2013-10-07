<?php if($코딩시사용용) { ?>
<link href="../../common/css/default.css" rel="stylesheet" type="text/css">
<link href="../../common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<link href="css/default.css" rel="stylesheet" type="text/css">
<script src="../../common/js/jquery.min.js"></script>
<script src="../../common/js/jquery.easing.1.3.js"></script>
<script src="../../common/js/jquery.form.js"></script>
<script src="../../common/js/jquery.lh.string1st.js"></script>
<script src="../../common/js/jquery.lh.calender1st.js"></script>
<script src="../../common/js/common.js"></script>
<? } ?>
<script>
$(window).load(function() {
	$("#List_Check_Main")
	.click(function() {
		$(this).get(0).checked = true;
	})
	.get(0).checked = true;
	
	//$(".ellipsis_title").width(200);
	ListRowsEllipsis_title(".ellipsis_title");
	//ListRowsEllipsis_title(".ellipsis");
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
	<table>
		<colgroup>
			<col width="35px"/>
			<col width="40px"/>
			<col width="auto"/>
			<col width="120px"/>
			<col width="76px"/>
			<col width="47px"/>
		</colgroup>
		<thead>
			<tr>
				<th class="numberFont">No</th>
				<th><input type="checkbox" id="List_Check_Main"></th>
				<th>제 목</th>
				<th>작성자</th>
				<th>작성일</th>
				<th class="last">조회</th>
			</tr>
		</thead>
		<tbody>
		<?
		$count = sizeof($_result);
		for($i = 0; $i < $count; $i++) {
			$_bb = $_result[$i];
		?>
			<tr>
				<td class="numberFont"><?=$_bb->no?></td>
				<td><input type="checkbox" name="" value="<?=$_bb->number?>"></td>
				<td class="title"><a href="<?=$_bb->title_link?>" class="ellipsis_title" title="<?=htmlspecialchars($_bb->title)?>"><?=$_bb->title?></a></td>
				<td><span class="ellipsis" title="<?=htmlspecialchars($_bb->name)?>"><?=$_bb->name?></span></td>
				<td class="numberFont"><?=date("Y.m.d", $_bb->date)?></td>
				<td class="numberFont last"><?=number_format($_bb->hit)?></td>
			</tr>
		<? }
		if($count == 0) { ?>
			<tr>
				<td colspan="10">
					<p class="no_list_rows">현재 등록되어 있는 게시물이 없습니다.<br>새로 글 작성하시려면 아래에 있는 글쓰기 버튼을 클릭 후 작성하시기 바랍니다.</p>
				</td>
			</tr>
		<? } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="10" class="footer">
					<div class="yhyh_list_bottom">
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