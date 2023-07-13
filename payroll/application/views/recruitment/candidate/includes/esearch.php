<div id="search_helper_holder">
	<div id="datepicker" style="display:none"><a href="javascript:void(0);" onClick="javascript:$('#datepicker').hide();">close</a> </div>
	
    <div id="position_option" style="display:none">
    <select onChange="javascript:loadPositionOption();" name="position_applied" id="position_applied">
      <option value="">-- Select Applied Position --</option>
   	  <?php foreach($positions as $key=>$value) { ?>
	  <option value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
      <?php } ?>
	</select><a href="javascript:void(0);" onClick="javascript:$('#position_option').hide();">close</a> 
    </div>
    
    <div id="status_option" style="display:none">
        <select onChange="javascript:loadStatusOption();" name="status" id="status">
        <option value="">-- Select Status --</option>
       
        <option value="Pending">Pending</option>
        <option value="Interview">Interview</option>
        <option value="Decline">Declined By Applicant</option>
        <option value="Rejected">Rejected</option>
        <option value="Hired">Hired</option>
      
        </select> <a href="javascript:void(0);" onClick="javascript:$('#status_option').hide();">close</a> 
    </div>
    
 </div>