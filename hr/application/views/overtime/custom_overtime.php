<script>
$(document).ready(function() { 
    jq17 = jQuery.noConflict(); 
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
        $.get(base_url + 'overtime/ajax_load_payroll_period_by_year',{selected_year:selected_year,selected_cutoff:selected_cutoff, selected_frequency:selected_frequency},
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

    <div class="detailscontainer_blue details_highlights" id="detailscontainer">
        <div class="earnings_period_selected">
            <div class="overtime_title_period"><?php echo $period_selected; ?>
                
                <?php if ($previous_cutoff_link != ''):?>
                    [ Go to:
                    <a href="<?php echo $previous_cutoff_link;?>">Previous Cutoff</a>
                <?php else:?>
                <?php endif;?>
                <?php if ($next_cutoff_link != ''):?>
                    |<a href="<?php echo $next_cutoff_link;?>">Next Cutoff</a>]
                <?php else:?>
                <?php endif;?>

                <div class="pull-right" style="position:relative; top:-3px; font-size: 12px !important; margin-right: 8px !important;">
                    <?php
                        if(!empty($get_sidebar)) {
                            $url = url('overtime/period?sidebar='. $get_sidebar);
                        } else {
                            $url = url('overtime/period');
                        }
                    ?>
                    <form method="get" action="<?php echo $url; ?>">  
                        <input type="hidden" name="sidebar" value="<?php echo $get_sidebar; ?>">
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
<input type="hidden" id="date_from" value="<?php echo $period['from']; ?>">
<input type="hidden" id="date_to" value="<?php echo $period['to']; ?>">
<input type="hidden" id="selected_frequency" value="<?php echo $selected_frequency; ?>">
<div class="break-bottom inner_top_option">
<?php if($is_period_lock == (isset($frequency_id) == false || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO)){ ?>   
    <!-- <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:withSelectedPendings(this.value);">
            <option value="">With Selected:</option>            
            <option value="ob_approve">Approve</option>      
            <option value="ob_archive">Disapprove</option>                     
        </select>
    </div>        -->  
<?php }else{ ?>
    <!-- <div class="pull-right">            
        <span class="label label-important"><i class="icon-lock disabled"></i> Selected period is lock for changes</span>
    </div> -->
<?php } ?>
    <?php if($download_url){ ?>
        <!-- <a id="import_undertime" class="gray_button" href="<?php echo $download_url; ?>"><i class="icon-excel icon-custom vertical-middle"></i> <b>Download</b></a> -->
    <?php } ?>
    <div class="clear"></div>
</div>
    <div id="custom_overtime_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
    $(function() {
         load_custom_overtime_list("<?php echo $period['from']; ?>","<?php echo $period['to']; ?>","<?php echo $selected_frequency; ?>");      
    });
</script>
