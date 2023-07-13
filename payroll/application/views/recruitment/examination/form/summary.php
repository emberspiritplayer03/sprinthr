
<script>
$(document).ready(function() {

	$("#exam_summary_checking_form").validationEngine({scroll:true});
});
	$('#exam_summary_checking_form').ajaxForm({
		success:function(o) {
				
					dialogOkBox('Successfully Checked',{ok_url:'recruitment/examination'});	
			
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	
	


</script>
<table width="100%" border="0" cellpadding="3" cellspacing="2">
  <tr>
    <td width="14%">Applicant Name:</td>
    <td width="86%"><?php echo $applicant->lastname . ', ' . $applicant->firstname; ?></td>
  </tr>
  <tr>
    <td>Examination:</td>
    <td><?php echo $examination->title; ?></td>
  </tr>
  <tr>
    <td>Passing Percentage:</td>
    <td><?php echo $examination->passing_percentage; ?>%</td>
  </tr>
  <tr>
    <td>Initial Score:</td>
    <td><?php echo $examination->result; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<div id="dialog_examination_form"></div>
<form id="exam_summary_checking_form" name="exam_summary_checking_form" method="post" action="<?php echo url('recruitment/_examination_checking'); ?>">
<input type="hidden" name="applicant_examination_id" value="<?php echo $applicant_examination_id; ?>" /> 
<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>" /> 

  <?php 
$numbering=0;

while($x < $total_questions)  {
	$numbering++;

		$value['question_'.$numbering];
	if($arr_xml['question_'.$numbering]['type']=='choices') {
		include 'include/choices.php';
	}elseif($arr_xml['question_'.$numbering]['type']=='essay') {
		include 'include/essay.php';
	}elseif($arr_xml['question_'.$numbering]['type']=='blank') {
		include 'include/blank.php';
	}
	$x++;
	?>

<?php 
}
?>
     <input class="blue_button" type="submit"  name="button" id="button" value="Finished Checking">

</form>
