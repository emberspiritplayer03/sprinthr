<h2 class="field_title"><?php echo $title_skills; ?>
<?php echo $btn_add_skill;?> 
</h2>
<div id="skill_edit_form_wrapper"></div>
<div id="skill_add_form_wrapper" style="display:none"><?php include 'form/skill_add.php'; ?></div>
<div id="skill_delete_wrapper"></div>
<div id="skill_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Skill</th>
          <th width="150" scope="col">Years Experience</th>
         
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($skills as $key=>$e) { ?>
        <tr>
          <td>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a href="javascript:void(0);" onclick="javascript:loadSkillEditForm('<?php echo $e->id; ?>');"><?php echo $e->skill; ?></a>
            <?php }else{ echo $e->skill; } ?>
          </td>
          <td><?php echo $e->years_experience; ?></td>

        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="3"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>