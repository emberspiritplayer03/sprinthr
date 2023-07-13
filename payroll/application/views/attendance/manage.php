<div id="employee_search_container">

<form method="get" action="<?php echo $action;?>">
<input type="hidden" name="from" value="<?php echo $start_date;?>" />
<input type="hidden" name="to" value="<?php echo $end_date;?>" />
<!--<form id="search_employee" method="get" action="<?php echo $action;?>">-->
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" value="<?php echo $query;?>" />
    <button id="create_schedule_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search Employee</button>
    </div>
</form>
</div><!-- #employee_search_container -->
<div class="detailscontainer_blue details_highlights" id="detailscontainer">
    <div class="earnings_period_selected">
        <div class="overtime_title_period">
        <div class="actions_holder float-right" style="position:relative; top:6px;"><a class="gray_button" href="javascript:void(0)" onclick="javascript:updateAttendance('<?php echo $start_date;?>', '<?php echo $end_date;?>')"><i class="icon-repeat vertical-middle"></i> Update Attendance</a></div>
        <?php if (strtotime($start_date) && strtotime($end_date)):?>
        	<h2 class="no-padding no-margin">Period: <span class="blue"><?php echo date('M j', strtotime($start_date));?></span> - <span class="blue"><?php echo date('M j, Y', strtotime($end_date));?></span></h2>
        <?php endif;?>      
        </div>        
    </div>
</div>
<!--<div><a class="gray_button" href="javascript:void(0)" onclick="javascript:importTimesheet()">Import Timesheet</a><br /><br /></div>-->
<div id="employee_list">
  <table class="formtable" id="box-table-a" style="margin:0px">
    <thead>
    	<tr>
        	<th width="150"><strong>Employee #</strong></th>
            <th><strong>Employee Name</strong></th>
            <!--<th>Late Hours</th>-->
           <!-- <th>Undertime Hours</th>-->
            <!--<th>OT Hours</th>-->
           <!-- <th># of Leaves</th>-->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($employees as $e):?>
        <tr>
          <td width="150"><?php echo $e->getEmployeeCode();?></td>
          <td><a href="<?php echo url('attendance/show_attendance?employee_id='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash() .'&from='. $start_date .'&to='. $end_date);?>"><?php echo $e->getLastname();?>, <?php echo $e->getFirstname();?></a></td>
<!--          <td>3</td>
          <td>5</td>
          <td>8</td>
          <td>3</td>-->
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