var DIALOG_CONTENT_HANDLER = '_dialog-box_';
var CONTENT_BODY_ID = '<?php echo CONTENT_BODY_ID;?>';
var MAIN_DIALOG_ID = '_main-dialog_';

$(document).ready(function() {	
	var dialog_length = $('#'+ DIALOG_CONTENT_HANDLER).length;
	if (dialog_length == 0) {
		div = $("<div id='"+ DIALOG_CONTENT_HANDLER +"' style='display:none'>").html('');
		$("body").prepend(div);	
	}
	
});


