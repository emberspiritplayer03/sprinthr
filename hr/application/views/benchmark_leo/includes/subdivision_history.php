<h2 class="field_title">Subdivision History</h2>
<div id="subdivision_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Subdivision</th>
          <th width="150" align="left" valign="middle" scope="col">Start Date</th>
          <th width="109" align="left" valign="middle" scope="col">End Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($subdivision_history as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top">
         	<?php echo $e->name; ?>
          </td>
          <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->start_date) ; ?></td>
          <td align="left" valign="top"><?php echo ($e->end_date=='')? 'Present' : Date::convertDateIntIntoDateString($e->end_date) ; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="3" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>
