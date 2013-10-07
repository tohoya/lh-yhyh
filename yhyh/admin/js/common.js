// JavaScript Document
$(window).load(function() {
	if($("#List_Check_Main").length > 0) {
		$("#List_Check_Main")
		.click(function() {
			$(this).get(0).checked = true;
		})
		.get(0).checked = true;
	}
	ListRowsEllipsis_title(".ellipsis_title");
});
