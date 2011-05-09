$(function() {
	$("a[rel^=lightbox]").lightBox();

	$("a#xgdb_his_show").click(function(event) {
		$("a#xgdb_his_show").css("display", "none");
		$("a#xgdb_his_hide").css("display", "inline");
		$("table#xgdb_his_show_table tr.odd").show(1000);
		$("table#xgdb_his_show_table tr.even").show(1000);
		event.preventDefault();
	});

	$("a#xgdb_his_hide").click(function(event) {
		$("a#xgdb_his_show").css("display", "inline");
		$("a#xgdb_his_hide").css("display", "none");
		$("table#xgdb_his_show_table tr.odd").hide(1000);
		$("table#xgdb_his_show_table tr.even").hide(1000);
		event.preventDefault();
	});
});
