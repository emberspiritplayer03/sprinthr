<style>
.ui-datepicker-calendar{
     display:none;
}
</style>
<script>
$(function() {
	$("#date_from").datepicker({
		dateFormat: 'MM yy',
		changeMonth:true,
		changeYear:true,
		showButtonPanel: true,		
		showOtherMonths:true,		
		onClose: function() {					
			var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var iYear  = $("#ui-datepicker-div .ui-datepicker-year :selected").val();			
			$(this).datepicker('setDate', new Date(iYear, iMonth, 1));			
			$('#date_to').datepicker('option', 'minDate', new Date(iYear, iMonth, 1));								
			loadEmployeeSummaryByDateRange($(this).val(),$("#date_to").val());
     	},
		beforeShow: function() {		  
		   if ((selDate = $(this).val()).length > 0) 
		   {
			  iYear = selDate.substring(selDate.length - 4, selDate.length);
			  iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), 
					   $(this).datepicker('option', 'monthNames'));
			  $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
			  $(this).datepicker('setDate', new Date(iYear, iMonth, 1));			 
		   }
		}
	});
	
	$("#date_to").datepicker({
		dateFormat: 'MM yy',
		changeMonth:true,
		changeYear:true,
		showButtonPanel: true,		
		showOtherMonths:true,		
		onClose: function() {			
			var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();			
			$(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			loadEmployeeSummaryByDateRange($("#date_from").val(),$(this).val());			
     	},
		beforeShow: function() {		   		  			  
		  if ((selDate = $(this).val()).length > 0) 
		   {
			  iYear = selDate.substring(selDate.length - 4, selDate.length);
			  iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), 
					   $(this).datepicker('option', 'monthNames'));
			  $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
			  $(this).datepicker('setDate', new Date(iYear, iMonth, 1));			 
		   }
		}
	});
});
</script>
<div id="hrplist_container">
<div class="container_12">
	<div class="col_1_2">
        <div class="inner">
            <div class="section reminder_area">
                <h2 class="sectiontab_title"><span class="icongear"></span>Number of Employees per Year 
                  <select name="year" id="year" onchange="javascript:loadTotalEmployeesByYear();">
                  <?php 
				  $x=1;
				  $year = date("Y");
				  while($x<=10) { ?>
                    <option value="<?php echo $year; ?>" ><?php echo $year; ?></option>
                    <?php  
					$x++;
					$year--;
					 ?>
                  <?php } ?>
                  </select>
                </h2>
                <div id="employee_by_year_wrapper">
                <?php include 'includes/no_employees_by_year.php'; ?>
                </div>
                
            </div>
            <div class="section persononleave_area">
                <h2 class="sectiontab_title">
                	<span class="icongear"></span>Employees Summary                    
                </h2>
                <table style="width:90%;border:0 !important;">
                    	<tr>
                        	<td class="no-border">From :</td>
                            <td class="no-border"><input type="text" name="date_from" id="date_from" style="width:80%;" />
                            </td>
                            <td class="no-border">To :</td>
                            <td class="no-border"><input type="text" name="date_to" id="date_to" style="width:80%;" /> </td>
                        </tr>
                        <tr>
                        	<td class="no-border" colspan="4">
                            	<div id="employee_summary_by_date_range_wrapper">
                            	<?php include 'includes/employee_summary.php'; ?>
                                </div>
                            </td>
                        </tr>
                    </table>       
            </div>
         <!--   <div class="section attendance_area">
                <h2 class="sectiontab_title"><span class="icongear"></span>Number of Employees By Salary</h2>
                <?php //include 'includes/no_employees_by_salary.php'; ?>
            </div>-->
          
        </div>
    </div>
    <div class="col_1_2">
    	<div class="inner">
          <div class="section persononleave_area">
                <h2 class="sectiontab_title"><span class="icongear"></span>Headcount <select style="visibility:hidden;"><option>Test</option></select></h2>
                
                <?php include 'includes/headcount_by_department.php'; ?>
            </div>
         <!--  <div class="section attendance_area">
                <h2 class="sectiontab_title"><span class="icongear"></span>Payroll Breakdown</h2>
                <?php // include 'includes/payroll_breakdown.php'; ?>
            </div>-->
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<script>
	$(function() { loadEmployeeSummaryByDateRange(); });
</script>
