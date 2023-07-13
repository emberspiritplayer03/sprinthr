<div id="dialog"></div>
<p>isElementExist('formSampler'); //is element exist  <a href="javascript:isElementExist('formSample');">Is element Exist</a>  <br />
  <br />
  dialogOkBox(message,ok_url,title,height,width,modal,dialog_id) <a href="javascript:loadDialog();">Ok Dialog</a>

<br />
<br />
<br />
dialogYesNoBox(message,yes_process,jump_url,title,height,width,modal,dialog_id)
 <a href="javascript:viewYesNoDialog();">Yes NO Dialog</a><br />
<br />
<br />
<br />
dialogYesNoForm(form_id,message,yes_url, title,height,width,modal,dialog_id) </p><br />
<form id="formSample" class="validationForm" name="form1" method="post" action="<?php echo url('source/_insert'); ?>">
  <input type="text" class="text" name="username" id="username" />
  <br />
  <br />
  <input type="submit" name="button" id="button" value="Submit" />
</form>
<p>&nbsp; </p>

  <script>
 
function loadDialog() {

dialogOkBox('<br>Successfully send');	
}

function viewYesNoDialog() {
	dialogYesNoBox("<br>This is a message");
	
}
var validator = $("#formSample").validate({
		rules: {
			username: {required:true}
		},
		messages: {
			
		},
		submitHandler: function() {
			dialogYesNoForm("formSample","<br>Successfully");

		}, /*errorElement: "div"*/
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});

  </script>