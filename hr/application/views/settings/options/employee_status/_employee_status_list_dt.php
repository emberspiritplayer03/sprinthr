<script>
$(function() {
 	 $('.t_view_cutoff_periods').tipsy({gravity: 's'});	
	 $('#employee_status_list').dataTable( {	
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
<table id="employee_status_list" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"></th>     
        <th valign="top" width="10%" style="font-size:12px;">Employee Status</th>        
      </tr>
    </thead>
    <tbody> 
    	<?php foreach($e_status as $es){ ?>
    	<tr>
        	<td>
                <?php if( !in_array($es->getId(), $default_ids) ){ ?>
            	<a class="t_view_cutoff_periods" href="javascript:editEmployeeStatus('<?php echo Utilities::encrypt($es->getId()); ?>');" title="Edit"><i class="icon-pencil"></i></a>&nbsp;&nbsp;
                <a class="t_view_cutoff_periods" href="javascript:archiveEmployeeStatus('<?php echo Utilities::encrypt($es->getId()); ?>');" title="Archive"><i class="icon-trash"></i></a>
                <?php } ?>
            </td>
            <td><span style="color:#21729E;"><?php echo $es->getName(); ?></span></td>            
        </tr>  
        <?php } ?>
    </tbody>	
</table>
</div>
