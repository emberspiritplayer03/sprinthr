<script>
$(document).ready(function() {
	$('#dataTableDependentList').dataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": true,
		"bSort": true,
		"bInfo": false,		
		"bScrollCollapse": false
	} );
} );
</script>
<table id="dataTableDependentList" class="display">
<thead>
  <tr>
    <th valign="top" class="table_header">&nbsp;</th>
    <th valign="top" class="table_header">Dependent Relationship</th>    
  </tr>
</thead>
<?php foreach ($dependents as $d):?>
  <tr>
    <td width="8%" valign="top" bgcolor="#FFFFFF">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>  
              <td valign="top">
                <a href="javascript:void(0);" onclick="javascript:load_edit_dependent(<?php echo $d->getId(); ?>);" style="display:inline-block;">
                    <label  title="Edit" id="edit" class="ui-icon ui-icon-pencil" style="cursor:pointer;"></label>
                </a>
              </td>                     
              <td valign="top">
                <a href="javascript:void(0);" onclick="javascript:load_delete_dependent(<?php echo $d->getId(); ?>);" style="display:inline-block;">
                    <label  title="Delete" id="delete" class="ui-icon ui-icon-trash" style="cursor:pointer;"></label>
                </a>
              </td>                                        
            </tr>
        </table>
    </td>
    <td width="92%" height="30%" valign="top" bgcolor="#FFFFFF" style="color:#0081B6;">  
    <?php echo $d->getRelationship(); ?>
    </td>   
  </tr>
<?php endforeach;?>
<tfoot>
  <tr>
  <th valign="top" class="table_header">&nbsp;</th>
    <th valign="top" class="table_header">Dependent Relationship</th>     
  </tr>
</tfoot>
</table>
<script>
$('.display #edit').tipsy({gravity: 's'});
$('.display #delete').tipsy({gravity: 's'});
</script>