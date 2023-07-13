<style>
#myProgressGeneratePayslip {
  width: 100%;
  background-color: #ddd;
}

#generatePayslipProgressBar {
  width: 5%;
  height: 30px;
  background-color: #0d76ac;
  text-align: center;
  line-height: 30px;
  color: white;
}

.ui-dialog-titlebar-close {
  /*display: none;*/
}

#myProgressGeneratePayslipFull {
  width: 100%;
  background-color: #ddd;
}

#generatePayslipProgressBarFull {
  width: 99%;
  height: 30px;
  background-color: #0d76ac;
  text-align: center;
  line-height: 30px;
  color: white;
}

</style>
<script>
var jq = jQuery.noConflict();
$(function(){
    $('#generatePayrollForm').validationEngine({scroll:false});     
    $('#generatePayrollForm').ajaxForm({
        success:function(o) {
            
            /*if(o.is_success){
                var query = window.location.search;
                dialogOkBox(o.message,{ok_url:"payroll_register/generation"+query});  
            }else{
               dialogOkBox(o.message,{});   
            }*/

            if(o.is_success){
                var query = window.location.search;
                showLoadingDialogProgressBarFull('Generate Payslip...');
                setTimeout(function(){
                    dialogOkBox(o.message,{ok_url:"project_site/payroll_register"+query});  
                  }, 1000);                     
                
            }else{
               dialogOkBox(o.message,{});   
            }            
            
        },
        dataType:'json',
        beforeSubmit: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            //showLoadingDialog('Processing...');        
            showLoadingDialogProgressBar('Generate Payslip...');
            return true;
        }
    }); 

    $("#generatePayrollForm")[0].reset();
    $(".filter-select").prop("disabled",true);
    $("#all_employee").click(function(){
        if($(this).is(":checked")) {
            $("#department_id").val("all");
            $(".filter-select").prop("disabled",true);
            $("#selected_employee_id").val("");    
            loadEmployeeByDepartmentId("null");
            $('#employee_wrapper').html(""); 
            $(".hide-show-tr").fadeOut(1000);
        }else{
            $(".filter-select").removeProp("disabled");
            loadEmployeeByDepartmentId("all");
            var selected_employee_id = $("#selected_employee_id").val();
            $(".hide-show-tr").fadeIn(1000);
            loadSelectedEmployees(selected_employee_id);
        }
        //$("#department_id").trigger("change");
    });

    $("#department_id").change(function(){
        var department_id = $(this).val();
        loadEmployeeByDepartmentId(department_id);
    });

    $("#toggle-btn").click(function(){

    });

    function showLoadingDialogProgressBar(message, params) {

        var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
        var width = 400;
        var height = 'auto';
        var title = message;

        if (params) {
            width = (params.width) ? params.width : width ;
            height = (params.height) ? params.height : height ;
            title = (params.title) ? params.title : title ;
        }   
            
        blockPopUp();
        $(dialog_id).html('<div id="myProgressGeneratePayslip"><div id="generatePayslipProgressBar">1%</div></div><div>Processing...</div>');
        $dialog = $(dialog_id);
        $dialog.dialog({
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true
        }).show().parent().find('.ui-dialog-titlebar-close').hide();    

        function move_progress_bar_update_attendance() {

            var elem     = document.getElementById("generatePayslipProgressBar");   
            var width    = 1;
            var interval = 500;
            //var interval = Math.floor(Math.random() * 2000) + 500; //500;

            var id = setInterval(function(){
                        frameProgressSync();
                      }, interval);

            function frameProgressSync() {
                if (width >= 100) {
                    clearInterval(id);
                } else {
                    width++; 

                    var limit_percentage = Math.floor(Math.random() * (92 - 88 + 1) ) + 83;

                    if( width <= limit_percentage ) {
                        if(width <= 5) {
                            elem.style.width = 5 + '%'; 
                            elem.innerHTML = width * 1  + '%';
                        } else {
                            elem.style.width = width + '%'; 
                            elem.innerHTML = width * 1  + '%';                      
                        }

                    } else {

                    }
                }

            }

        }

        $(function(){
          move_progress_bar_update_attendance();
        }); 

    } 
      
    function showLoadingDialogProgressBarFull(message, params) {

        var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
        var width = 400;
        var height = 'auto';
        var title = message;

        if (params) {
            width = (params.width) ? params.width : width ;
            height = (params.height) ? params.height : height ;
            title = (params.title) ? params.title : title ;
        }   
            
        blockPopUp();
        $(dialog_id).html('<div id="myProgressGeneratePayslipFull"><div id="generatePayslipProgressBarFull">100%</div></div><div>Processing...</div>');
        $dialog = $(dialog_id);
        $dialog.dialog({
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true
        }).show().parent().find('.ui-dialog-titlebar-close').hide();    

        function move_progress_bar_generate_payslip_full() {

            var elem     = document.getElementById("generatePayslipProgressBarFull");   
            var width    = 100;
            var interval = 100;

            var id = setInterval(function(){
                        frameProgressSync();
                      }, interval);

            function frameProgressSync() {
                if (width >= 100) {
                    clearInterval(id);
                } else {
                    width = 100;; 
                    elem.style.width = width + '%'; 
                    elem.innerHTML = width * 1  + '%';                      
                }

            }

        }

        $(function(){
          move_progress_bar_generate_payslip_full();
        }); 

    } 

});

function loadEmployeeByDepartmentId(department_id) {
    var q = $("#q").val();
    $("#employee-select-wrapper").html(loading_image);
    $.post(base_url + 'payroll_register/_load_employee_select',{department_id:department_id,q:q},function(o) {
        $('#employee-select-wrapper').html(o);       
    }); 
}

function loadSelectedEmployees(selected_employee_id) {
    $.post(base_url + 'payroll_register/_load_selected_employee',{selected_employee_id:selected_employee_id},function(o) {
        $('#employee_wrapper').html(o);    
        jq("#employee_id").select2("val", "");
        $("#loading-msg").html("");
    }); 
}
</script>
<table class="table">
    <tr class="info">
        <th colspan="4">Payroll Details</th>
    </tr>
    <tr class="info">
        <td width="25%">
            Date : <b><?php echo Tools::getMonthString($data['month']); ?> <?php echo $data['year'];?></b>
        </td>
        <td width="25%">
            Cutoff : <b><?php echo $data['cutoff_number'].($data['cutoff_number'] == 1 ? "st" : "nd"); ?> </b>
        </td>
        <td width="25%">
            Type : <b><?php echo ucfirst($data['q']); ?></b>
        </td>
         <td width="25%">
            Frequency :  <b> <?php  echo G_Settings_Pay_Period_Helper::getPayPeriodNameById($data['frequency'])['pay_period_name'];  
             ?></b>
        </td>
    </tr>
    <tr class="info" >
        <td  >
            Processed Payroll : <b><?php echo $payroll_employee_details['processed_payroll'];?> Employee(s)</b> 
        </td>
        <td >
            Unprocessed Payroll : <b><?php echo $payroll_employee_details['unprocessed_payroll'];?> Employee(s)</b>
        </td>
        <td>
            Total Employees : <b><?php echo $payroll_employee_details['total_employees'];?> Employee(s)</b>
        </td>
        <td colspan="1">
            
        </td>
    </tr>
<!--     <tr class="info">
        <td width="33%">
            Processed Payroll : <b><?php echo $payroll_employee_details['processed_payroll'];?> Employee(s)</b> 
        </td>
        <td width="33%">
            Unprocessed Payroll : <b><?php echo $payroll_employee_details['unprocessed_payroll'];?> Employee(s)</b>
        </td>
        <td width="33%">
            Total Employees : <b><?php echo $payroll_employee_details['total_employees'];?> Employee(s)</b>
        </td>
    </tr>  -->
</table>


<!-- <div id="form_main" class="inner_form popup_form"> -->
<div id="form_main" class="">
    <form name="generatePayrollForm" id="generatePayrollForm" method="post" action="<?php echo url('project_site/generate_payslip'); ?>">    
    <input type="hidden" id="month" name="month" value="<?php echo $data['month']; ?>" />
    <input type="hidden" id="cutoff_number" name="cutoff_number" value="<?php echo $data['cutoff_number']; ?>" />
    <input type="hidden" id="year" name="year" value="<?php echo $data['year']; ?>" />
    <input type="hidden" id="q" name="q" value="<?php echo $data['q']; ?>" />
    <input type="hidden" id="selected_employee_id" name="selected_employee_id" value="" />
    <input type="hidden" name="frequency_type_id" value="<?php echo $data['frequency']; ?>">
    <div id="form_default">
       
    <a href="javascript:void(0);" id="toggle-btn" style="text-decoration:none;">
        <h3 id="" style="padding: 2px 2px 2px 15px; background-color: gray; color: rgb(255, 255, 255);">Payroll Options <i class="icon icon-chevron-down icon-white"></i></h3>
    </a>
    <div id="toggle-content">
        <table width="100%"> 
            <tr>
                <td >&nbsp;</td>
                <td >
                    <input checked="checked" type="checkbox" style="vertical-align:text-bottom;"  name="all_employee" id="all_employee"> 
                    <label style="display:inline-flex;padding:5px;" for="all_employee">  All Employees </label>
                </td>
            </tr>
            <tr>
                <td width="25%" class="">Department </td>
                <td width="75%" class="">
                    <select class=" filter-select" id="department_id" name="department_id" >
                      <option value="all">All</option>
                      <?php foreach($departments as $d) { ?>
                        <option value="<?php echo Utilities::encrypt($d->id);?>"><?php echo $d->title;?></option>
                      <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="25%" class="">Employee </td>
                <td width="75%" class="" valign="top" >
                    <div id="employee-select-wrapper">
                        <select class=" filter-select" id="employee_id" name="employee_id" >
                            <option value="all">All</option>
                        </select>
                    </div>              
                </td>
            </tr>
            <tr>
                <td width="25%" class="">&nbsp;</td>
                <td width="75%" class="">
                    <input style="vertical-align:sub;" type="checkbox" name="remove_resigned" class="" id="remove_resigned" /> <label style="display:inline-flex;" for="remove_resigned">Remove Resigned</label>
                    &nbsp;&nbsp;
                    <input style="vertical-align: sub;" type="checkbox" name="remove_terminated" class="" id="remove_terminated" /> <label style="display:inline-flex;" for="remove_terminated">Remove Terminated</label>
                    &nbsp;&nbsp;
                    <input style="vertical-align: sub;" type="checkbox" name="remove_inactive" class="" id="remove_inactive" checked="" /> <label style="display:inline-flex;" for="remove_terminated">Remove Inactive</label>
                </td>
            </tr>   
            <tr ><td colspan="2">&nbsp;</td></tr> 
           </table>
           <div class="hide-show-tr" style="display:none;">

                    <div style="width:100%;" id="employee_wrapper"></div>
            </div>
        </div>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>

                <td>
                    <div class="pull-right">
                        <input value="Confirm" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;
                        <!-- <a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editGroupTeam');">Cancel</a> -->
                    </div>
                </td>
            </tr>
        </table>
    </div>    
    </form>
</div>