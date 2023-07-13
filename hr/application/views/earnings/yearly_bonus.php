<script>
$(document).ready(function() {	
	//$('#withSelectedAction').validationEngine({scroll:false});	
	$(".btn-process-yearly-bonus").click(function(){
		processYearlyBonus();
	});
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
		//$("#chkAction").removeAttr('disabled');
		var status = 1; 
	} else { 
		$('#check_uncheck').attr('title', 'Check All');									
		//$("#chkAction").attr('disabled',true);
		var status = 0;
	}
	
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}

</script>
<?php include('includes/_wrappers.php'); ?>
<div class="earnings-dt-container">
	<form name="withSelectedAction" id="withSelectedAction">
	<input type="hidden" name="eid" id="eid" value="<?php echo $eid; ?>" />
	<div class="break-bottom inner_top_option">
		<div class="pull-left" style="width:50%;">
        	Year : 
        	<select style="width:40%;" name="year" id="year">
        	<?php for( $x = $start_year; $x <= date("Y"); $x++  ){ ?>
        		<option <?= ($x ==  date('Y') ? 'selected="selected"' : ''); ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
        	<?php } ?>
        	</select>
        </div>  
	    <div class="pull-right datatable_withselect display-inline-block right-space">            
            <!-- <a class="btn btn-small btn-process-yearly-bonus" href="javascript:void(0);">Process Yearly Bonus</a>  -->                
            <a class="btn btn-small" href="<?php echo url('earnings/process_yearly_bonus'); ?>">Process Yearly Bonus</a> 
        </div> 	    	
	    <div class="clear"></div>
	</div>
	    <div id="earnings_list_dt_wrapper" class="dtContainer"></div>    
	</form>
</div>
<script>
	$(function() {
		 load_yearly_bonus_list_dt("<?php echo date("Y"); ?>");	

		 $('#year').change(function(){
		 	load_yearly_bonus_list_dt($(this).val());	
		 });

	});
</script>
