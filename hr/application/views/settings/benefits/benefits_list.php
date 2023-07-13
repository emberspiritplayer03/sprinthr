<script>
$(document).ready(function() {
	$('#dataTableLicenseList').dataTable( {
		//"sScrollY": 200,
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
<table id="dataTableLicenseList" class="display">
<thead>
  <tr>
    <th valign="top" class="table_header">&nbsp;</th>
    <th valign="top" class="table_header">Type</th>
    <th valign="top" class="table_header">Description</th>        
  </tr>
</thead>
<?php foreach ($licenses as $l):?>
  <tr>
    <td width="8%" valign="top" bgcolor="#FFFFFF">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>  
              <td valign="top">
                <a href="javascript:void(0);" onclick="javascript:load_edit_license(<?php echo $l->getId(); ?>);" style="display:inline-block;">
                    <label  title="Edit" id="edit" class="ui-icon ui-icon-pencil" style="cursor:pointer;"></label>
                </a>
              </td>                     
              <td valign="top">
                <a href="javascript:void(0);" onclick="javascript:load_delete_license(<?php echo $l->getId(); ?>);" style="display:inline-block;">
                    <label  title="Delete" id="delete" class="ui-icon ui-icon-trash" style="cursor:pointer;"></label>
                </a>
              </td>                                        
            </tr>
        </table>
    </td>
    <td width="30%" height="30%" valign="top" bgcolor="#FFFFFF" style="color:#0081B6;">  
    <?php echo $l->getLicenseType(); ?>
    </td> 
    <td width="72%" height="30%" valign="top" bgcolor="#FFFFFF" style="color:#0081B6;">  
    <?php echo $l->getDescription(); ?>
    </td>    
  </tr>
<?php endforeach;?>
<tfoot>
  <tr>
  <th valign="top" class="table_header">&nbsp;</th>
  <th valign="top" class="table_header">Type</th>     
  <th valign="top" class="table_header">Description</th>     
  </tr>
</tfoot>
</table>
<script>
$('.display #edit').tipsy({gravity: 's'});
$('.display #delete').tipsy({gravity: 's'});
</script>