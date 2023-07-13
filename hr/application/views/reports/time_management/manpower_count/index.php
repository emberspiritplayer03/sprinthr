<style>
.report-options-list, .options-list{list-style:none;}
ul.report-options-list li.report-options-li{display:-moz-grid;width:306px;margin:10px;padding:9px;}
/*ul.report-options-list li.li-sections{width:90%;}*/
.report-options-list p{font-weight:bold;margin:3px;background-color:#198CC9;color:#ffffff;padding:5px;}
div.option-container{height:auto;}
</style>
<script>
$(function(){
     $("#frm-report-manpower-count").validationEngine({scroll:false}); 

     $("#manpower_date_from").datepicker({    
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect    :function() { 
            $("#mc_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
        }
    }); 

    $("#manpower_date_to").datepicker({   
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect    :function() { 
        
        }
    }); 
});
</script>
<h2><?php echo $title; ?></h2>
<div id="form_main" class="employee_form">
<form id="frm-report-manpower-count" name="form1" method="post" action="<?php echo url('reports/download_manpower_count_data'); ?>">
     <div id="form_default">
        <h3 class="section_title"></h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td><input type="text" id="manpower_date_from" class="validate[required]" name="date_from" /></td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td><input type="text" id="manpower_date_to" class="validate[required]" name="date_to"  /></td>
            </tr>  
            <tr>
                <td></td>
                <td class="form-inline">                
                    <div class="rep-checkbox-container">
                      <label class="checkbox"><input type="checkbox" name="manpower_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                      <label class="checkbox"><input type="checkbox" name="manpower_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                      <label class="checkbox"><input type="checkbox" name="manpower_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                      <label class="checkbox"><input type="checkbox" name="manpower_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                    </div>
                </td>
            </tr>            
            <tr>
                <td class="field_label">Report Type:</td>
                <td>
                <select class="r_type" name="r_type" id="r_type" >
                    <option value="<?php echo 'summarized'; ?>">SUMMARIZED</option>
                    <option value="<?php echo 'detailed'; ?>">DETAILED</option>
                </select></td>
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
