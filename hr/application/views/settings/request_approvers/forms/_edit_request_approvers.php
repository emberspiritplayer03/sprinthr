<div class="script-container"></div>
<script>
$(function(){  
  <?php echo $ini_script; ?>  
  $(".remove-append-approvers").click(function(){  	
  	var dataIndex = $(this).attr("data-index");
  	var dataLevel = $('.tr-approvers').length;  
  	for(x = dataIndex; x<=dataLevel; x++){ 	  		
  		$("div#approvers-level-" + x).remove();
  	}	
  });
});
</script>

<?php 
	foreach($level as $key => $value){ 
		foreach($value as $subkey => $subvalue){	
			if( $subkey == 'level' && $subvalue['level'] != $approvers_level ){
				$approvers_level = $subvalue['level'];
?>
				<div id="approvers-level-<?php echo $approvers_level; ?>">
				<table width="100%" border="0" cellspacing="1" cellpadding="2">
				<tr class="tr-approvers tr-append-level-<?php echo $approvers_level; ?>">
				      <td style="width:15%" align="left" valign="middle">Approver (Level <?php echo $approvers_level; ?>)</td>
				      <td style="width:15%" align="left" valign="middle">: 
				        <input class="validate[required] text-input" type="text" name="approvers[<?php echo $approvers_level; ?>]" id="approver_<?php echo $approvers_level; ?>" value="" />
				        <?php if( $approvers_level > 1 ){ ?>
				        	<a href="javascript:void(0);" data-index=<?php echo $approvers_level; ?> class="remove-append-approvers"><i class="icon-remove-sign"></i>Remove</a>				        	
				        <?php } ?>
				      </td>
				</tr>
				</table>
				</div>
<?php
			}
		}
	} 
?>