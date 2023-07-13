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
</script>


<h2 class="field_title"><?php echo $title_requirements; ?><a class="add_button" id="requirements_add_button_wrapper"  href="javascript:loadRequirementsAddForm();"><strong>+</strong><b>Add Requirements</b></a></h2>
<div id="requirements_edit_form_wrapper"></div>
<div id="requirements_add_form_wrapper" style="display:none">
<?php 
include 'form/requirements_add.php';
?>
</div>
<div id="requirements_delete_wrapper"></div>

<div id="requirements_table_wrapper">
<form id="requirements_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_requirements'); ?>" >

<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<table width="100%" id="hor-minimalist-b" class="formtable">
     <thead>
        <tr>
          <th><input type="checkbox" /></th>
          <th>Title</th>
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
          <td onmouseout="javascript:hideDelete('<?php echo $key; ?>');" onmouseover="javascript:displayDelete('<?php echo $key; ?>');"><?php echo Tools::friendlyTitle($key); ?><label class="delete_requirement_nav" id="<?php echo $key; ?>" > <a class="delete_link" href="javascript:loadRequirementsDeleteDialog('<?php echo $key; ?>');"><span class="delete"></span>delete</a></label></td>
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