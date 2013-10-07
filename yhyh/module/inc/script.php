<script>var $_yhyh_web = "<?=_lh_yhyh_web?>";</script>
<script>window.jQuery || document.write('<script src="<?=_lh_yhyh_web?>/common/js/jquery.min.js"><\/script>')</script>
<script>window.jQuery && document.write('<script src="<?=_lh_yhyh_web?>/common/js/jquery.easing.1.3.js"><\/script>')</script>
<script>window.browser || document.write('<script src="<?=_lh_yhyh_web?>/common/js/browser.js"><\/script>')</script>
<script>window.jQuery.mousewheel || document.write('<script src="<?=_lh_yhyh_web?>/common/js/jquery.mousewheel.js"><\/script>')</script>
<script>window.jQuery.fn.ajaxSubmit || document.write('<script src="<?=_lh_yhyh_web?>/common/js/jquery.form.js"><\/script>')</script>
<script>String().LayoutPop || document.write('<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.string1st.js"><\/script>')</script>
<script>window.FormMiniCalender || document.write('<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.calender1st.js"><\/script>')</script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.fn.1.4.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/jquery.lh.popup.1.1.js"></script>
<script src="<?=_lh_yhyh_web?>/common/js/common.js"></script>
<script>

var Delete_Complete;

function Select_Rows_Delete_Check(target) {
	var count = $(target).length;
	
	var delete_idx = "";
	$(target).each(function() {
		if($(this).get(0).checked) {
			delete_idx += delete_idx ? "," + $(this).val() : $(this).val();
		}
	});
	
	if(count == 0 || !delete_idx) {
		alert("선택하신 항목이 없습니다.");
		return false;
	}
	
	Rows_Delete_Check(delete_idx, "선택하신 항목을 삭제하시겠습니까?");
}

function Rows_Delete_Check(_idxs, msg) { //no, start_time, end_time) {
	if(!msg) msg = "이 글을 삭제하시겠습니까?";
	if(confirm(msg)) {
		$.post("<?=_lh_yhyh_web?>/", { _module : "delete_proc", _idxs : _idxs, _id : "<?=$_REQUEST["_id"]?>" }, function(data) {
			//alert(data);
			var p = $("> p", $("<p/>").html(data));
			switch(p.attr("class")) {
				case "error":
					alert(p.html());
				break;
				case "complete":
					if(Delete_Complete) {
						Delete_Complete({
							message : p.html()
						});
					} else {
						alert(p.html());
						location.reload();
					}
				break;
			}
		});
	}
}


(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ko_KR/all.js#xfbml=1&appId=635657806456193";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

</script>