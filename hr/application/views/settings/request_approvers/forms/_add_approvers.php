<style>
.textboxlist{width:309px;display:inline-block;}
</style>
<script>
$(function(){  
  var t<?php echo $approvers_level; ?> = new $.TextboxList('#approver_<?php echo $approvers_level; ?>', {unique: true,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}

    }
  }});

  $(".remove-append-approvers").click(function(){    
    var dataIndex = $(this).attr("data-index");
    var dataLevel = $('.tr-approvers').length;  
    for(x = dataIndex; x<=dataLevel; x++){        
      $("div#approvers-level-" + x).remove();
    } 
  }); 
  
});
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="2">
<tr class="tr-approvers">
      <td style="width:15%" align="left" valign="middle">Approver (Level <?php echo $approvers_level; ?>)</td>
      <td style="width:15%" align="left" valign="middle">: 
        <input class="validate[required] text-input" type="text" name="approvers[<?php echo $approvers_level; ?>]" id="approver_<?php echo $approvers_level; ?>" value="" />
        <a href="javascript:void(0);" data-index=<?php echo $approvers_level; ?> class="remove-append-approvers"><i class="icon-remove-sign"></i>Remove</a>
      </td>
</tr>
</table>