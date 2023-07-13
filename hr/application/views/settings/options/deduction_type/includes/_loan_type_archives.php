<form name="loanTypeWithSelectedAction" id="loanTypeWithSelectedAction">  
<div class="break-bottom inner_top_option">    
    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkActionLoanType" id="chkActionLoanType" onchange="javascript:loanTypeArchiveWithSelectedAction(this.value);">
        <option value="">With Selected:</option>
        <option value="loan_type_restore">Restore Archived</option>                        
    </select>
    </div>    
    <div class="clear"></div>
</div>  
    <div id="loan_type_list_dt_wrapper" class="dtContainer"></div>    
</form>