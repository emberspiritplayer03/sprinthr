<style>
.approver-name{padding:8px;background-color:#198cc9;color:#ffffff;margin-top:10px;}
</style>
<script>
$(function(){
  $("#approve-disapprove-request").validationEngine({scroll:false});
  $('#approve-disapprove-request').ajaxForm({
      success:function(o) {
          if (o.is_success) {
          	closeDialog('#' + DIALOG_CONTENT_HANDLER);
            $(".approvers-request-details").html("<div class='alert alert-success'>" + o.message + "</div>");        
          } else {              
            dialogOkBox(o.message,{});          
          }                   
      },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });
});

</script>
<div class="approvers-request-details">
<form id="approve-disapprove-request" name="approve-disapprove-request" action="<?php echo url('benchmark_bio/_email_approve_request'); ?>" method="post"> 
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