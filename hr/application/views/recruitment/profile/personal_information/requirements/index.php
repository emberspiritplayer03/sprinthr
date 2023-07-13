<script>
$("#requirements_edit_form").validationEngine({scroll:false});
$('#requirements_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#requirements_wrapper").html('');
			loadPage("#requirements");
			loadApplicantSummary();
			loadPhoto();
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

function chkUnchk()
{
	var check_uncheck = document.requirements_edit_form.elements['check_uncheck'];
	if(check_uncheck.checked == 1) {		
		var status = 1; 
	} else { 		
		var status = 0;
	}
		
	var theForm = document.requirements_edit_form;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].id=='checkbox')
            theForm.elements[i].checked = status;
    }
}

</script>


<h2 class="field_title"><?php echo $title; ?>
<a id="requirements_add_button_wrapper" href="javascript:loadRequirementsAddForm();" class="add_button"><strong>+</strong><b>Add Requirement</b></a></h2>
<div id="requirements_edit_form_wrapper"></div>
<div id="requirements_add_form_wrapper" style="display:none">
<?php 
include 'form/requirements_add.php';
?>
</div>
<div id="requirements_delete_wrapper"></div>
<div id="requirements_table_wrapper">
<form id="requirements_edit_form" name="requirements_edit_form" method="post" action="<?php echo url('recruitment/_update_requirements'); ?>" >

<input type="hidden" name="applicant_id" value="<?php echo $applicant_id; ?>" />
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="20" scope="col"><input type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
          <th width="686" scope="col">Requirements</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($requirements as $key=>$e) { 
		   foreach($e as $key=>$val) {
	   ?>
        <tr >
          <td width="20" align="right"><input name="<?php echo $key; ?>" type="checkbox" id="checkbox" <?php echo $str = ($val=='on') ? 'checked="checked"': '' ; ?> /></td>
          <td onmouseout="javascript:hideDelete('<?php echo $key; ?>');" onmouseover="javascript:displayDelete('<?php echo $key; ?>');"><?php echo Tools::friendlyTitle($key); ?><label class="delete_requirement_nav" id="<?php echo $key; ?>" > <a class="delete_link" href="javascript:loadRequirementsDeleteDialog('<?php echo $key; ?>');"><span class="delete"></span>Delete</a></label></td>
        </tr>
       <?php 
	   $ctr++;
		   }
		if($e) {
		 ?>
		 
		<tr class="form_action_section">
          <td width="20" align="right">&nbsp;</td>
          <td class="action_section"><input class="blue_button" type="submit" value="Update" /></td>
        </tr>
		 <?php 
		}
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="2"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</form>
</div>

<script>
$(".delete_requirement_nav").hide();
</script>