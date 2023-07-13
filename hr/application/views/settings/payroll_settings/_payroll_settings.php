<script>
$(function(){
  $(".edit-payroll-settings").click(function(){
     var dataKey = $(this).attr("data-key");
     editPayrollSettings(dataKey);
  });
});
</script>
<?php foreach( $variables as $key => $variable ){ ?>
  <table id="dataTableLicenseList" class="display">
    <thead>
      <tr>
        <th valign="top" class="table_header">&nbsp;</th>
        <th valign="top" class="table_header" style="text-align:center;">Value</th>
        <th valign="top" class="table_header"></th>        
      </tr>
    </thead>
    <?php foreach($variable as $subKey => $details){ ?>
      <tr>
        <td width="38%" valign="top" bgcolor="#FFFFFF"><?php echo $details['description']; ?></td>
        <td width="28%" valign="top" style="text-align:center;" bgcolor="#FFFFFF"><?php echo $details['value']; ?></td>
        <td width="8%" valign="top" bgcolor="#FFFFFF"><a id="payroll-num-days" class="btn btn-small edit-payroll-settings" data-key="<?php echo $subKey; ?>" href="javascript:void(0);">Edit</a></td>
      </tr>
    <?php } ?>
  </table>
<?php } ?>