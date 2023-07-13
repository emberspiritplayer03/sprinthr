
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
<div id="employee_search_container">
    <div id="formwrap" class="employee_form_summary">
        <div id="form_main" class="inner_form wider">
            <div id="form_default">
                <h3 class="section_title">Employee Details</h3>
                <div class="float-left" style="width:50%">
                <table width="100%">
                  <tr>
                    <td class="field_label">Examination:</td>
                    <td><strong class="blue"><?php echo $examination->title; ?></strong></td>
                  </tr>
                  <tr>
                    <td class="field_label">Applicant Name:</td>
                    <td><strong><?php echo $applicant->lastname . ', ' . $applicant->firstname; ?></strong></td>
                  </tr>                                
                </table>
                </div>
                <div class="float-right" style="width:50%">
                <table width="100%">
                	<tr>
                        <td class="field_label">Passing Percentage:</td>
                        <td><strong><?php echo $examination->passing_percentage; ?>%</strong></td>
                      </tr>
                      <tr>
                        <td class="field_label">Initial Score:</td>
                        <td><?php echo $examination->result; ?>&nbsp;</td>
                      </tr>
                </table>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
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
<div align="center"><button class="blue_button" type="submit"  name="button" id="button"><i class="icon-ok icon-white"></i> Finished Checking</button></div>

</form>
