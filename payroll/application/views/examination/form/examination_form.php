<script>
$(document).ready(function() {

	$("#applicant_examination_form").validationEngine({scroll:true});
});
	$('#applicant_examination_form').ajaxForm({
		success:function(o) {
				
					dialogOkBox('Wait for Checking',{ok_url:"examination"});	
			
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	
	


</script>
Direction:
<div id="dialog_examination_form"></div>
<form id="applicant_examination_form" name="applicant_examination_form" method="post" action="<?php echo url('examination/_finish_answering_examination'); ?>">
<input type="hidden" name="applicant_examination_id" value="<?php echo $applicant_examination_id; ?>">
<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
  <table width="656" border="0" cellpadding="4" cellspacing="2">
    <tr>
      <td align="left">&nbsp;
      <?php 
	  $numbering=0;
		foreach($q as $key=>$question) {
		$numbering++;
			if($question->type=='choices') {
				include 'include/choices.php';
			}elseif($question->type=='essay') {
				include 'include/essay.php';
			}elseif($question->type=='blank') {
				include 'include/blank.php';
			}
		}
		?>
      </td>
    </tr>
    <tr>
      <td align="center"><input class="blue_button" type="submit"  name="button" id="button" value="Done"></td>
    </tr>
  </table>
</form>
