<div id="form_main" class="inner_form popup_form">
    <form id="editDeductionType" class="editDeductionType" name="editDeductionType"  action="<?php echo url('loan/_insert_new_loan_type'); ?>" method="post"> 
    <input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />    
    <input type="hidden" id="loan_type_id" name="loan_type_id" value="<?php echo Utilities::encrypt($d->getId()); ?>" />
      
        <div id="form_default">      
            <table>
                 <tr>
                   <td class="field_label">Name:</td>
                   <td><input class="validate[required] text-input" type="text" name="loan_type" id="loan_type" value="<?php echo $d->getLoanType(); ?>" style="width:100%;" /></td>
                 </tr>                             
             </table>
        </div>
        <div id="form_default" class="form_action_section">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="field_label">&nbsp;</td>
                    <td>
                    <input type="submit" value="Save" class="curve blue_button" />
                    <a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editDeductionType');">Cancel</a>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div><!-- #form_main -->


