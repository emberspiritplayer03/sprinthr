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
//print_r($kpi);
 ?>
<div id="dialog_performance_form"></div>
<div id="dropholder"><a class="dropbutton" onclick="javascript:loadGoto();" href="#">Goto</a>
<div id="dropcontent" class="dropcontent" >
<a href="#">Profile</a><br />
<a href="#">Performance </a></div>
</div>

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
  <table width="691" border="0" cellpadding="4" cellspacing="4">
    <tr>
      <td width="343" align="left"><strong>Criteria</strong></td>
      <td width="175" align="left"><strong>Rating(s)</strong></td>
      <td width="141" align="left"><strong>Comment</strong></td>
    </tr>
    <?php 
	$x=1;
	
	$total = count($kpi);
	while($x<=$total){  ?>
    <tr>
      <td align="left">
      <input type="hidden" id="id_<?php echo $value->id; ?>" name="id_<?php echo $value->id; ?>" value="<?php echo $value->id; ?>">
      <input type="hidden" id="title_<?php echo $value->id; ?>" name="title_<?php echo $value->id; ?>" value="<?php echo $value->title; ?>">
      <input type="hidden" id="desc_<?php echo $value->id; ?>" name="desc_<?php echo $value->id; ?>" value="<?php echo $value->description; ?>">

	  <b><?php echo $kpi['kpi_'.$x]['title']; ?></b><br><?php echo $kpi['kpi_'.$x]['desc']; ?></td>
      <td align="left"><?php echo $kpi['kpi_'.$x]['result']; ?></td>
      <td align="left"><?php echo (is_array($kpi['kpi_'.$x]['comment'])==1)? '&nbsp;': $kpi['kpi_'.$x]['comment'] ; ?></td>
    </tr>
    <?php 
	$x++;
	} ?>
    <tr>
      <td colspan="3" align="center"><input class="blue_button" type="submit"  name="button" id="button" value="Done"></td>
    </tr>
  </table>
</form>
