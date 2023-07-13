<?php include('includes/_wrappers.php'); ?>
<script>
$(function(){
	$("#year").change(function(){
		var year_selected = this.value;
		load_annualize_tax_by_year(year_selected);
	});
});
</script>
<div class="earnings-dt-container">
	<form name="withSelectedAction" id="withSelectedAction">	
	<div class="break-bottom inner_top_option">
		<div class="pull-left" style="width:50%;">
        	Year : 
        	<select style="width:40%;" name="year" id="year">
        	<?php for( $x = $start_year; $x <= date("Y"); $x++  ){ ?>
        		<option><?php echo $x; ?></option>
        	<?php } ?>
        	</select>
        </div>  
	    <div class="pull-right datatable_withselect display-inline-block right-space">                        
            <a class="btn btn-small" href="<?php echo url('annualize_tax/process_annual_tax'); ?>">Process Annual Tax</a> 
        </div> 	    	
	    <div class="clear"></div>
	</div>
	    <div id="earnings_list_dt_wrapper" class="dtContainer"></div>    
	</form>
</div>
<script>
	$(function() {
		 load_annualize_tax_by_year("<?php echo date("Y"); ?>");		 
	});
</script>
