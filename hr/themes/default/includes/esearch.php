<script>

	$(function() {
		$( "#period_from" ).datepicker({			
			dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true,
			onSelect	:function() { 
				$("#period_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
			}
		});
		$( "#period_to" ).datepicker({			
			dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true
		});
		
		can_manage = "<?php echo $can_manage ?>";
	});

</script>
<div id="search_helper_holder" style="top:34px;">
	<div id="from_to" style="display:none">
    	<input type="text" id="period_from" placeholder="Date From" />
        <input type="text" id="period_to" placeholder="Date To" />         
         <a class="btn btn-primary btn-small" href="javascript:void(0);" onclick="javascript:loadPeriodFromTo();">Add</a>
         <a href="javascript:void(0);" onClick="javascript:$('#from_to').hide();" class="btn btn-mini" title="Close"><i class="icon-remove"></i></a> 
    </div>
    <div id="department_option" style="display:none">
    
      <select onChange="javascript:loadDepartment();" class="curve employee_search_select" name="department" id="department">
        <option>Please Select Department</option>
       <?php foreach($departments as $key=>$value) { ?>
        <option value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
       <?php } ?>
       
      </select>
      <a href="javascript:void(0);" onClick="javascript:$('#department_option').hide();" class="btn btn-mini" title="Close"><i class="icon-remove"></i></a> 
    </div>


    <div id="position_option" style="display:none">
    
      <select onChange="javascript:loadPosition();" class="curve employee_search_select" name="position" id="position">
        <option>Please Select Position</option>
       <?php foreach($positions as $key=>$value) { ?>
        <option value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
       <?php } ?>
       
      </select>
      <a href="javascript:void(0);" onClick="javascript:$('#position_option').hide();" class="btn btn-mini" title="Close"><i class="icon-remove"></i></a> 
    </div>
    
     <div id="employment_status_option" style="display:none">
    
      <select onChange="javascript:loadEmploymentStatus();" class="curve employee_search_select" name="employment_status" id="employment_status">
        <option>Please Select Status</option>
       <?php foreach($employement_status as $key=>$value) { ?>
        <option value="<?php echo $value->status; ?>"><?php echo $value->status; ?></option>
       <?php } ?>
       
      </select>
      <a href="javascript:void(0);" onClick="javascript:$('#employment_status_option').hide();" class="btn btn-mini" title="Close"><i class="icon-remove"></i></a> 
    </div>

</div>


