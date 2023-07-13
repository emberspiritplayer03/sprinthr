<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
legend{
	-moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: -moz-use-text-color -moz-use-text-color #E5E5E5;
    border-image: none;
    border-style: none none solid;
    border-width: 0 0 1px;
    color: #333333;
    display: block;
    font-size: 21px;
    line-height: 40px;
    margin-bottom: 20px;
    padding: 0;
    width: 100%;
}
	
</style>

<script>
$(document).ready(function() {		
	$('#policySettings').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);					
				$("#error_container").html(o.message);																
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);										
				$("#error_container").html(o.message);
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Updating...');
		}
	});		
});

</script>

<div id="error_container"></div>
<form class="form-inline" id="policySettings" name="policySettings" method="post" action="<?php echo url('settings/update_policy'); ?>">
	<input type="hidden" name="policy[]" value="No" />
	<fieldset>
	<legend>Modules</legend>
	<div class="alert alert-block alert-error">
    	Note : <b>Updating Policy Module.</b>
   </div>
	<?php foreach($policy as $p_content): ?>
    <input type="checkbox" name="policy[<?php echo $p_content->getId(); ?>]" <?php echo $p_content->getIsActive() == 'Yes' ? 'checked="checked"' : ''; ?>  value="Yes" /> <?php echo $p_content->getPolicy(); ?>
	 <br />	
	<?php endforeach; ?>
   <br /><br />
	<button class="btn btn-primary" type="submit">Update</button>
	</fieldset>
</form>