<h2 class="field_title"><?php echo $title_skills; ?><a class="add_button" id="skill_add_button_wrapper" href="javascript:loadSkillAddForm();"><strong>+</strong><b>Add Skill</b></a></h2>
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
          <td><a href="javascript:void(0);" onclick="javascript:loadSkillEditForm('<?php echo $e->id; ?>');"><?php echo $e->skill; ?></a></td>
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