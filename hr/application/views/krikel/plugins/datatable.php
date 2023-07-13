<p><strong>Demo</strong> <br />
Datatable

<a id="selectall" href="#" onclick="return selectAll();">Select All</a> &nbsp;|&nbsp;
<a id="unselectall" href="#" onclick="return unselectAll();">Unselect All</a>
<div class="yui-skin-sam">
	<div id="user_datatable_wrapper"></div>
</div>
<textarea name="list_box" id="list_box" ></textarea>

<script>
function updateTextArea() {
	var allVals = [];
     $('#user_datatable_wrapper :checked').each(function() {
       allVals.push($(this).val());
	 //alert($("#bill_check").val());
     });

     $('#list_box').val(allVals);
	 list_box =  $('#list_box').val();

	$.post(base_url +'source/_update_container',{list_box:list_box},
	function(o){});
}


loadUserDatatable();
function loadUserDatatable()
{
	element_id = 'user_datatable_wrapper';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<center> <a href=\"javascript:delete("+ id +");\">Delete</a>  <a href=\"javascript:view("+ id +");\">Display</a>"; 
	
			};
			
	var test = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
			};
	var checked = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
				elCell.innerHTML = "<input id='bill_check' name='"+ id +"' type=checkbox onclick='javascript:updateTextArea();' value="+id+">"; 
			};		
			
			
		var columns = 	[
						 {key:"id",label:"s",width:13,resizeable:true,sortable:false, formatter:checked},
						 {key:"test",label:"Action",width:100,resizeable:true,sortable:false, formatter:action},
						 {key:"firstname",label:"Firstname",width:100,resizeable:true,sortable:true, formatter:test},
						 {key:"lastname",label:"Lastname",width:80,resizeable:true,sortable:true},
						 {key:"middlename",label:"Middlename",width:80,resizeable:true,sortable:true}
						 ];
		var fields =	['id','firstname','lastname','middlename'];
		var height = 	'300px';
		var width = 	'590px';

		var controller = 'source/_json_encode_user_datatable?';		
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
}
  </script>


<br />
<br />

<strong>Include</strong><br /> 
Yui::loadDatatable();<br />
<br />
<br />
<strong>Usage:</strong><br />
<strong>Views</strong></p>
<p>
  
  <textarea name="textarea" id="textarea" cols="80" rows="5">
<div class="yui-skin-sam">
	<div id="exam_schedule_datatable"></div>
</div>
  <script>
  loadUserDatatable();
  </script>
  </textarea>
  <br />
<strong>Controller</strong></p>
<p>
<textarea name="textarea2" id="textarea2" cols="80" rows="18" wrap="off">
function _json_encode_user_datatable() {
    $search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
    
    $limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
    $order_by = ($_GET['sort'] != '') ? $_GET['sort'] . ' ' . $_GET['dir']  :  'id asc' ;
    
    $sql = "SELECT * FROM g_user ORDER BY ".$order_by. " " . $limit ;
    $data = Model::runSql($sql,true);
    
    $sql = "SELECT COUNT(*) as total FROM g_user";
    $count_total =  Model::runSql($sql,true);
    $total = count($data);
    $total_records =$count_total[0]['total'];
    
    header("Content-Type: application/json"); 
    echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
</textarea>
</p>
 <br />
<strong>Script</strong></p>
<p>
  <textarea name="textarea2" id="textarea2" cols="80" rows="20" wrap="off">
function loadUserDatatable()
{
	element_id = 'user_datatable_wrapper';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<center> <a href=\"javascript:delete("+ id +");\">Delete</a>  <a href=\"javascript:view("+ id +");\">Display</a>"; 
	
			};
			
	var test = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
			};						
			
		var columns = 	[
						 {key:"id",label:"Action",width:100,resizeable:true,sortable:false, formatter:action},
						 {key:"firstname",label:"Firstname",width:100,resizeable:true,sortable:true, formatter:test},
						 {key:"lastname",label:"Lastname",width:80,resizeable:true,sortable:true},
						 {key:"middlename",label:"Middlename",width:80,resizeable:true,sortable:true}
						 ];
		var fields =	['id','firstname','lastname','middlename'];
		var height = 	'300px';
		var width = 	'590px';

		var controller = 'source/_json_encode_user_datatable?';		
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
}  </textarea>
</p>
