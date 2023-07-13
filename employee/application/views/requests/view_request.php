<style>
.approver-name{padding:8px;background-color:#198cc9;color:#ffffff;margin-top:10px;}
.alert-box{margin-left:221px;width:30%;margin-bottom:19px;}
</style>
<script>
$(function(){
  $("#approve-disapprove-request").validationEngine({scroll:false});
  $(".btn-approve-disapprove").click(function(e){
  	e.preventDefault();
    var btnValue    = $(this).attr('value');
    eNotificationApproveDisapproveRequest(btnValue);
  });
});

</script>
<div class="approvers-request-details">
<form id="approve-disapprove-request" name="approve-disapprove-request" action="" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" name="reid" value="<?php echo $reid; ?>" />
<input type="hidden" name="aeid" value="<?php echo $aeid; ?>" />
<input type="hidden" name="greid" value="<?php echo $greid; ?>" />
<input type="hidden" name="request_type" value="<?php echo $request_type; ?>" />
<div id="form_main">     
	<?php 
		if($sub_file != ''){ 			
			include_once($sub_file);
		}
	?>
</div><!-- #form_main -->
</form>
</div>