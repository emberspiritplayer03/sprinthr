<form name="loanListWithSelectedAction" id="loanListWithSelectedAction">  
<div class="break-bottom inner_top_option">    
    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:loanArchiveWithSelectedAction(this.value);">
        <option value="">With Selected:</option>
        <option value="loan_restore">Restore Archived</option>                        
    </select>
    </div>    
    <div class="clear"></div>
</div>  
    <div id="loan_list_dt_wrapper" class="dtContainer"></div>    
</form>