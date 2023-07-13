<script>
$("#requirements_edit_form").validationEngine({scroll:false});
$('#requirements_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{height:240,width:390});
			$("#requirements_wrapper").html('');
			loadEmployeeSummary();
			var hash = window.location.hash;
			loadPage(hash);
			
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


<h2 class="field_title"><?php echo $title_requirements; ?>
<?php echo $btn_add_requirements; ?>
</h2>
<div id="requirements_edit_form_wrapper"></div>
<div id="requirements_add_form_wrapper" style="display:none">
<?php 
include 'form/requirements_add.php';
?>
</div>
<div id="requirements_delete_wrapper"></div>

<div id="requirements_table_wrapper">
<form id="requirements_edit_form" name="requirements_edit_form" method="post" action="<?php echo url('employee/_update_requirements'); ?>" >

<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<table width="100%" id="hor-minimalist-b" class="formtable">
     <thead>
        <tr>
          <th><input type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
          <th>Title</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($requirements as $key=>$e) { 
		   foreach($e as $key=>$val) {
	   ?>
       <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?> 
        <tr >
          <td style="padding-left:10px;" width="20" align="right"><input name="<?php echo $key; ?>" type="checkbox" id="checkbox" <?php echo $str = ($val=='on') ? 'checked="checked"': '' ; ?> /></td>
          <td onmouseout="javascript:hideDelete('<?php echo $key; ?>');" onmouseover="javascript:displayDelete('<?php echo $key; ?>');"><?php echo Tools::friendlyTitle($key); ?><!--<label class="delete_requirement_nav inline" id="<?php echo $key; ?>" >-->&nbsp;&nbsp;<a class="delete_link delete_link_option" href="javascript:loadRequirementsDeleteDialog('<?php echo $key; ?>');"><span class="delete"></span>Delete</a><!--</label>--></td>
        </tr>
       <?php }else { ?>
       <tr >
          <td style="padding-left:10px;" width="20" align="right"><input name="<?php echo $key; ?>" type="checkbox" id="checkbox" <?php echo $str = ($val=='on') ? 'checked="checked"': '' ; ?> /></td>
          <td><?php echo Tools::friendlyTitle($key); ?></td>
        </tr>
	   <?php } ?>
       <?php 
	   $ctr++;
		   }
		if($e) {
		 ?>
		<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
		<tr class="form_action_section">
          <td class="action_section" colspan="2"><div style="padding-left:30px;"><button class="blue_button" type="submit">Update</button></div></td>
        </tr>
        <?php } ?>
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