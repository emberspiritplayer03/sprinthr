<style>
#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
#sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size:13px; height: 1.5em; }
#sortable li.ui-state-default:hover { cursor:move !important;}
#sortable li a:link, #sortable li a:visited { text-decoration:underline; color:#A02E50;}
#sortable li span.span_page_title {width:95%; display:block; float:left;}
#sortable li span.span_page_name {width:50%; display:block; float:right; text-align:left;}
</style>
<script type="text/javascript">
$(function() {
	$("#sortable").sortable({
		placeholder: 'ui-state-highlight',
		update: function() {
			var BASE_URL = 'http://' + window.location.host;						
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings'; 
			$.post(base_url + "settings/_sort_approvers_level?dbname=<?php echo G_SETTINGS_REQUEST_APPROVERS; ?>&field=level&request_id=<?php echo $request_id; ?>", order, function(o){
					if(o.is_success == 1){
						load_request_approvers_dt(o.request_id);
					}
			},"json");
		}			
	});
	$("#sortable").disableSelection();
});
</script>
<div class="sort_caption">Drag and Drop the item to sort Approvers level</div>
<br />
	<ul id="sortable">
		<?php foreach ($approvers as $a): ?>
				<li class="ui-state-default" id="view_<?php echo $a->getId(); ?>">
                	<span class="span_page_title">
                		<?php 
							if($a->getOverrideLevel() == Settings_Request_Approver::GRANTED){
								$override = '<span class="red" style="font-size:10px;padding-left:10px;">With Override Level</span>';
							}else{$override = '';}
							if($a->getType() == Settings_Request_Approver::POSITION_ID){
								if($a->getPositionEmployeeId() == Settings_Request::APPLY_TO_ALL){
									echo $a->getLevel() . '. ' . 'All Positions' .$override;
								}else{
									$p = G_Job_Finder::findById($a->getPositionEmployeeId());
									if($p){
										echo $a->getLevel() . '. ' . $p->getTitle() . $override;
									}
								}
							}else{
								if($a->getPositionEmployeeId() == Settings_Request::APPLY_TO_ALL){
									echo $a->getLevel() . '. ' . 'All Employees' . $override;
								}else{
									$e = G_Employee_Finder::findById($a->getPositionEmployeeId());
									if($e){
										echo $a->getLevel() . '. ' . $e->getFirstname() . " " . $e->getLastname() . $override;
									}
								}
							}
						?> 
                	</span>
                </li>
		<?php endforeach; ?>
	</ul>
    
<div class="demo">
</div>