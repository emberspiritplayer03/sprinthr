var can_manage = "";
function load_view_all_audit_trail_datatable(searched,field)
{
	addActiveState('btn_viewall','btn-small');	
	element_id = 'audit_trail_datatable';
			
	var title = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
				//if(can_manage) {	
				//		elCell.innerHTML = "<a class='dropbutton' href="+base_url+"recruitment/profile?rid="+id+"&hash="+hash+"&status="+application_status_id+"#application_history><img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span></a>";
				//} else {
				//	elCell.innerHTML = "<img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span>"; 
				//}
			};
			
	var selection = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<div>"+options+"</div>";
					if(can_manage) {
						elCell.innerHTML = '<div id="dropholder"><a onClick="javascript:loadOptions('+id+');" class="dropbutton" id="dropButton_'+id+'"><span>Options</span></a><div id="options_'+id+'" class="dropcontent candidate_option" style="display:none;">'+options+'</div></div>'	
					} else  { elCell.innerHTML = "---"; }
			};						
			
		var columns = 	[{key:"id",label:"Id",width:20,resizeable:true,sortable:true},
						 {key:"user",label:"User",width:90,resizeable:true,sortable:true},
						 {key:"action",label:"Action",width:90,resizeable:true,sortable:true},
						 {key:"event_status",label:"Event Status",width:60,resizeable:true,sortable:true},
						 {key:"details",label:"Details",width:100,resizeable:true,sortable:true},
						 {key:"audit_date",label:"Audit Date",width:60,resizeable:true,sortable:true},
						 {key:"ip_address",label:"Ip Address",width:45,resizeable:true,sortable:true}
						 ];
						 
		var fields =	['id','user','action','event_status', 'details','audit_date','ip_address'];
		var height = 	'100%';
		var width = 	'100%';
		
		if(searched) {
			var controller = 'audit_trail/_json_encode_view_all_audit_trail_list_search?search='+searched+'&field='+field+'&';			
		}else {
			var controller = 'audit_trail/_json_encode_view_all_audit_trail_list?';
		}					
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 

function addActiveState(obj_id,class_name)
{
	$('.' + class_name).removeClass('active');
	$("#" + obj_id).addClass("active");
}

function searchAuditTrail()
{
	var searched = $("#search").val();
	var field = $("#category").val();
	load_view_all_audit_trail_datatable(searched,field);
}