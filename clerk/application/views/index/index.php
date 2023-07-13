Lorem Ipsum

<script>
	var dialog_id = '#' + MAIN_DIALOG_ID;			
			$('#'+ DIALOG_CONTENT_HANDLER).html('<div id="'+ MAIN_DIALOG_ID +'">hehehe</div>');
			var $dialog = $(dialog_id);
			$dialog.dialog({
				title: 'Changed Schedules',
				width: 450,
				height: 300,
				modal: true,
				resizable: true,
				buttons: {
					'Ok': function() {
						$dialog.dialog('destroy');
						$(dialog_id).remove();
					}
				},
				close: function() {
					$dialog.dialog('destroy');
					$(dialog_id).remove();
				}				
			});	
</script>