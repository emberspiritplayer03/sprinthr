<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Supervisor / Subordinates</h2>
<div id="supervisor_table_wrapper">
    <div class="container_12">
        <div class="col_1_2">
        <div class="inner">
        <!--<h3 class="content_subtitle">Supervisor</h3>-->
        <table width="858" id="hor-minimalist-b"  border="0">
          <thead>
            <tr>
              <th width="117" align="left" valign="top" scope="col">Supervisor Name</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($subordinate as $key=>$e) { ?>
           <?php $sub = G_Employee_Finder::findById($e->supervisor_id); ?>
            <tr>
              <td align="left" valign="middle">
              	<?php if($can_manage) { ?>
              	<a href="javascript:void(0);" onclick="javascript:loadSupervisorEditForm(<?php echo $e->id; ?>);"> <?php echo $sub->firstname. " ". $sub->lastname; ?></a>
				<?php } else { echo $sub->firstname. " ". $sub->lastname; } ?>
              </td>
            </tr>
           <?php 
           $ctr++;
           }
    
          if($ctr==0) { ?>
              <tr>
              <td align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
            </tr> 
            <?php }  ?>
          </tbody>
        </table>
        </div>
        </div>
        <div class="col_1_2">
        <div class="inner">
        <!--<h3 class="content_subtitle">Subordinates</h3>-->
        <table width="858" id="hor-minimalist-b"  border="0">
          <thead>
            <tr>
              <th width="150" align="left" valign="middle" scope="col">Subordinates Name</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($supervisor as $key=>$e) { ?>
           <?php $subordinate = G_Employee_Finder::findById($e->employee_id); ?>
            <tr>         
              <td align="left" valign="top">
              	<?php if($can_manage) { ?>
              		<a href="javascript:void(0);" onclick="javascript:loadSubordinatesEditForm(<?php echo $e->id; ?>);"><?php echo $subordinate->firstname. " ". $subordinate->lastname; ?></a>
                <?php } else {echo $subordinate->firstname. " ". $subordinate->lastname;} ?>
              </td>
            </tr>
           <?php 
           $ctr++;
           }
    
          if($ctr==0) { ?>
              <tr>
              <td colspan="2" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
            </tr> 
            <?php }  ?>
          </tbody>
      </table>
        </div>
        </div>
        <div class="clear"></div>
    </div>
</div>