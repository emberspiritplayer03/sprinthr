<script>
$("#bank_form #bank_date_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#bank_form #bank_date_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
function checkForm() {
	var date_from = $("#bank_form #bank_date_from").val();
	var date_to = $("#bank_form #bank_date_to").val();
	if (date_from == '' || date_to == '') {
		return false;	
	} else {
		return true;	
	}
}
</script>
<h2><?php echo $title;?></h2>
<form id="bank_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php echo url($action); ?>">
  <table width="221" border="0" cellpadding="3" cellspacing="3">
    <tr>
      <td width="100" valign="top"><strong>From:
      </strong><input type="text" name="date_from" id="bank_date_from" style="width:100px" /></td>
      <td width="251" valign="top"><strong>To:</strong>
      <input type="text" name="date_to" id="bank_date_to" style="width:100px" /></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><input type="submit" value="Download Report" /></td>
    </tr>
  </table>
</form>
