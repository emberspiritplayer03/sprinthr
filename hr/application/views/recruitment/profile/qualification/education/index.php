<h2 class="field_title"><?php echo $title_education; ?><a class="add_button" id="education_add_button_wrapper" href="javascript:loadEducationAddForm();"><strong>+</strong><b>Add Education</b></a></h2>
<div id="education_edit_form_wrapper"></div>
<div id="education_add_form_wrapper" style="display:none"><?php include 'form/education_add.php'; ?></div>
<div id="education_delete_wrapper"></div>
<div id="education_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Institute</th>
          <th width="150" scope="col">Course</th>
          <th width="109" scope="col">Year</th>
          <th width="109" scope="col">GPA Score</th>
          <th width="109" scope="col">Attainment</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($education as $key=>$e) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadEducationEditForm('<?php echo $e->id; ?>');"><?php echo $e->institute; ?></a></td>
          <td><?php echo $e->course; ?></td>
          <td><?php echo $e->year; ?></td>
          <td><?php echo $e->gpa_score; ?></td>
          <td><?php echo $e->attainment; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="5"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>