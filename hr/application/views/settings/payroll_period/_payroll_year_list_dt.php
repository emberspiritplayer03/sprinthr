<script>
$(function() {
 	 $('.t_view_cutoff_periods').tipsy({gravity: 's'});	
	 $('#payroll_year_list').dataTable( {	
	    "aoColumns": [
				{ "bSortable": false,sWidth: '3%'},					
				{sWidth: '90%',sClass:'dt_small_font'},											
	    ],  
	   "bJQueryUI": true,
	   "sPaginationType": "full_numbers"
	});
});
</script>
<div class="break-bottom inner_top_option">        
    <div class="pull-right">    	
    	<a class="btn btn-small" href="javascript:generatePayrollPeriod('<?php echo $current_year; ?>');"><i class="icon-plus"></i><b>Generate Current Cutoff Period</b></a>
    </div>
    <div class="clear"></div>
</div>
<div class="table-container">
<table width="100%" class="formtable manydetails">  
  <thead>
  <tr>
    <th>Payroll Year</th>    
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  </thead>  
  <?php foreach($data as $d){ ?>
  <tr>
  	<td><b><?php echo $d->getYearTag(); ?></b></td>
  	<td><a title="Edit Attendance" href="javascript:load_payroll_period_list_dt('<?php echo $d->getYearTag(); ?>');" class="link_option edit"><i class="icon-calendar"></i> View Cutoff Periods</a></td> 
    <td><a title="Edit Attendance" href="javascript:lockAllPayrollPeriodBySelectedYear('<?php echo $d->getYearTag(); ?>');" class="link_option edit"><i class="icon-lock"></i> Lock All Cutoff Periods</a></td>   
    <td><a title="Edit Attendance" href="javascript:addPayrollPeriod('<?php echo $d->getYearTag(); ?>');" class="link_option edit"><i class="icon-plus"></i> Add Cutoff Period</a></td>       
  </tr>  
  <?php } ?>
</table>
</div>
