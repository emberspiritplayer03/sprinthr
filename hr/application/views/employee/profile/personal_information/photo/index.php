<script>
$('#photo_upload').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Uploaded',{});			
			
			closeDialog('#photo_wrapper');
			loadPhoto();	
		}else {
			dialogOkBox(o,{});	
		}		
	}
});
</script>
<div id="form_main" class="inner_form popup_form">
<form id="photo_upload" name="photo_form" method="post" action="<?php echo url('employee/_upload_photo'); ?>" enctype="multipart/form-data">
<div id="form_default" align="center">
    <img src="<?php echo $filename;  ?>?<?php echo $filemtime; ?>" width="140" border="1" align="middle"  /><br><br>
    <input type="hidden" name="employee_id" value="<?php echo $employee->getId(); ?>">
      <label for="fileField"></label>
    <input type="file" name="fileField" id="fileField">
</div>
<div id="form_default" class="form_action_section">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><div align="center"><input class="curve blue_button" type="submit" name="button" id="button" value="Upload">&nbsp;<a href="javascript:void(0);" onclick="javascript:closePhotoDialog();">Cancel</a></div></td>
      </tr>
    </table>
</div>
</form>
</div>