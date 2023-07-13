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
<div class="table-container">
<table width="100%" class="formtable manydetails">  
  <thead>
  <tr>
    <th>Select Payroll Year</th>    
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  </thead>  
  <?php foreach($data as $d){ ?>
  <tr>
  	<td><b><?php echo $d->getYearTag(); ?></b></td>
  	<td><a title="Edit Attendance" href="<?php echo url("attendance?year=" . $d->getYearTag()); ?>" class="link_option edit"><i class="icon-calendar"></i> View Cutoff Periods</a></td> 
    <td></td>   
    <td></td>       
  </tr>  
  <?php } ?>
</table>
</div>
