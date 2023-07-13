<style>
    .approver-name{padding:8px;background-color:#198cc9;color:#ffffff;margin-bottom:3px;}
</style>

<script>
$(document).ready(function() {  
    $('#withSelectedAction').validationEngine({scroll:false});  
    
    $("#chkAction").change(function(){
        withSelectedAction($(this).val());
    });
});
//$(function() {
//	datatable_loader(<?php echo $sidebar; ?>);
//	$('.overtime_action_link').hide();
//});
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
        //$("#chkAction").removeAttr('disabled');
        var status = 1; 
    } else { 
        $('#check_uncheck').attr('title', 'Check All');                                 
        //$("#chkAction").attr('disabled',true);
        var status = 0;
    }
    
    var c = 0;
    var theForm = document.withSelectedAction;
    for (i=0; i<theForm.elements.length; i++) {         
        if (theForm.elements[i].name=='dtChk[]') {
            theForm.elements[i].checked = status;
            c++;
        }
    }

    if(c > 0 && status == 1) {
        $("#chkAction").removeAttr('disabled');
    }else{
        $("#chkAction").attr('disabled',true);
    }
}

function showPageByDepartment(group_id) {
    var query = window.location.search;
    //window.location.href = '#group_id='+ group_id;

    window.location.href = base_url + 'overtime/period'+ query + '&group_id=' + group_id + '&pageID=1';

    //$('#filter_form').submit();

    //$.get(base_url + 'overtime/period'+ query, {group_id: group_id, ajax:1}, function(html_data){
    //    $('#main').html(html_data)
    //});
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

<input type="hidden" id="from_period" name="from_period" value="<?php echo $from_period; ?>" />
<input type="hidden" id="to_period" name="to_period" value="<?php echo $to_period; ?>" />
<input type="hidden" id="frequency_period" name="frequency_period" value="<?php echo $selected_frequency; ?>" />

<div id="main">

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
                          <option value = "1" <?php echo $selected_frequency == '1' ? 'selected="selected"' : ''?>>Bi-Monthly</option>
                          <option value = "2" <?php echo $selected_frequency == '2' ? 'selected="selected"' : ''?>>Weekly</option> 
                           <option value = "3" <?php echo $selected_frequency == '3' ? 'selected="selected"' : ''?>>Monthly</option>    
                        </select>              
                        <input class="gray_button" type="submit" name="submit" value="Load">
                    </form>
                </div>

            </div>            
        </div>
    </div>
<div id="form_default">
<div class="break-bottom inner_top_option">

    <h2><?php echo $sub_title;?></h2><br>
   
	<div class="select_dept display-inline-block right-space">
        <form id="filter_form" method="get">
        <strong>Show by department:</strong>
            <select class="select_option_sched" id="department_id" name="department_id" onchange="javascript:showPageByDepartment(this.value)"><!--onchange="javascript:showPageByDepartment(this.value)"-->
            <option value="">All</option>
            <?php foreach($departments as $d){ ?>
                <option <?php echo ($group_id == $d->getId()) ? 'selected="selected"' : ''  ;?> value="<?php echo $d->getId(); ?>"><?php echo $d->getName(); ?></option>
            <?php } ?>
        </select>
        <?php if($permission_action == Sprint_Modules::PERMISSION_02 && $sidebar != 4) { ?>
            <div style="float:right;margin-left:273px;">
                <select style="width:150px;" disabled="disabled" name="chkAction" id="chkAction" >
                    <option value="">With Selected:</option>  
                    <?php if($ot_status == "pending") { ?>
                        <option value="approve">Approve</option>      
                        <option value="disapprove">Disapprove</option>
                    <?php }elseif($ot_status == "approved") { ?>   
                        <option value="pending">Set as Pending</option>    
                        <option value="disapprove">Disapprove</option>
                    <?php }elseif($ot_status == "disapproved") {?>
                        <option value="pending">Set as Pending</option>   
                        <option value="approve">Approve</option>
                    <?php } ?>                                            
                </select>
            </div>
        <?php } ?>
        </form>
        <br/>
            
        
    </div>
	 <?php if($download_url){ ?>
        <div style="float:right"><a id="import_undertime" class="gray_button" href="<?php echo $download_url; ?>"><i class="icon-excel icon-custom"></i> Download Result</a></div>
    <?php } ?>
    <?php if($is_period_lock == (isset($frequency_id) == false || $frequency_id != 2 ? G_Cutoff_Period::YES : G_Weekly_Cutoff_Period::YES)){ ?>   
	     <div class="pull-right">
    	    <span class="label label-important"><i class="icon-lock disabled"></i> Selected period is lock for changes</span>
	     </div>
     <?php } ?>
    <div class="clear"></div>
</div>
	<?php if($sidebar == 1 || $sidebar == 5){ ?>
    <div class="break-bottom">
	<div class="datatable_withselect display-inline-block right-space">
        <?php if($is_period_lock == (isset($frequency_id) == false || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO)){ ?>   
            <!--<select class="select_option_sched" disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:change_overtime_request_status(this.value);">
                <option value="">With Selected:</option>
                <option value="<?php //echo G_Employee_Overtime_Request::APPROVED; ?>">Approve</option>
                <option value="Archive">Send to Archive</option>
            </select>
        <?php } ?>-->
    </div>
        <?php include('includes/overtime.php'); ?>
    <div class="clear"></div>
	</div>
    <?php }elseif($sidebar == 2){ ?>
        <div class="break-bottom">
            <div class="datatable_withselect display-inline-block right-space">
                <?php if($is_period_lock == (isset($frequency_id) == false || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO)){ ?>   
                    <!--<select class="select_option_sched" disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:change_overtime_request_status(this.value);">
                        <option value="">With Selected:</option>
                        <option value="<?php echo G_Employee_Overtime_Request::APPROVED; ?>">Approve</option>
                        <option value="Archive">Send to Archive</option>
                    </select>-->
                <?php } ?>
            </div>
            <?php
            include('includes/overtime.php');
            ?>
            <div class="clear"></div>
        </div>
    <?php }elseif($sidebar == 4){ ?>
    <div class="break-bottom">
	<div class="datatable_withselect display-inline-block right-space">
        <?php if($is_period_lock == (isset($frequency_id) == false || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO)){ ?>   
            <!--<select class="select_option_sched" disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:change_overtime_request_status(this.value);">
                <option value="">With Selected:</option>           
                <option value="Restore Archive">Restore Archived</option>                    
            </select>-->
        <?php } ?>
    </div>
        <?php
        include('includes/error_reports.php');
        ?>
    <div class="clear"></div>
	</div>
    <?php } ?>

</div>

</div>