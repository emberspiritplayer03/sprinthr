<form action="<?php echo url('source/_ajax_form'); ?>" method="post" enctype="multipart/form-data" name="form1" id="myForm">
  <table width="379" border="0" cellpadding="4" cellspacing="3">
    <tr>
      <td width="123">Firstname</td>
      <td width="571"><input class="curve" type="text" name="firstname" id="firstname" /></td>
    </tr>
    <tr>
      <td>Lastname</td>
      <td><input class="curve" type="text" name="lastname" id="lastname" /></td>
    </tr>
    <tr>
      <td>Middlename</td>
      <td><input class="curve" type="text" name="middlename" id="middlename" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input class="" type="file" name="fileField" id="fileField" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input class="button_j" type="submit" name="button" id="button" value="Submit" /></td>
    </tr>
  </table>
</form>


<script>
$(document).ready(function() { 
			
	$('#myForm').ajaxForm({
		success:function(o) {	
			alert(o);	
		}
	});
			
}); 
</script>

<strong>INCLUDE:</strong><br />
Jquery::loadJqueryFormSubmit();<br />
<br />
<strong>Script:</strong><br />
<textarea name="textarea" id="textarea" cols="80" rows="13" wrap="soft">
 <script>
$(document).ready(function() { 
			
	$('#myForm').ajaxForm({
		success:function(o) {	
			alert(o);	
		}
	});
			
}); 
</script>
</textarea>
<br />
<strong>Controller:</strong><br />
<textarea name="textarea2" id="textarea2" cols="80" rows="6" wrap="soft">
function _ajax_form() 
{
    print_r($_POST);	
}
</textarea>
<br />
<strong>View:</strong><br />
<textarea name="textarea3" id="textarea3" cols="80" rows="13" wrap="soft">
<form action="<?php echo url('source/_ajax_form'); ?>" method="post" enctype="multipart/form-data" name="form1" id="myForm">
  <table width="379" border="0" cellpadding="4" cellspacing="3">
    <tr>
      <td width="123">Firstname</td>
      <td width="571"><input class="curve" type="text" name="firstname" id="firstname" /></td>
    </tr>
    <tr>
      <td>Lastname</td>
      <td><input class="curve" type="text" name="lastname" id="lastname" /></td>
    </tr>
    <tr>
      <td>Middlename</td>
      <td><input class="curve" type="text" name="middlename" id="middlename" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input class="" type="file" name="fileField" id="fileField" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input class="button_j" type="submit" name="button" id="button" value="Submit" /></td>
    </tr>
  </table>
</form>	
</textarea>
