<?php if($project_site) { ?>
<h2 class="field_title">Project Site History</h2>
<div id="job_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
  <thead>
    <tr>
      <th width="117" align="left" valign="middle" scope="col">Project Site</th>
      <th width="109" align="left" valign="middle" scope="col">Start Date</th>
      <th width="109" align="left" valign="middle" scope="col">End Date</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  $ctr = 0;
   foreach($project_site as $key=>$e) { 

        $p = G_Project_Site_Finder::findById($e->getProjectId());
        if($p){
            $project_site_name = $p->getprojectname();
        }

    ?>
    <tr>
      <td align="left" valign="top"><?php echo $project_site_name; ?></td>
      <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->getStartDate()) ; ?></td>
      <td align="left" valign="top"><?php echo ($e->getEndDate()=='')? 'Present' : Date::convertDateIntIntoDateString($e->getEndDate()) ; ?></td>
    </tr>
   <?php 
   $ctr++;
   }

  if($ctr==0) { ?>
      <tr>
      <td colspan="4" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
    </tr> 
    <?php }  ?>
  </tbody>
</table>
</div>
<?php } ?>