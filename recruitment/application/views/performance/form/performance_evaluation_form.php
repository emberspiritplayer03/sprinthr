<script>
$(document).ready(function() {

	$("#performance_evaluation_form").validationEngine({scroll:true});
});
	$('#performance_evaluation_form').ajaxForm({
		success:function(o) {
				
					dialogOkBox('Successfully Evaluated',{ok_url:"performance"});	
			
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	
	


</script>
<?php
//echo "<pre>";
//print_r($employee);
 ?>
<div id="dialog_performance_form"></div>
<table width="351" border="0" cellpadding="3" cellspacing="2">
  <tr>
    <td width="164"><strong>Peformance Title</strong></td>
    <td width="171"><?php echo $performance_title; ?>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Employee</strong></td>
    <td><?php echo $employee_name; ?></td>
  </tr>
  <tr>
    <td><strong>Reviewer</strong></td>
    <td><?php echo $reviewer_name; ?></td>
  </tr>
  <tr>
    <td><strong>Period From</strong></td>
    <td><?php echo $employee->period_from; ?></td>
  </tr>
  <tr>
    <td><strong>Period To</strong></td>
    <td><?php echo $employee->period_to; ?></td>
  </tr>
  <tr>
    <td><strong>Due Date</strong></td>
    <td><?php echo $employee->due_date; ?></td>
  </tr>
</table>
<blockquote>&nbsp;</blockquote>
<form id="performance_evaluation_form" name="performance_evaluation_form" method="post" action="<?php echo url('performance/_save_evaluation'); ?>">
  <input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="performance_id" value="<?php echo $performance_id; ?>">
<input type="hidden" name="employee_performance_id" value="<?php echo $employee_performance_id; ?>">
  <table width="950" border="0" cellpadding="4" cellspacing="2">
    <tr>
      <td align="left"><strong>Criteria</strong></td>
      <td align="center"><strong>Does  not Meet <br>
      Minimum Standards</strong></td>
      <td align="center"><strong>Needs  <br>
      Improvement</strong></td>
      <td align="center"><p><strong>Meets<br>
Expectations</strong></p></td>
      <td align="center"><strong>Exceeds  <br>
      Expectation</strong></td>
      <td align="center"><p><strong>Outstanding</strong></p></td>
      <td align="center"><strong>Comment</strong></td>
    </tr>
    <?php foreach($kpi as $key=>$value) {  ?>
    <tr>
      <td align="left">
      <input type="hidden" id="id_<?php echo $value->id; ?>" name="id_<?php echo $value->id; ?>" value="<?php echo $value->id; ?>">
      <input type="hidden" id="title_<?php echo $value->id; ?>" name="title_<?php echo $value->id; ?>" value="<?php echo $value->title; ?>">
      <input type="hidden" id="desc_<?php echo $value->id; ?>" name="desc_<?php echo $value->id; ?>" value="<?php echo $value->description; ?>">

	  <b><?php echo $value->title; ?></b><br><?php echo $value->description; ?></td>
      <td align="center"><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="1" class="validate[required]"></td>
      <td align="center"><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="2" class="validate[required]"></td>
      <td align="center"><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="3" class="validate[required]"></td>
      <td align="center"><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="4" class="validate[required]"></td>
      <td align="center"><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="5" class="validate[required]"></td>
      <td align="center"><textarea name="comment_<?php echo $value->id; ?>" id="comment_<?php echo $value->id; ?>" cols="45" rows="5"></textarea></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="7" align="center"><input class="blue_button" type="submit"  name="button" id="button" value="Done"></td>
    </tr>
  </table>
</form>
