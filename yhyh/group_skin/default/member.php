<?php if($코딩시사용용) { ?>
<link href="<?=_lh_yhyh_web?>/common/css/default.css" rel="stylesheet" type="text/css">
<link href="<?=_lh_yhyh_web?>/common/css/formDesignNormal.css" rel="stylesheet" type="text/css">
<script src="<?=_lh_yhyh_web?>/common/js/jquery.min.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.calender1st.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/common.js"></script>
<? } ?>
<link href="<?=_lh_yhyh_web?>/skin/defaultBoard/css/default.css" rel="stylesheet" type="text/css">
<script>
$(window).load(function() {
	$("#List_Check_Main")
	.click(function() {
		$(this).get(0).checked = true;
	})
	.get(0).checked = true;
});
</script>
<div id="yhyh_board_main_body" class="yhyh_list">
	<div class="yhyh_list_header">
		<p class="numberFont">Article : <?=number_format($_total_rows)?></p>
		<div class="list_header_right">
			<a href="<?=$PHP_SELF?>?_module=login&_id=<?=$_REQUEST["_id"]?>" class="a_button numberFont">Login</a>
			<a href="<?=$PHP_SELF?>?_module=register&_id=<?=$_REQUEST["_id"]?>" class="a_button numberFont">Join</a>
			<a href="<?=$PHP_SELF?>?_module=login&_id=<?=$_REQUEST["_id"]?>" class="a_button numberFont">Admin</a>
		</div>
	</div>
	<table>
		<colgroup>
			<col width="50px"/>
			<col width="10px"/>
			<col width="auto"/>
			<col width="120px"/>
			<col width="100px"/>
			<col width="70px"/>
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
				<td class="title"><a href="<?=$_bb->title_link?>" class="ellipsis_title" title="<?=$_bb->title?>"><?=$_bb->title?></a></td>
				<td><span class="ellipsis" title="<?=$_bb->name?>"><?=$_bb->name?></span></td>
				<td class="numberFont"><?=date("Y.m.d", $_bb->date)?></td>
				<td class="numberFont last"><?=number_format($_bb->hit)?></td>
			</tr>
		<? } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="10">
					<div class="yhyh_list_paging"><?=$_LhDb->Paging($link, 5, "_self");?><span><a href="<?=$_write_link?>" class="a_button">글쓰기</a></span></div>
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<!--<a href="" class="a_button">다시보기</a>-->
</div>