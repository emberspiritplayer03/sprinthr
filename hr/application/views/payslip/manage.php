<style>
#cutoff_period { margin-bottom: 5px; }
</style>

<script>
$(function(){
  $('.cutoff-selector-btn').click(function(){
    $('.cutoff-period-nav').hide();
    $('.cutoff-period-selector').show();
  });

  $('.back-to-cutoff-period-nav').click(function(){
    $('.cutoff-period-nav').show();
    $('.cutoff-period-selector').hide();
  });

  $("#payslip-year-selector").change(function(){    
     changePayPeriodByYear(this.value,'payslip-pay-period-container');
  });
  changePayPeriodByYear($("#payslip-report-year-selector").val(),'payslip-pay-period-container');

  $('.jump-to-cutoff').click(function(){
    var cutoff_period = $('#cutoff_period').val();
    var arr_cp = cutoff_period.split("/");
    location.href = base_url + 'payslip/manage?from='+arr_cp[0]+'&to='+arr_cp[1];
  });

});
</script>
<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
<input type="hidden" name="from" value="<?php echo $start_date;?>" />
<input type="hidden" name="to" value="<?php echo $end_date;?>" />
<input type="hidden" name="hpid" value="<?php echo $_GET['hpid'];?>" />
<input type="hidden" name="frequency" value="<?php echo $_GET['frequency'];?>" />
<!--<form id="search_employee" method="get" action="<?php echo $action;?>">-->
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" value="<?php echo $query;?>" />
    <button id="create_schedule_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search Employee</button><!--&nbsp;&nbsp;<label class="checkbox inline">
    	<input type="checkbox" <?php echo $checked; ?> id="s_exact" name="s_exact" />Exact match to entered query
    </label>-->
    </div>
</form>
</div><!-- #employee_search_container -->
<div class="detailscontainer_blue details_highlights" id="detailscontainer" style="min-height: 65px;">
    <div class="earnings_period_selected cutoff-period-nav">
        <div class="overtime_title_period">
        <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
            <!-- <div class="actions_holder float-right" style="position:relative; top:6px;"><a class="gray_button" href="javascript:void(0)" onclick="javascript:updateAttendance('<?php echo $start_date;?>', '<?php echo $end_date;?>')"><i class="icon-repeat vertical-middle"></i> Update Attendance</a></div>-->
        <?php } ?>
        <?php if (strtotime($start_date) && strtotime($end_date)):?>
            <h2 class="no-padding no-margin">
            	Period: <span class="blue"><?php echo date('M j', strtotime($start_date));?></span> - <span class="blue"><?php echo date('M j, Y', strtotime($end_date));?></span>

            	<!--<div class="pull-right">
						<a class="blue_button" href="javascript:void(0);" onclick="javascript:filterTimeSheetBreakDown('<?php echo $start_date; ?>','<?php echo $end_date; ?>');"><i class="icon-search icon-white"></i> Filter Timesheet Breakdown</a>            
            	</div>-->
            </h2>
            <div class="earnings_period_selected">
                <div class="overtime_title_period"><?php echo $period_selected; ?>
                    [ Go to:
                    <?php if ($previous_cutoff_link != ''):?>
                        <a href="<?php echo $previous_cutoff_link;?>">Previous Cutoff</a>
                    <?php else:?>
                        Previous Cutoff
                    <?php endif;?>
                    |
                    <?php if ($next_cutoff_link != ''):?>
                        <a href="<?php echo $next_cutoff_link;?>">Next Cutoff</a>
                    <?php else:?>
                        Next Cutoff
                    <?php endif;?>
                    | <a class="cutoff-selector-btn" href="javascript:void(0);">Select Cutoff Period </a>
                    ]</div>
            </div>
        <?php endif;?>        
        </div>       
    </div>
    <div class="cutoff-period-selector" style="display: none;">
        <h2 class="no-padding no-margin" style="display: inline;"> 
          Period: 
        </h2>
        <select id="payslip-year-selector" style="margin-bottom: 5px;">
          <?php $start_year = 2015; ?>
          <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
            <option <?php echo (date("Y") == $start ? 'selected="selected"' : ''); ?> value="<?php echo $start; ?>"><?php echo $start; ?></option>
          <?php } ?>
        </select>
        <div class="payslip-pay-period-container" style="display:inline-block;"></div>
        <a class="btn jump-to-cutoff" style="margin-bottom: 5px; height: 22px; line-height: 23px;">Go</a>
        <div>[ <a class="back-to-cutoff-period-nav" href="javascript:void(0);">Back</a> ]</div>
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
          <td>
              <a class="dropbutton" title="Edit Payslip" href="<?php echo url('payslip/show_payslip?employee_id='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash() .'&from='. $from .'&to='. $to .'&frequency='. $frequency);?>"><?php echo $e->getLastname();?>  <?php echo $e->extension_name;?> , <?php echo $e->getFirstname();?> <?php echo $e->middlename[0]; ?>. </a>
              <!--<a href="<?php echo url('payslip/show_payslip?employee_id='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash() .'&from='. $start_date .'&to='. $end_date);?>"><?php echo $e->getLastname();?>  <?php echo $e->extension_name;?> , <?php echo $e->getFirstname();?> <?php echo $e->middlename[0]; ?>. </a>-->
          </td>
<!--          <td>3</td>
          <td>5</td>
          <td>8</td>
          <td>3</td>-->
        </tr>
      <?php endforeach;?> 
    </tbody>
  </table>
</div>

<br>
<div style="text-align: center"><?php echo $pager_links;?></div>

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