<table width="1010" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="516" scope="col">Title</th>
          <th width="218" scope="col">Description</th>
          <th width="77" scope="col">&nbsp;</th>
          <th width="181" scope="col">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
       
      <?php 
	  $ctr = 0;
	  $numbering=0;
	   foreach($kpis as $key=>$e) { 
	   $numbering++;
	   ?>
       <tr  onmouseout="javascript:hideDelete('<?php echo $e->id; ?>');" onmouseover="javascript:displayDelete('<?php echo $e->id; ?>');">
          <td colspan="4">
   
		 <script>
            $(function() {
            $("#kpi_edit_form_<?php echo $e->id; ?>").validationEngine({scroll:false});
            $("#kpi_edit_form_<?php echo $e->id; ?>").ajaxForm({
                success:function(o) {
                    if(o==1) {
                        dialogOkBox('Successfully Updated',{});
                        loadPerformanceKpi(<?php echo $e->performance_id; ?>);
                        
                    }else {
                        dialogOkBox(o,{});	
                    }		
                },
                beforeSubmit:function() {
                    showLoadingDialog('Saving...');	
                }
            });
            
            });
            </script>
            
            <div id="kpi_edit_form_dialog_<?php echo $e->id; ?>" style="display:none">
            <form class="kpi_edit_form" id="kpi_edit_form_<?php echo $e->id; ?>" name="form1" method="post" action="<?php echo url('settings/_edit_kpi'); ?>">
            <input type="hidden" name="id" value="<?php echo $e->id; ?>" />
            <input type="hidden" name="performance_id" value="<?php echo $e->performance_id; ?>" />
            <input type="hidden" name="order_by" value="<?php echo $e->order_by; ?>" />
            
              <table class="" width="200" border="0" cellpadding="3" cellspacing="3">
                 <tr>
                  <td width="0" align="right" valign="top">Title:</td>
                  <td valign="top"><input name="title" type="text" id="title" value="<?php echo $e->title; ?>" /></td>
                </tr>
                <tr>
                  <td width="0" align="right" valign="top">Description:</td>
                  <td width="0" valign="top"><textarea name="description" id="description" cols="45" rows="5"><?php echo $e->description; ?></textarea></td>
                </tr>
                
                <tr>
                  <td align="right" valign="top">&nbsp;</td>
                  <td valign="top"><input type="submit" name="button" id="button" value="Update" /></td>
                </tr>
              </table>
            </form>
            </div>   


          </td>
        </tr>
        
        <tr id="kpi_table_wrapper_<?php echo $e->id; ?>"  onmouseout="javascript:hideDelete('<?php echo $e->id; ?>');" onmouseover="javascript:displayDelete('<?php echo $e->id; ?>');">
          <td><bold><?php echo $numbering; ?>)</bold>&nbsp;<a href="javascript:void(0);" onclick="javascript:displayKpiEditForm('<?php echo $e->id; ?>');"><?php echo htmlentities($e->title); ?></a></td>
          <td><?php echo htmlentities($e->description); ?></td>
          <td>&nbsp;</td>
          <td>
          <label class="delete_kpi_nav" id="<?php echo $e->id; ?>" > 
          <a href="javascript:void(0);" onclick="javascript:displayKpiEditForm(<?php echo $e->id; ?>)">Edit</a> <a href="javascript:void(0);" onclick="javascript:loadKpiDeleteDialog(<?php echo $e->id; ?>,<?php echo $e->performance_id; ?>);">Delete</a> 
          <?php if($numbering!=1) { ?>
          <a href="javascript:void(0);" onclick="javascript:moveKpiUp(<?php echo $e->id; ?>,<?php echo $e->performance_id; ?>)">Move Up</a>
          <?php } ?>
          </label></td>
        </tr>
       
       <?php 
	   $ctr++;
	   }
	  if($ctr==0) { ?>
		  <tr>
          <td colspan="4"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
<script>
$(".delete_kpi_nav").hide();

</script>
