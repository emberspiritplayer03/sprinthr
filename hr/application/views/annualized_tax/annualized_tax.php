<?php include('includes/_wrappers.php'); ?>
<script>
$(function(){
	$("#year").change(function(){
		var year_selected = this.value;
		load_annualized_tax_by_year(year_selected);
	});
});
</script>
<div class="earnings-dt-container">
	<form name="withSelectedAction" id="withSelectedAction" action="<?php echo url('annualize_tax/process_tax'); ?>">
	<div class="break-bottom inner_top_option">
		<div class="pull-left" style="width:50%;">
        	Year : 
        	<select style="width:40%;" name="year" id="year">
        	<?php for( $x = $start_year; $x <= date("Y"); $x++  ){ ?>
        		<option <?php echo( $x == date("Y") ? 'selected="selected"' : '' ); ?> ><?php echo $x; ?></option>
        	<?php } ?>
        	</select>
        </div>  
	    <div class="pull-right datatable_withselect display-inline-block right-space">                        
           <!--  <a class="btn btn-small" href="<?php echo url('annualize_tax/process_tax'); ?>">Annualize Tax</a>  -->
         <input id="btn_tax" type="submit" value="Annualize Tax">
        </div> 	    	
	    <div class="clear"></div>
	</div>
	    <div id="annualized_tax_list_dt_wrapper" class="dtContainer"></div>    
	</form>
</div>
<script>
	$(function() {
		 load_annualized_tax_by_year("<?php echo date("Y"); ?>");		 
	});
</script>
<style type="">
	
#btn_tax{
color:#333333;
text-decoration: none;
background-color:#e6e6e6;
*background-color: #d9d9d9;
background-position: 0 -15px;
-webkit-transition: background-position 0.1s linear;
-moz-transition: background-position 0.1s linear;
-o-transition: background-position 0.1s linear;
transition: background-position 0.1s linear;
border-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.25);
padding: 3px 9px;
font-size: 12px;
line-height: 18px;
text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
text-align: center;
vertical-align: middle;
cursor: pointer;
margin-bottom: 0;
display: inline-block;
border: 1px solid #bbbbbb;
border-radius: 3px;
box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
background-image: linear-gradient(to bottom, #ffffff,#e6e6e6);
background-repeat: repeat-x;
}

</style>
