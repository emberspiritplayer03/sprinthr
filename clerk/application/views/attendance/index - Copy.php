<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
<!--<form id="search_employee" method="get" action="<?php echo $action;?>">-->
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" />
    <button id="create_schedule_submit" class="blue_button" type="submit">Search Employee</button>
    </div>
</form>
</div><!-- #employee_search_container -->
<div><a class="gray_button" href="javascript:void(0)" onclick="javascript:importTimesheet()">Import Timesheet</a><br /><br /></div>
<div id="employee_list">
  <table class="formtable" id="box-table-a" style="margin:0px">
    <thead>
    	<tr>
        	<th width="150"><strong>Employee #</strong></th>
            <th><strong>Employee Name</strong></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($employees as $e):?>
        <tr>
          <td width="150"><?php echo $e->getEmployeeCode();?></td>
          <td><a href="<?php echo url('attendance/show_attendance?employee_id='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash());?>"><?php echo $e->getLastname();?>, <?php echo $e->getFirstname();?></a></td>
        </tr>
      <?php endforeach;?> 
    </tbody>
  </table>
</div>

<script language="javascript">
$(document).ready(function() {
	$('#search_employee').ajaxForm({
		success:function(o) {	
			$('#employee_list').html(o);
		},
		beforeSubmit:function() {
			$('#employee_list').html(loading_image + ' Loading... ');
		}
	});		
});
//$('.tooltip').tipsy({gravity: 's'});
//$('.info').tipsy({gravity: 's'});
</script>