<h2 class="field_title"><?php echo $title_dependent; ?>
<?php echo $btn_add_dependent; ?>
</h2>
<div id="dependent_edit_form_wrapper"></div>
<div id="dependent_add_form_wrapper" style="display:none"><?php include 'form/dependent_add.php'; ?></div>
<div id="dependent_delete_wrapper"></div>
<div id="dependent_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Name</th>
          <th width="150" scope="col">Relationship</th>
          <th width="109" scope="col">Birthdate</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	   $ctr = 0;    
	   foreach($dependents as $key=>$e) { ?>
        <tr>
          <td><?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?><a href="javascript:void(0);" onclick="javascript:loadDependentEditForm('<?php echo $e->id; ?>');"><?php echo $e->name; ?><?php } else {echo $e->name;} ?></a></td>
          <td><?php echo $e->relationship; ?></td>
          <td><?php 
                if( $e->birthdate != '0000-00-00' ){
                   echo Date::convertDateIntIntoDateString($e->getBirthdate()); 
                }
            ?>
          </td>
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