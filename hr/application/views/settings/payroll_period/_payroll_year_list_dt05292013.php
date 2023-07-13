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
    	<a class="btn btn-small" href="javascript:generatePayrollPeriod('<?php echo $current_year; ?>');"><i class="icon-plus"></i><b>Generate Payroll Period</b></a>
    </div>
    <div class="clear"></div>
</div>
<div class="table-container">
<table id="payroll_year_list" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
        <th valign="top" width="10%" style="font-size:12px;">Payroll Year</th>        
      </tr>
    </thead>
    <tbody> 
    	<?php for($x=$current_year;$x >= $start_year; $x--){ ?>
    	<tr>
        	<td><a class="t_view_cutoff_periods" href="javascript:load_payroll_period_list_dt('<?php echo $x; ?>');" title="View Cutoff Periods"><i class="icon-calendar"></i></a>&nbsp;&nbsp;<a class="t_view_cutoff_periods" href="javascript:lockAllPayrollPeriodBySelectedYear('<?php echo $x; ?>');" title="Lock All Cutoff Periods"><i class="icon-lock"></i></a></td>
            <td><b><?php echo $x; ?></b></td>            
        </tr>  
        <?php } ?>
    </tbody>	
</table>
</div>
