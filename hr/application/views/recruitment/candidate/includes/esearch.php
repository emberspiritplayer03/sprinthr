<div id="search_helper_holder">
	<div id="datepicker" style="display:none"><a style="float:right; position:relative; right:-30px; top:3px;" href="javascript:void(0);" onClick="javascript:$('#datepicker').hide();" title="Close" class="btn btn-mini"><i class="icon-remove"></i></a> </div>
	
    <div id="position_option" style="display:none">
    <select onChange="javascript:loadPositionOption();" name="position_applied" id="position_applied">
      <option value="">-- Select Applied Position --</option>
   	  <?php foreach($positions as $key=>$value) { ?>
	  <option value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
      <?php } ?>
	</select>&nbsp;<a href="javascript:void(0);" onClick="javascript:$('#position_option').hide();" title="Close" class="btn btn-mini"><i class="icon-remove"></i></a> 
    </div>
    
    <div id="status_option" style="display:none">
        <select onChange="javascript:loadStatusOption();" name="status" id="status">
        <option value="">-- Select Status --</option>
       
        <option value="Pending">Pending</option>
        <option value="Interview">Interview</option>
        <option value="Decline">Declined By Applicant</option>
        <option value="Rejected">Rejected</option>
        <option value="Hired">Hired</option>
      
        </select>&nbsp;<a href="javascript:void(0);" onClick="javascript:$('#status_option').hide();" title="Close" class="btn btn-mini"><i class="icon-remove"></i></a> 
    </div>
    
 </div>