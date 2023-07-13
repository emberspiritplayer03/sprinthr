<script>
$(document).ready(function(){ 
  var oTable = $("#dtDepartments").dataTable( {
	   "aoColumns": [   			   			  				
			{"bSortable": false,sWidth: "18%"},								
			{sWidth: "55%",sClass:"dt_small_font dt_center"},
			{sWidth: "30%",sClass:"dt_small_font dt_center"}													
	 	],
		"bProcessing":true, 		
		"bAutoWidth": true,
		"bInfo":true,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],	
		"sPaginationType": "full_numbers",
		"bPaginate": true
	});
});
</script>
<div>
	<?php include_once('includes/bread_crumbs.php'); ?>
</div>
<div>
	<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:addNewDepartment('<?php echo $eid; ?>',2);"><strong>+</strong><b>Add New Department</b></a>
</div>
<br />
<div class="table-container">
<table id="dtDepartments" class="display">
<thead>
  <tr>
	<th valign="top"></th>
    <th valign="top">Department Name</th>   
    <th valign="top"></th>    
  </tr>
</thead>	
    <?php foreach ($departments as $d){?>
      <tr>
        <td valign="middle">
            <div class="i_container">
            	<ul class="dt_icons">                	
                	<li>
                    	<a title="Edit" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:editDepartment('<?php echo Utilities::encrypt($d->getId()); ?>');">
                        	<i class="icon-pencil"></i>
                        </a>
                    </li>
                    <li>
                    	<a title="Add Section" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:addNewSection('<?php echo Utilities::encrypt($d->getId()); ?>',1);">
                        	<i class="icon-plus"></i>
                        </a>
                    </li>                                                          
                    <li>
                    	<a title="Archive" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:archiveCompanyDepartment('<?php echo Utilities::encrypt($d->getId()); ?>');">
                        	<i class="icon-trash"></i>
                        </a>
                    </li> 
                </ul>
            </div>
         </td>         
        <td valign="center" align="left" style="color:#333"><?php echo $d->getTitle();?></td>   
        <td valign="center" align="left" style="color:#333">
        	 <?php				
				      /*$count_groups = G_Company_Structure_Helper::countTotalGroupsIsNotArchiveByParentId($d->getId());				
				      $count_teams  = G_Company_Structure_Helper::countTotalTeamsIsNotArchiveByParentId($d->getId());				
				      $total  		  = $count_groups + $count_teams;*/

              $count_sections = G_Company_Structure_Helper::countTotalSectionsIsNotArchiveByParentId($d->getId()); 
              $title          = "Section(s) : {$count_sections}";       
			     ?>         
           <?php if($count_sections > 0){ ?>
               <a id="btn_groups" title="Click to manage sections" class="btn btn-primary btn-small" href="javascript:void(0);" onclick="javascript:load_department_teams_groups('<?php echo Utilities::encrypt($d->getId()); ?>');"><?php echo $title; ?></a>
           <?php } ?>
        </td>                                  
      </tr>
    <?php } ?>    
</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
$('.display #btn_dept').tipsy({gravity: 's'});
$('.display #btn_groups').tipsy({gravity: 's'});
$('.display #btn_teams').tipsy({gravity: 's'});
</script>