<script>
$(document).ready(function() {	
	$('#withSelectedAction').validationEngine({scroll:false});	
});

function countChecked()
{		
	var inputs     = document.withSelectedAction.elements['dtChk[]'];
	var is_checked = false;
	var cnt        = 0;
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            is_checked = theForm.elements[i].checked;
			if(is_checked){								 
			 	cnt++;
			}
    }
	
	return cnt;

}

function chkUnchk()
{
	var check_uncheck = document.withSelectedAction.elements['check_uncheck'];
	if(check_uncheck.checked == 1) {	
		$('#check_uncheck').attr('title', 'Uncheck All');									
		$("#chkAction").removeAttr('disabled');
		var status = 1; 
	} else { 
		$('#check_uncheck').attr('title', 'Check All');									
		$("#chkAction").attr('disabled',true);
		var status = 0;
	}
	
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}
</script>

<script>
    function changePayPeriodByYear(selected_year,selected_cutoff,class_container,selected_frequency) {
        $("." + class_container).html(loading_image);
        $.get(base_url + 'deductions/ajax_load_payroll_period_by_year',{selected_year:selected_year,selected_cutoff:selected_cutoff, selected_frequency:selected_frequency},
          function(o){
            $("." + class_container).html(o);     
          }
        );
    }     
    $(function(){
        $("#payslip-report-year-selector").change(function(){   
           changePayPeriodByYear(this.value,'<?php echo $cutoff_selected; ?>','payslip-pay-period-container','<?php echo $selected_frequency; ?>');
        });

        $("#payslip-report-frequency-selector").change(function(){    
           changePayPeriodByYear($("#payslip-report-year-selector").val(),'<?php echo $cutoff_selected; ?>','payslip-pay-period-container',this.value);
        });

        changePayPeriodByYear($("#payslip-report-year-selector").val(),'<?php echo $cutoff_selected; ?>','payslip-pay-period-container','<?php echo $selected_frequency; ?>');
    });  
</script>

<?php include('includes/_wrappers.php'); ?>
    <div class="detailscontainer_blue details_highlights" id="detailscontainer">
        <div class="earnings_period_selected">
            <div class="overtime_title_period"><?php echo $period_selected; ?>
                <?php //echo G_Cutoff_Period_Helper::showPeriodNavigation($cutoff_id, $location); ?>
                <div class="pull-right" style="position:relative; top:-3px; font-size: 12px !important; margin-right: 8px !important;">
                    <?php
                        $url = url('deductions/approved');
                    ?>
                    <form method="get" action="<?php echo $url; ?>">  
                        <div class="payslip-pay-period-container" style="display:inline-block;"></div>
                        <select name="year_selected" id="payslip-report-year-selector">
                          <?php foreach($all_cutoff_years as $year){ ?>
                                  <?php if($year <= date("Y")) { ?>
                                          <option <?php echo $year_selected == $year ? 'selected="selected"' : ''?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                  <?php } ?>
                          <?php } ?>
                        </select>
                        <select id="payslip-report-frequency-selector" name="selected_frequency">
                            <?php
                            foreach(G_Settings_Pay_Period_Finder::findAll() as $freq)
                            {
                            ?>

                                <option value = "<?php echo $freq->id; ?>" <?php echo $selected_frequency == $freq->id ? 'selected="selected"' : ''?>> <?php echo $freq->pay_period_name; ?> </option>    

                            <?php
                            }
                            ?>

                          <!-- <option value = "1" <?php echo $selected_frequency == '1' ? 'selected="selected"' : ''?>>Bi-Monthly</option>
                          <option value = "2" <?php echo $selected_frequency == '2' ? 'selected="selected"' : ''?>>Weekly</option>     -->
                        </select>                  
                        <input class="gray_button" type="submit" name="submit" value="Load">
                    </form>
                </div>                
            </div>
        </div>
    </div>
<form name="withSelectedAction" id="withSelectedAction">
<input type="hidden" name="eid" id="eid" value="<?php echo $eid; ?>" />
<div class="break-bottom inner_top_option">   
    <!-- <div class="pull-right earnings-total total bold">Total:&nbsp;<span class="float-right"><span class="label label-info" id="total_approved"></span></span></div>     -->
    <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	 
        <div class="datatable_withselect display-inline-block right-space">    
            <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:withSelectedApproved(this.value);">
                <option value="">With Selected:</option>                              
                <option value="deduction_archive">Send to Archive</option>                     
            </select>
        </div>
    <?php }else{ ?>  
        <div class="pull-right">        	
            <span class="label label-important"><i class="icon-lock disabled"></i> Selected period is lock for changes</span>
        </div>
    <?php } ?>
    <?php if($download_url){ ?>
        <a id="import_undertime" class="gray_button vertical-middle" href="<?php echo $download_url; ?>"><i class="icon-excel icon-custom vertical-middle"></i> <b>Download Deductions</b></a>
    <?php } ?>
    <div class="clear"></div>
</div>
	<!--<div class="ui-state-highlight ui-corner-all"><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
        All data below will be included in payroll period : <span class="red bold"><?php echo $period['from']; ?></span> to <span class="red bold"><?php echo $period['to']; ?></span>
    </div>
    <br />-->
    <div id="deductions_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
	$(function() { 
        var frequency_id = "<?php echo $frequency_id = $selected_frequency; ?>";    
        
		load_approved_deductions_list_dt("<?php echo $eid; ?>",frequency_id);
		load_sum_approved_deductions("<?php echo $eid; ?>");
	});
</script>
