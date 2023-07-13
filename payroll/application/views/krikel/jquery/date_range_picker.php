<script language="javascript">

$(function() {
	 $('#rangeA').daterangepicker({arrows:true,dateFormat: 'yy-mm-dd'}); 
	 $('#rangeB').daterangepicker( {
	  arrows:true,
	  presetRanges: [
		{text: 'My Range', dateStart: '03/07/08', dateEnd: 'Today'}
			]
	 } );
    
});



</script>
<table width="519" border="0">
  <tr>
    <td>Date</td>
    <td><input type="text" id="rangeA" name="date_range_picker" /></td>
  </tr>
  <tr>
    <td>Set Range</td>
    <td><input type="text" id="rangeB" name="rangeB" /></td>
  </tr>
</table>
<br />
<br />
<strong>INCLUDE:</strong><br />
Jquery::loadDateRangePicker();<br />
<br />
<strong>Views:</strong><br />
<textarea name="textarea" id="textarea" cols="80" rows="13" wrap="soft">
<script language="javascript">

$(function() {
	 $('#rangeA').daterangepicker({arrows:true,dateFormat: 'yy-mm-dd'}); 
	 $('#rangeB').daterangepicker( {
	  arrows:true,
	  presetRanges: [
		{text: 'My Range', dateStart: '03/07/08', dateEnd: 'Today'}
			]
	 } );
    
});



</script>
<table width="519" border="0">
  <tr>
    <td>Date</td>
    <td><input type="text" id="rangeA" name="date_range_picker" /></td>
  </tr>
  <tr>
    <td>Set Range</td>
    <td><input type="text" id="rangeB" name="rangeB" /></td>
  </tr>
</table>
</textarea>
