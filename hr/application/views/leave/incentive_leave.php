<script>
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

</script>
<?php include('includes/_wrappers.php'); ?>
<div class="break-bottom inner_top_option">	
    <div class="select_dept display-inline-block right-space">
        <strong>Select month - year:</strong> 
            <select id="cmb-month">            
            <?php foreach($months as $key => $month){ ?>
                <option value="<?php echo $key; ?>"><?php echo $month; ?></option>
            <?php } ?>
        </select>
        <select id="cmb-year">
            <?php for($x = $start_year; $x <= $end_year; $x++){ ?>
                <option><?php echo $x; ?></option>
            <?php } ?>
        </select>
        <input type="submit" class="btn btn-view-incentive-leave" value="View" />        
    </div>   
    <a href="javascript:void(0);" class="btn btn-process-incentive-leave pull-right">Process incentive leave</a>
    <div class="clear"></div>
</div>
<div id="incentive_leave_list_dt_wrapper" class="dtContainer"></div>
</div>
<script>
	$(function() { 
        
        $(".btn-view-incentive-leave").click(function(){
            load_employees_incentive_leave_dt();
        });

        $(".btn-process-incentive-leave").click(function(){
            var month = $("#cmb-month").val();
            var year  = $("#cmb-year").val();
            processIncentiveLeave(month,year);
        });

        load_employees_incentive_leave_dt(); 
    });
</script>
