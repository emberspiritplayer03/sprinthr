<script>
function checkForm() {
	return true;
}
</script>
<h2><?php echo $title;?></h2>
<form id="payable_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php echo url($action); ?>">
	<input type="submit" value="Download Report" />
</form>
