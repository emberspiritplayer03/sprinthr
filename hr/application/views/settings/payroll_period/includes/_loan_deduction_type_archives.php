<form name="loanDeductionTypeWithSelectedAction" id="loanDeductionTypeWithSelectedAction">  
<div class="break-bottom inner_top_option">    
    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkActionLoanDeductionType" id="chkActionLoanDeductionType" onchange="javascript:loanDeductionTypeArchivedWithSelectedAction(this.value);">
        <option value="">With Selected:</option>
        <option value="loan_deduction_type_restore">Restore Archived</option>                        
    </select>
    </div>    
    <div class="clear"></div>
</div>  
    <div id="loan_deduction_type_list_dt_wrapper" class="dtContainer"></div>    
</form>