<script>
	$(function() {
		$("#search_user_group").autocomplete({
		minLength: 2,
		source:  base_url + 'settings/_quick_autocomplete_search_by_user_group',
		select: function( event, ui ) {
				$('#policy_type_wrapper').hide();
				var h_id 				= ui.item.id;
				var h_user_group_id		= ui.item.user_group_id;
				var return_type			= ui.item.return_type;
				var group_name			= ui.item.group_name;
				var group_description	= ui.item.group_description;
		
				var employee_name		= ui.item.employee_name;
				var employment_status	= ui.item.employment_status;
	
				$('#group_info_wrapper').html(group_name);
				$('#group_description_wrapper').html(group_description);
				
				$('#user_info_wrapper').html(employee_name);
				$('#user_status_wrapper').html(employment_status);
			
				$('#h_id').val(h_id);
				$('#h_user_group_id').val(h_user_group_id);
				if(return_type == "<?php echo G_Access_Rights::USER; ?>") {
					h_uar_id = h_id;
					$('#policy_type').val("<?php echo G_Access_Rights::USER; ?>");
					
				} else {
					h_uar_id = h_user_group_id;
					$('#policy_type').val("<?php echo G_Access_Rights::GROUP; ?>");
					$('#group_info_wrapper').val(group_name);
				}
				
				$.post(base_url + 'settings/_load_user_group_user_rights_option',{h_uar_id:h_uar_id},function(o) {
					$('#user_group_access_rights_option_wrapper').html(o);
				});
				
				return false; 
			}
		});
		
		$('#search_user_group').attr("title","Type User or Group to see suggestions.");
		$('#search_user_group').tipsy({gravity: 's'});
		
		
		$('#access_rights_form').ajaxForm({
			success:function(o) {
			},
			dataType:'json'
		});	
	});
</script>
<form id="access_rights_form" name="access_rights_form" method="POST" action="<?php echo url('settings/_insertUserGroupAccessRights'); ?>">
<input class="validate[required] text-input" type="text" name="search_user_group" id="search_user_group" value="" />
<input type="hidden" id="h_id" name="h_id" value="">
<input type="hidden" id="h_user_group_id" name="h_user_group_id" value="">
<input type="hidden" id="policy_type" name="policy_type" value="" />

<br />
<br />

  <table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
  	<td style="width:25%" align="left" valign="middle">Group:</td>
    <td style="width:75%" align="left" valign="middle"><span id="group_info_wrapper"></span></td>
  </tr>
  <tr>
  	<td style="width:25%" align="left" valign="middle">Description:</td>
    <td style="width:75%" align="left" valign="middle"><span id="group_description_info_wrapper"></span></td>
  </tr>
  </table>
  
<br />
<br />

<div id="user_group_access_rights_option_wrapper"></div>
</form>