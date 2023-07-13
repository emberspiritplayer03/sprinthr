<style>
.btn-show-dept-section-schedule{margin:5px !important;}
</style>

<script>
$(function(){
  $(".btn-show-dept-section-schedule").click(function(){
     var eid = $(this).attr("data-index");
     //alert(base_url + 'schedule/show_department_schedule?eid=' + eid);
     location.href = base_url + 'schedule/show_department_schedule?eid=' + eid; 
  });
});
</script>
<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" value="<?php echo $query;?>" placeholder="Type employee, department or section name" />
    <button id="employee_search_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search</button>
    </div>
</form>
</div><!-- #employee_search_container -->

<div id="employee_list">
  <div class="pull-right"><?php echo $btn_schedule_department_section; ?></div>
  <div class="clear"></div>
  <table class="formtable" id="box-table-a" style="margin:0px">
    <thead>
    	<tr>
        	<th width="50"><strong>Employee ID</strong></th>
            <th width="120"><strong>Employee Name</strong></th>
            <th><strong>Action</strong></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($employees as $e):?>
        <tr>
          <td width="150"><?php echo $e->getEmployeeCode();?></td>
          <td class="bold"><?php echo $e->getLastname();?>, <?php echo $e->getFirstname();?></td>
          <td>
            <div><a class="link_option" href="<?php echo url('schedule/show_employee_schedule?eid='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash());?>" title="Show Schedules">Show Schedules</a></div>
          </td>          
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>

