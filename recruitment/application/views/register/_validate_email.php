<div style="margin-top:3px;">
<input type="hidden" id="eError" value="<?php echo $error; ?>" />
<?php if($error == 1){?>
	<br />	
	<div class="ui-state-highlight ui-corner-all message_box"><span class="label label-warning">Not Available</span></div>
	<script>
		$(document).ready(function(){
			$('#add_applicant_form input[type="submit"]').attr('disabled','disabled');
 		});
	</script>
	
<?php }else{ ?>
	<br />
	<div class="ui-state-highlight ui-corner-all message_box"><span class="label label-success">Available</span></div>
	<script>
		$(document).ready(function(){
			$('#add_applicant_form input[type="submit"]').removeAttr('disabled');
 		});
	</script>
<?php } ?>
</div>