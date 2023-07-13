<script>

	$(function() {
		$( "#datepicker" ).datepicker({
			onSelect: function(dateText, inst) { 
					$("#search").val($("#search").val()+dateText);
					$("#search").focus();
					$("#search").setCursorToTextEnd();
					$("#datepicker").hide();
  			},
			dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true
	
		});
		
		$("#quick_search").autocomplete({
		minLength: 2,
		source:  base_url + 'employee/_quick_autocomplete',
		select: function( event, ui ) {
				
				$.post(base_url+"employee/_generate_username",{employee_id:ui.item.id},
				function(o){
					$("#quick_search").val('');
					if(o==1) {
						dialogOkBox("Employee is already registered",{});
						$("#employee_name").val('');
						$("#password").val('');
						$("#username").val('');
						$("#confirm_password").val('');

					}else {
						$("#employee_name" ).val(ui.item.label);
						$("#employee_id").val(ui.item.id);
						$("#username").val(o);	
						$.post(base_url+"employee/_generate_password",{employee_id:ui.item.id},
						function(o){
							$("#password").val(o);
						});
					}
					
				});
				
				return false;
			}
		});
		
		$("#search_by_employee_code").autocomplete({
		minLength:1,
		source:  base_url + 'employee/_quick_autocomplete_search_by_employee_code',
		select: function( event, ui ) {
				$( "#employee_name" ).val(ui.item.label);
				$("#employee_id").val(ui.item.id);
				$("#search_by_employee_code").val('');
				$.post(base_url+"employee/_generate_username",{employee_id:ui.item.id},
				function(o){
						
					if(o==1) {
						dialogOkBox("Employee is already registered",{});
						$("#employee_name").val('');
						$("#password").val('');
						$("#username").val('');
						$("#confirm_password").val('');
			
					}else {
						$("#employee_name" ).val(ui.item.label);
						$("#employee_id").val(ui.item.id);
						$("#username").val(o);	
						$.post(base_url+"employee/_generate_password",{employee_id:ui.item.id},
						function(o){
							$("#password").val(o);
						});
					}
				});
				
				return false;
			}
		});
	});

</script>

<?php if($_GET['add_account']=='true') { ?>
		<div id="account_form_wrapper" >
<?php }else { ?>
		<div id="account_form_wrapper" style="display:none" >
<?php } ?>
<?php include 'form/add_account.php'; ?>
</div>
<div align="right">

</div>
<?php include 'includes/search.php'; ?>


<div id="account_edit_form_wrapper" style="display:none"></div>

<div id="account_list_wrapper">
    <div class="yui-skin-sam">
      <div id="account_datatable"></div>
    </div>
</div>


<script>
load_employee_account_datatable();
</script>
<input type="hidden" name="employee_hash" id="employee_hash"/>
