<script>

</script>

<div id="audit_search_container">
<div id="auditsearchmain">
	<!-- DONT REMOVE THIS! --><div></div><!-- -->
	<div id="search_wrapper" class="employee_basic_search searchcnt">
        <input name="search" type="text" id="search" class="curve" id="search" size="100" />
        <select class="curve" name="category" id="category">
          <option value="" selected="selected">-- Select --</option>
          <option value="user" >User</option>
          <option value="action">Action</option>
          <option value="event_status">Event Status</option>
          <option value="details">Details</option>
          <option value="audit_date">Audit Date</option>
        </select>
      <button type="submit" class="blue_button"  onclick="javascript:searchAuditTrail();"><i class="icon-search icon-white"></i> Search</button>
	</div>
</div><!-- #auditsearchmain -->
</div>

<div class="btn-group float-right">
    <a title="View All" id="btn_viewall" class="btn btn-small" href="javascript:load_view_all_audit_trail_datatable();">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
</div>

<div class="clear"></div>
<br />
<div class="yui-skin-sam">
	<div id="audit_trail_datatable"></div>
</div>
<div id="audit_wrapper"></div>
<div id="confirmation"></div>
<script>

load_view_all_audit_trail_datatable();

$(function(){
	$('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});	 	 
});
</script>

<input type="hidden" name="applicant_hash" id="applicant_hash"/>