<h2 class="field_title"><?php echo $title_supervisor; ?><a class="add_button" id="supervisor_add_button_wrapper" href="javascript:loadSupervisorAddForm();"><strong>+</strong><b>Add Supervisor / Subordinates</b></a></h2>
<div id="supervisor_edit_form_wrapper"></div>
<div id="supervisor_add_form_wrapper" style="display:none"><?php include 'form/supervisor_add.php'; ?></div>
<div id="supervisor_delete_wrapper"></div>
<div id="supervisor_table_wrapper">
    <div class="container_12">
        <div class="col_1_2">
        <div class="inner">
        <!--<h3 class="content_subtitle">Supervisor</h3>-->
        <table width="858" id="hor-minimalist-b"  border="0">
          <thead>
            <tr>
              <th width="117" scope="col">Supervisor Name</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($subordinate as $key=>$e) { ?>
            <tr>
              <td><a href="javascript:void(0);" onclick="javascript:loadSupervisorEditForm(<?php echo $e->id; ?>);">
              <?php
              $sub = G_Employee_Finder::findById($e->supervisor_id);
               echo $sub->firstname. " ". $sub->lastname; ?></a></td>
            </tr>
           <?php 
           $ctr++;
           }
    
          if($ctr==0) { ?>
              <tr>
              <td><center><i>No Record(s) Found</i></center></td>
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
              <th width="150" scope="col">Subordinates Name</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($supervisor as $key=>$e) { ?>
            <tr>         
              <td>
              <a href="javascript:void(0);" onclick="javascript:loadSubordinatesEditForm(<?php echo $e->id; ?>);">
              <?php 
               $subordinate = G_Employee_Finder::findById($e->employee_id);
               echo $subordinate->firstname. " ". $subordinate->lastname;
             ?></a></td>
            </tr>
           <?php 
           $ctr++;
           }
    
          if($ctr==0) { ?>
              <tr>
              <td colspan="2"><center><i>No Record(s) Found</i></center></td>
            </tr> 
            <?php }  ?>
          </tbody>
      </table>
        </div>
        </div>
        <div class="clear"></div>
    </div>
</div>