<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title"><?php echo $title_dependent; ?></h2>
<div id="dependent_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Name</th>
          <th width="150" align="left" valign="middle" scope="col">Relationship</th>
          <th width="109" align="left" valign="middle" scope="col">Birthdate</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($dependents as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top"><?php if($can_manage) { ?><a href="javascript:void(0);" onclick="javascript:loadDependentEditForm('<?php echo $e->id; ?>');"><?php echo $e->name; ?><?php } else {echo $e->name;} ?></a></td>
          <td align="left" valign="top"><?php echo $e->relationship; ?></td>
          <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->birthdate); ?></td>
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