<script>
$(function(){
  $("#btn-move-cutoff-period").click(function(){
    var action = "<?php echo $action;?>";
    $("#new_payroll_period_id").val($("#select-payroll-period").val());
    modalDialogYesNo(action);
  });
});
</script>
<style>.ui-dialog .ui-dialog-content{ padding:10px 10px 0px 10px; }</style>
<table  style="border:none; width:100%" >  
    <tr>
        <td>Cutoff Period</td>
        <td>
            <select id="select-payroll-period">
              <?php foreach($cutoff_arr_data as $key => $value) {  ?>
                <option value="<?php echo $value['cutoff_id'];?>"><?php echo $value['label'];?></option>
              <?php } ?>
            </select>
        </td>             
    </tr>
    <tr>
        <td></td>
        <td><a id="btn-move-cutoff-period" href="javascript:void(0);" class="blue_button">Submit</a></td>
    </tr>
</table>

