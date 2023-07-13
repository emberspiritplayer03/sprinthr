<div id="detailscontainer" class="detailscontainer_blue">
	<div id="applicant_details">
        <div class="employee_form_summary" id="formwrap">
            <div class="inner_form" id="form_main">
                <div id="form_default">
                    <div class="col_1_4"><img class="applicant_exam_pp" src="<?php echo BASE_FOLDER;?>images/profile_noimage.gif" alt="Profile Photo"  /></div>
                    <div class="col_3_4">
                        <table>
                          <tr>
                            <td class="field_label">Applicant Name:</td>
                            <td class="bold blue"><?php echo $applicant->lastname . ', ' . $applicant->firstname; ?></td>
                          </tr>
                          <tr>
                            <td class="field_label">Examination:</td>
                            <td class="bold"><?php echo $examination->title; ?></td>
                          </tr>
                          <tr>
                            <td class="field_label">Passing Percentage:</td>
                            <td class="bold"><?php echo $examination->passing_percentage; ?>%</td>
                          </tr>
                          <tr>
                            <td class="field_label">Initial Score:</td>
                            <td class="bold"><?php echo $examination->result; ?>&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="field_label">Date Taken:</td>
                            <td class="bold"><?php echo Date::convertDateIntIntoDateString($examination->schedule_date); ?></td>
                          </tr>
                        </table>
                    </div>
                    <div class="clear"></div>
                </div><!-- #form_default -->
            </div><!-- #form_main -->        
        </div>
    </div><!-- #applicant_details -->
</div>
<div id="examination_wrapper">    
    <div id="exam_result_wrapper">
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
    </div>
</div>
<div id="form_main_examination">
	<div id="form_default" class="form_action_section">
    	<div align="center"><input type="button" class="blue_button" value="OK" onclick="javascript:loadExamViewDialog();" /></div>
    </div>
</div>
<div id="examination_summary_wrapper"></div>
<input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo Utilities::encrypt($applicant->id); ?>" />
<input type="hidden" id="hash" name="hash" value="<?php echo Utilities::createHash($applicant->id); ?>" />
<input type="hidden" id="applicant_status" name="applicant_status" value="<?php echo $applicant->status; ?>" />

