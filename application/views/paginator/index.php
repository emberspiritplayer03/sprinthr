<div id="record_list"></div>

<script>
loadRecords();

function loadRecords() {
	$.post(base_folder + 'source/_show_records',{},
		function(data) {
				$("#record_list").html(data);
	});
}

 function gotoPage(page,div_id)
 {
	 
	$.post(base_folder + 'source/_show_records?page='+page,{},
		function(data) {
				$("#"+div_id).html(data);
	});
}
 </script>