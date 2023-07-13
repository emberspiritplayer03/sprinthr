<h2><?php echo $title; ?></h2>
<script>
$("#leave_birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#leave_date_from").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#leave_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
	}
});	

$("#leave_date_to").datepicker({	
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
	
	}
});	

$(function(){
    $("#frm-report-loan").validationEngine({scroll:false}); 
});
</script>

<?php
  $loan_type_group = array();
  foreach($loan_types as $ltkey => $ltd) {
    if( ($ltd->getLoanType() == 'Pagibig Loan') || ($ltd->getLoanType() == 'Pagibig Calamity Loan') || ($ltd->getLoanType() == 'Pagibig Salary Loan') ) {
      $loan_type_group['Pagibig Loan'][] = $ltd;
    }elseif( ($ltd->getLoanType() == 'SSS Loan') || ($ltd->getLoanType() == 'SSS Calamity Loan') || ($ltd->getLoanType() == 'SSS Salary Loan') ) {
      $loan_type_group['SSS Loan'][] = $ltd;
    } else {
      $loan_type_group['Others'][] = $ltd;
    }
  }
?>
<div id="form_main" class="employee_form">
<form id="frm-report-loan" name="form1" method="post" action="<?php echo url('reports/download_loan_data'); ?>">
    
     <!--
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td><input type="text" id="leave_date_from" name="date_from" class="validate[required]" /></td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td><input type="text" id="leave_date_to" name="date_to" class="validate[required]" /></td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    -->

    
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Report Type:</td>
                <td>

                    <select class="select_option" name="loan_report_type" id="loan_report_type" onChange="javascript:checkReportType();">
                        <option value="default">Default</option>
                        <option value="semi_month_loan_reg">Semi-Monthly Loan Register</option>
                        <option value="monthly_loan_reg">Monthly Loan Register</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td class="field_label"><div class="year_label" id="year_label" style="">Year:</div></td>
                <td>
                    <select class="select_option" name="year" id="year" style="">
                        <option id="all_value" value="<?php echo "All"; ?>"><?php echo "All"; ?></option>
                        <option value="<?php echo date("Y") - 1; ?>"><?php echo date("Y") - 1; ?></option>
                        <option selected value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                    </select>                    
                </td>
            </tr>
            <tr>
                <td class="field_label"><div class="month_label" id="month_label" style="">Month:</div></td>
                <td>
                    <select class="select_option" name="month" id="month" style="">
                        <?php $y = date("Y"); ?>
                        <option id="all_value2" value="All"><?php echo "All"; ?></option>
                        <?php for ($m = 1; $m <= 12; $m++) { ?>
                                    <option <?php echo $m == 1 ? 'selected' : ''; ?> value="<?php echo $m; ?>"><?php echo date('F', strtotime($y . '-' .$m . '-14'));?></option>
                        <?php } ?>
                    </select>                    
                </td>
            </tr> 
            <tr>
                <td class="field_label"><div class="payroll_period_label" id="payroll_period_label" style="display:none;">Payroll Period:</div></td>
                <td>
                    <select class="select_option" name="payroll_period" id="payroll_period" style="display:none;">
                        <?php foreach ($cutoff_periods as $c):?>
                                <option value="<?php echo $c->getStartDate();?>/<?php echo $c->getEndDate();?>"><?php echo $c->getYearTag();?> - <?php echo $c->getMonth();?> - <?php echo $c->getCutoffCharacter();?></option>
                        <?php endforeach;?>
                    </select>                    
                </td>
            </tr>                        
        </table>
    </div>
    <div class="form_separator"></div>

    
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Search by:</td>
                <td>
                    <select class="select_option" name="search_field" id="leave_search_field" onChange="javascript:checkIfAllLeave();">
                        <option value="all">All</option>
                        <option value="firstname">Firstname</option>
                        <option value="lastname">Lastname</option>
                        <option value="employee_code">Employee Code</option>
                        <!--
                        <option value="birthdate">Birthdate</option>
                        <option value="marital_status">Marital Status</option>
                        -->
                    </select>                         
                    <input style="display:none;margin-top:5px;" type="text" name="search" id="leave_search" />
                    <input type="text" style="display:none;margin-top:5px;" name="birthdate" id="leave_birthdate" />
                </td>
            </tr> 
            <!--
            <tr>
            	<td class="field_label">Department:</td>
                <td><label for="department_applied"></label>
                <select class="select_option" name="department_applied" id="department_applied" >
                <option value="all">All Department</option>
                <?php //foreach($departments as $d) { ?>
                <option value="<?php //echo $d->getId(); ?>"><?php //echo $d->getTitle(); ?></option>
                <?php //} ?>
                </select></td>
            </tr>  
            -->
            <tr>
                <td class="field_label">Loan Type:</td>
                <td>
                    <select class="select_option" name="loan_type" id="loan_type">
                        <option value="all" >All</option>
                            <?php 
                            foreach($loan_type_group as $ltg_key => $ltg){
                            ?>
                            <?php if($ltg_key == 'Others') { ?>
                                    <?php foreach($ltg as $lt) { ?>
                                            <option value="<?php echo $lt->getId(); ?>"><?php echo $lt->getLoanType(); ?></option>
                                    <?php } ?>
                            <?php }elseif($ltg_key == 'Pagibig Loan') { ?>

                                    <optgroup label="<?php echo $ltg_key; ?>">
                                      <?php foreach($ltg as $lt) { ?>
                                              <?php if($lt->getLoanType() != 'Pagibig Loan') { ?>
                                                <option value="<?php echo $lt->getId(); ?>"><?php echo str_replace('Pagibig', '', $lt->getLoanType()); ?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $lt->getId(); ?>"><?php echo $lt->getLoanType(); ?></option>
                                              <?php } ?>
                                      <?php } ?>
                                    </optgroup>

                            <?php }elseif($ltg_key == 'SSS Loan') { ?>

                                    <optgroup label="<?php echo $ltg_key; ?>">
                                      <?php foreach($ltg as $lt) { ?>
                                              <?php if($lt->getLoanType() != 'SSS Loan') { ?>
                                                <option value="<?php echo $lt->getId(); ?>"><?php echo str_replace('SSS', '', $lt->getLoanType()); ?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $lt->getId(); ?>"><?php echo $lt->getLoanType(); ?></option>
                                              <?php } ?>
                                      <?php } ?>
                                    </optgroup>

                            <?php } ?>
                                
                            <?php } ?>                         

                    </select>              
            </tr>

            <tr>
                <td class="field_label">Status:</td>
                <td>
                    <select class="select_option" name="status" id="status">
                        <option value="all" >All</option>
                            <option value="<?php echo G_Employee_Loan::DONE; ?>" ><?php echo G_Employee_Loan::DONE; ?></option>
                            <option value="<?php echo G_Employee_Loan::PENDING; ?>" ><?php echo G_Employee_Loan::PENDING; ?></option>
                            <option value="<?php echo G_Employee_Loan::IN_PROGRESS; ?>" ><?php echo G_Employee_Loan::IN_PROGRESS; ?></option>
                            <option value="<?php echo G_Employee_Loan::CANCELLED; ?>" ><?php echo G_Employee_Loan::CANCELLED; ?></option>
                    </select>
                </td>        
            </tr>              
            <tr>
                <td class="field_label">Deduction Type:</td>
                <td>
                    <select class="select_option" name="deduction_type" id="deduction_type">
                        <option value="all" >All</option>
                            <option value="<?php echo G_Employee_Loan::BI_MONTHLY; ?>" ><?php echo G_Employee_Loan::BI_MONTHLY; ?></option>
                            <option value="<?php echo G_Employee_Loan::MONTHLY; ?>" ><?php echo G_Employee_Loan::MONTHLY; ?></option>
                            <option value="<?php echo G_Employee_Loan::WEEKLY; ?>" ><?php echo G_Employee_Loan::WEEKLY; ?></option>
                            <option value="<?php echo G_Employee_Loan::DAILY; ?>" ><?php echo G_Employee_Loan::DAILY; ?></option>
                            <option value="<?php echo G_Employee_Loan::QUARTERLY; ?>" ><?php echo G_Employee_Loan::QUARTERLY; ?></option>
                    </select>              
                </td>
            </tr> 
            <tr>
                <td class="field_label">Positions:</td>
                <td>
                    <select class="select_option" name="position_type" id="position_type">
                        <option value="all" >All</option>
                            <?php foreach($positions as $p) { ?>
                            <option value="<?php echo $p->getId(); ?>" ><?php echo $p->getTitle(); ?></option>
                            <?php } ?>
                    </select>              
                </td>
            </tr>                                               
            <tr>
                <td></td>
                <td class="form-inline">                
                    <div class="rep-checkbox-container">
                      <label class="checkbox"><input type="checkbox" name="loan_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="loan_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="loan_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="loan_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                    </div>
                </td>
            </tr>                      
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input class="blue_button" type="submit" name="button" id="button"  value="Search"></td>
            </tr>
        </table>
    </div>
</form>
</div>
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>