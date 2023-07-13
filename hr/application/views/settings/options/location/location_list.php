<script>
$(document).ready(function() {
	$('#dataTableLocationList').dataTable( {
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
<table id="dataTableLocationList" class="display">
<thead>
  <tr>
    <th valign="top" class="table_header">&nbsp;</th>
    <th valign="top" class="table_header">Code</th>
    <th valign="top" class="table_header">Location</th>    
  </tr>
</thead>
<?php foreach ($locations as $l):?>
  <tr>
    <td width="5%" valign="top" bgcolor="#FFFFFF">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>  
              <td valign="top">
                <a href="javascript:void(0);" onclick="javascript:load_edit_location(<?php echo $l->getId(); ?>);" style="display:inline-block;">
                    <label  title="Edit" id="edit" class="ui-icon ui-icon-pencil" style="cursor:pointer;"></label>
                </a>
              </td>                     
              <td valign="top">
                <a href="javascript:void(0);" onclick="javascript:load_delete_location(<?php echo $l->getId(); ?>);" style="display:inline-block;">
                    <label  title="Delete" id="delete" class="ui-icon ui-icon-trash" style="cursor:pointer;"></label>
                </a>
              </td>                                        
            </tr>
        </table>
    </td>
    <td width="5%" height="30%" valign="top" bgcolor="#FFFFFF" style="color:#0081B6;">  
    <?php echo $l->getCode(); ?>
    </td>
    <td width="92%" height="30%" valign="top" bgcolor="#FFFFFF" style="color:#0081B6;">  
    <?php echo $l->getLocation(); ?>
    </td>   
  </tr>
<?php endforeach;?>
<tfoot>
  <tr>
  <th valign="top" class="table_header">&nbsp;</th>
  <th valign="top" class="table_header">Code</th>
  <th valign="top" class="table_header">Location</th>     
  </tr>
</tfoot>
</table>
<script>
$('.display #edit').tipsy({gravity: 's'});
$('.display #delete').tipsy({gravity: 's'});
</script>