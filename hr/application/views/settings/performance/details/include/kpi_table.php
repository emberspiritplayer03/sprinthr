<table class="formtable" width="100%"  border="0">
      <thead>
        <tr>
          <th width="26%" scope="col">Title</th>
          <th width="44%" scope="col">Description</th>
          <th alig width="30%" scope="col">&nbsp;</th>
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
          <td colspan="3" class="no-padding no-border">
   
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
                <div id="form_main" class="popup_form inner_form">
                    <form class="kpi_edit_form" id="kpi_edit_form_<?php echo $e->id; ?>" name="form1" method="post" action="<?php echo url('settings/_edit_kpi'); ?>">
                    <input type="hidden" name="id" value="<?php echo $e->id; ?>" />
                    <input type="hidden" name="performance_id" value="<?php echo $e->performance_id; ?>" />
                    <input type="hidden" name="order_by" value="<?php echo $e->order_by; ?>" />
                        <div id="form_default">
                          <table width="100%">
                             <tr>
                              <td class="field_label">Title:</td>
                              <td><input name="title" type="text" id="title" value="<?php echo $e->title; ?>" /></td>
                            </tr>
                            <tr>
                              <td class="field_label">Description:</td>
                              <td><textarea style="width:280px; min-width:280px;" name="description" id="description"><?php echo $e->description; ?></textarea></td>
                            </tr>
                          </table>
                        </div>
                        <div id="form_default" class="form_action_section">
                          <table width="100%">
                            <tr>
                              <td class="field_label">&nbsp;</td>
                              <td><input class="blue_button" type="submit" name="button" id="button" value="Update" /></td>
                            </tr>
                          </table>
                        </div>
                    </form>
                </div>
            </div>   


          </td>
        </tr>
        
        <tr id="kpi_table_wrapper_<?php echo $e->id; ?>"  onmouseout="javascript:hideDelete('<?php echo $e->id; ?>');" onmouseover="javascript:displayDelete('<?php echo $e->id; ?>');">
          <td><?php echo $numbering; ?>)&nbsp;<a href="javascript:void(0);" onclick="javascript:displayKpiEditForm('<?php echo $e->id; ?>');"><b><?php echo htmlentities($e->title); ?></b></a></td>
          <td><?php echo htmlentities($e->description); ?></td>
          <td>
          <!--<label class="delete_kpi_nav" id="<?php echo $e->id; ?>" style="float:left;">-->
          <a class="link_option" href="javascript:void(0);" onclick="javascript:displayKpiEditForm(<?php echo $e->id; ?>)" title="Edit"><i class="icon-edit"></i> Edit</a> <a class="link_option" href="javascript:void(0);" onclick="javascript:loadKpiDeleteDialog(<?php echo $e->id; ?>,<?php echo $e->performance_id; ?>);" title="Delete"><i class="icon-trash"></i> Delete</a> 
          <?php if($numbering!=1) { ?>
          <a class="link_option" href="javascript:void(0);" onclick="javascript:moveKpiUp(<?php echo $e->id; ?>,<?php echo $e->performance_id; ?>)" title="Move Up"><i class="icon-arrow-up"></i> Move Up</a>
          <?php } ?>
         <!--</label>--></td>
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
