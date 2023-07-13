<div id="dialog"></div>
<p>dialogOkBox(dialog_id,title,height,width,modal,ok_url,message) <a href="javascript:loadDialog();">Ok Dialog</a>

<br />
<br />
<br />
 dialogYesNoBox(dialog_id,yes_url,go_url,title,height,width,modal,message)
 <a href="javascript:viewYesNoDialog();">Yes NO Dialog</a><br />
<br />
<br />
<br />
dialogYesNoForm(dialog_id,form_id,yes_url, title,height,width,modal,message) </p><br />
<form id="formSample" class="validationForm" name="form1" method="post" action="<?php echo url('source/_insert'); ?>">
  <input type="text" class="text" name="username" id="username" />
  <br />
  <br />
  <input type="submit" name="button" id="button" value="Submit" />
</form>
<p>&nbsp; </p>

  <script>
function loadDialog() {
dialogOkBox('#dialog','sample',200,250,true,'',"<br>Successfully send");	
}

function viewYesNoDialog() {
	dialogYesNoBox('#dialog','source/_edit',"","test",200,300,true,"<br>This is a message");
	
}
var validator = $("#formSample").validate({
		rules: {
			username: {required:true}
		},
		messages: {
			
		},
		submitHandler: function() {
			dialogYesNoForm("#dialog","formSample","", "test",300,400,true,"<br>Successfully");

		}, /*errorElement: "div"*/
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});

  </script>