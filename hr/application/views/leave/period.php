<script>
$(function(){
    load_leave_list_dt(0, "<?php echo $selected_frequency; ?>");
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
    function changePayPeriodByYear(selected_year,selected_cutoff,class_container, selected_frequency = 1) {
        $("." + class_container).html(loading_image);
        $.get(base_url + 'leave/ajax_load_payroll_period_by_year',{selected_year:selected_year,selected_cutoff:selected_cutoff, selected_frequency:selected_frequency},
          function(o){
            $("." + class_container).html(o);     
          }
        );
    }     
    $(function(){
        $("#payslip-report-year-selector").change(function(){   
           changePayPeriodByYear(this.value,'<?php echo $cutoff_selected; ?>','payslip-pay-period-container', $("#payslip-report-frequency-selector").val());
        });

        $("#payslip-report-frequency-selector").change(function(){    
           changePayPeriodByYear($("#payslip-report-year-selector").val(),'<?php echo $cutoff_selected; ?>','payslip-pay-period-container',this.value);
        });

        changePayPeriodByYear($("#payslip-report-year-selector").val(),'<?php echo $cutoff_selected; ?>','payslip-pay-period-container', $("#payslip-report-frequency-selector").val());
    });  
</script>

<?php include('includes/_wrappers.php'); ?>
    <div id="detailscontainer" class="detailscontainer_blue details_highlights">
        <div class="earnings_period_selected">
            <div class="overtime_title_period"><?php echo $period_selected; ?>
                <?php if ($previous_cutoff_link != ''){ ?>
                [ Go to:
                <?php if ($previous_cutoff_link != ''):?>
                    <a href="<?php echo $previous_cutoff_link;?>">Previous Cutoff</a>
                <?php else:?>
                    
                <?php endif;?>
                |
                <?php if ($next_cutoff_link != ''):?>
                    <a href="<?php echo $next_cutoff_link;?>">Next Cutoff</a>
                <?php else:?>
                    
                <?php endif;?>
                ]<?php } ?>

                <div class="pull-right" style="position:relative; top:-3px; font-size: 12px !important; margin-right: 8px !important;">
                  <form method="get" action="<?php echo url('leave/period'); ?>">  
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
                        foreach(G_Settings_Pay_Period_Finder::findAll() as $period)
                        {
                        ?>

                            <option value = "<?php echo $period->id; ?>" <?php echo $selected_frequency == $period->id ? 'selected="selected"' : ''?>> <?php echo $period->pay_period_name; ?> </option>    

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
<input type="hidden" id="from_period" name="from_period" value="<?php echo $from_period; ?>"/>
<input type="hidden" id="to_period" name="to_period" value="<?php echo $to_period; ?>" />

<div class="break-bottom inner_top_option">	
    <div class="select_dept display-inline-block right-space">
        <strong>Select Department:</strong> <select id="cmb_dept_id" onchange="javascript:load_leave_list_dt(this.value);">
            <option value="" selected="selected">All</option>
            <?php foreach($departments as $d){ ?>
                <option value="<?php echo $d->getId(); ?>"><?php echo $d->getTitle(); ?></option>
            <?php } ?>
        </select>
    </div>
    <?php if($download_url){ ?>
        <a id="import_undertime" class="gray_button vertical-middle" href="<?php echo $download_url; ?>"><i class="icon-excel icon-custom vertical-middle"></i> <b>Download Leave</b></a>
    <?php } ?>
    <div class="clear"></div>
</div>
<div class="break-bottom">
    <?php if($is_period_lock == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){ ?>   
    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:pendingLeaveWithSelectedAction(this.value);">
            <option value="">With Selected:</option>
            <option value="approve">Approve</option>
            <option value="disapprove">Disapprove</option>  
            <option value="archive">Send to Archive</option>                    
        </select>
    </div> 
	<?php } ?>    
    <div class="clear"></div>
</div>
    <div id="leave_list_dt_wrapper" class="dtContainer"></div>    
</form>

<div id="import_leave_wrapper" style="display:none">
<?php include 'form/import_leave.php'; ?>
</div>