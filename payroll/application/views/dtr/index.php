<style type="text/css">
body.template_blank { background:#999999;}
span.dtr_label { font-size:66px; line-height:100px;}
input.dtr_textbox, input.dtr_button {font-size: 66px; height: 100px; line-height: 100px; width: 450px;} 
input.dtr_button { width:auto;}
</style>

<script>
function selectText() {
	$('#employee_code').select();
}

function refresh_list() {
	$.post(base_url + 'dtr/refresh', {}, function(data){
		$('#record_handler').html(data);
	});	
}

$(document).ready(function(e) {
    refresh_list();
});
$('#dtr_form').ajaxForm({
	success:function(o) {
		if (o.has_error) {
			$('#message').html('<span style="color:red">Please try again</span>');
			$('#message').show();
			$('#employee_code').select();
		} else {
			$('#message').html('<span style="color:blue">Success!</span>');
			$('#message').show();
			$('#employee_code').val('');
			$('#employee_code').select();
			refresh_list();
		}
	},
	dataType:'json',
	beforeSubmit: function() {
		var code = $('#employee_code').val();
		if (code != '') {
			return true;	
		}
		$('#message').hide();
		return false;
	}
});
</script>

<form id="dtr_form" name="dtr_form" action="<?php echo url('dtr/punch');?>">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center"><table width="269" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td width="29"><span class="dtr_label">ID:</span></td>
        <td width="144"><input onfocus="selectText()" autofocus="autofocus" type="text" id="employee_code" name="employee_code" class="dtr_textbox" /></td>
        <td width="66"><input type="submit" value="Enter" class="dtr_button" /></td>
        </tr>
      </table></td>
  </tr>
  </table>
</form>
<div id="message" align="center" style="font-size:50px; background-color:yellow"></div>
<br />

<div id="record_handler"></div>