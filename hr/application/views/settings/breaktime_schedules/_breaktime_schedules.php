<script>
$(function(){
  $(".edit-payroll-settings").click(function(){
     var field = $(this).attr("id");
     editPayrollSettings(field);
  });
});
</script>
<table id="dataTableLicenseList" class="display">
<thead>
  <tr>
    <th valign="top" class="table_header">&nbsp;</th>
    <th valign="top" class="table_header" style="text-align:center;">Value</th>
    <th valign="top" class="table_header"></th>        
  </tr>
</thead>
<tr>
  <td width="38%" valign="top" bgcolor="#FFFFFF">Number of Days</td>
  <td width="18%" valign="top" style="text-align:center;" bgcolor="#FFFFFF"><?php echo $data['number_of_days']; ?></td>
  <td width="8%" valign="top" bgcolor="#FFFFFF"><a id="payroll-num-days" class="btn btn-small edit-payroll-settings" href="javascript:void(0);">Edit</a></td>
</tr>
</table>