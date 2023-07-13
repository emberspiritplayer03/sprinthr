
<script type="text/javascript">
	$(document).ready(function() {
		$('#department_list_dt').dataTable( {
			//"sScrollY": "200px",
			"bScrollCollapse": true,
			"bPaginate": true,
			"sPaginationType": "full_numbers",
			"bJQueryUI": true,
			"aoColumnDefs": [
				{ "sWidth": "10%", "aTargets": [ -1 ] }
			]
		} );
	} );
</script>
 <div id="department_dropdown_wrapper_startup" style="display:none;">
  <select class="validate[required] select_option" name="department_id_startup" id="department_id_startup">
      <option value="" selected="selected">-- Select Department --</option>
        <?php foreach($departments as $key=>$value) { ?>
            <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      <!--<option value="add">Add Department...</option>-->
  </select>           
 </div> 
 <div class="form_separator"></div>
 <div class="inline" style="padding-top:5px;">
 	<a href="javascript:void(0);" class="btn btn-small" onclick="javascript:checkForAddDepartmentStartup('add');"><i class="icon-plus"></i> Add Department</a>
 </div>
 <div class="div_table_border section_container">
    <table  width="100%" border="0" cellpadding="0" cellspacing="0" class="formtable" id="department_list_dt">
        <thead>
            <tr>
                <th width="40%" align="center" valign="middle">Department</th>
                <th width="60%" align="center" valign="middle">Description</th>
            </tr>
        </thead>
         <tbody>
            <?php if($departments){ foreach($departments as $content): ?>
                    <tr>
                        <td width="40%" align="left" valign="top"><?php echo $content->getTitle(); ?></td>
                        <td width="60%" align="left" valign="top"><?php echo $content->getDescription(); ?></td>
                     </tr>
            <?php endforeach; }?>
         </tbody>
     </table>
 </div>