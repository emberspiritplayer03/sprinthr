<script>
var jqAction = jQuery.noConflict(); 
$(function(){

  var oTable = $('#dt').dataTable({   
   "aoColumns": [  
          <?php for($i = 1; $i < count($processed_payroll_data['label_header']); $i++) { ?>             
            {sWidth: '50%',sClass:'dt_small_font'},     
          <?php } ?>
                   
     ],
    'bProcessing':true,
    'bServerSide':false,
    "bAutoWidth": true,
    //"bStateSave": true,
    "bInfo":false,
    "bJQueryUI": true,
    "aaSorting": [[ 0, "asc" ]],
    "sPaginationType": "two_button",
    "fnDrawCallback": function() {
            
        }
  });

  $(".check-all-deduction").removeAttr("checked");
  
  $(".check-all-deduction").change(function(){
    var chkbox_classname = $(this).attr("id");
    if($(this).is(':checked')){
      $("."+chkbox_classname).attr("checked","checked");
    }else{
      $("."+chkbox_classname).removeAttr("checked");
    }
  });
});
</script>
<div class="" style="max-width:980px; overflow-x: auto; overflow-y:hidden; white-space: nowrap; "> 
  <table id="dt" class="formtable display" width="100%">
    <thead>
      <tr>
        <?php foreach($processed_payroll_data['label_header'] as $variable_key => $label) { ?>
          <th>
          <?php if(in_array($label,$processed_payroll_data['col_with_checkbox']) ) { ?>
            <input class="check-all-deduction" id="<?php echo str_replace(" ","-",$label);?>" style="display:inline-flex; vertical-align:sub;" type="checkbox" >
          <?php } ?>
            <label style="display:inline-flex;font-weight:bold;" for="<?php echo str_replace(" ","-",$label);?>"><?php echo $label;?></label>
          </th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($processed_payroll_data['row_data'] as $key => $value):?>
        <tr>
          <?php foreach($processed_payroll_data['label_header'] as $variable_key => $label) { ?>
            <td>
              <?php if(in_array($label,$processed_payroll_data['col_with_checkbox']) && $value[$variable_key] > 0) { ?>
                <input class="<?php echo str_replace(" ","-",$label);?>" name="selected_deduction[]" value="<?php echo $value['employee_id']."/".$variable_key."/".$value[$variable_key];?>" id="<?php echo str_replace(" ","-",$label).$key;?>" style="display:inline-flex; vertical-align:sub;" type="checkbox" >
              <?php } ?>
              <label style="display:inline-flex;" for="<?php echo str_replace(" ","-",$label).$key;?>"><?php echo $value[$variable_key];?></label>
            </td>
          <?php } ?> 
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <input style="display:none;" type="submit" value="Submit" name='submit' id="submitBtn">  
</div>