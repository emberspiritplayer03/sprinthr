<div id="search_helper_holder" >
	<div id="datepicker" style="display:none"></div>
    <div id="department_option" style="display:none">
    
      <select onChange="javascript:loadDepartment();" class="curve employee_search_select" name="department" id="department">
        <option>Please Select Department</option>
       <?php foreach($departments as $key=>$value) { ?>
        <option value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
       <?php } ?>
       
      </select>
      <a href="javascript:void(0);" onClick="javascript:$('#department_option').hide();" class="btn btn-mini" title="Close"><i class="icon-remove"></i></a> 
    </div>

    <div id="section_option" style="display:none">
      <select onChange="javascript:loadSection();" class="curve employee_search_select" name="section" id="section">
        <option>Please Select Section</option>
       <?php foreach($sections as $key=>$value) { ?>
        <option value="<?php echo $value['title']; ?>"><?php echo $value['dept_section']; ?></option>
       <?php } ?>
       
      </select>
      <a href="javascript:void(0);" onClick="javascript:$('#section_option').hide();" class="btn btn-mini" title="Close"><i class="icon-remove"></i></a> 
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


