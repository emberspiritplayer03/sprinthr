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
<div><a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:addNewGroupTeam('<?php echo $eid; ?>',2);"><strong>+</strong><b>Add New Groups / Teams</b></a></div>
<br />
<div class="table-container">
<table id="dtDepartments" class="display">
<thead>
  <tr>
	<th valign="top"></th>
    <th valign="top">Group / Team</th>   
    <th valign="top"></th>    
  </tr>
</thead>	
    <?php foreach ($data as $d){?>
      <tr>
        <td valign="middle">
            <div class="i_container">
            	<ul class="dt_icons">                	
                	<li>
                    	<a title="Edit" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:editTeamGroup('<?php echo Utilities::encrypt($d->getId()); ?>','<?php echo $eid; ?>');">
                        	<i class="icon-pencil"></i>
                        </a>
                    </li>
                    <li>
                    	<a title="Add Group / Team" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:addSubGroupTeam('<?php echo Utilities::encrypt($d->getId()); ?>','<?php echo $eid; ?>');">
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
        <td valign="center" align="left" style="color:#333"><?php echo $d->getTitle();?> <span class="red bold" style="vertical-align:super;font-size:10px;">(<?php echo $d->getType(); ?>)</span></td>   
        <td valign="center" align="left" style="color:#333">
        	<?php
				//$count_branches    = G_Company_Structure_Helper::countTotalBranchesIsNotArchiveByParentIdAndCompanyBranchId($d->getParentId(),$d->getId());
				//$count_departments = G_Company_Structure_Helper::countTotalDepartmentsIsNotArchiveByParentIdAndCompanyBranchId($d->getParentId(),$d->getId());				
				$count_groups = G_Company_Structure_Helper::countTotalGroupsIsNotArchiveByParentId($d->getId());				
				$count_teams  = G_Company_Structure_Helper::countTotalTeamsIsNotArchiveByParentId($d->getId());				
				$total 		  = $count_groups + $count_teams;
			?>
           <!-- <span class="label label-info">Branches : <?php //echo $count_branches; ?></span>-->
           <!--<a id="btn_dept" title="Click to manage departments" class="btn btn-primary btn-small btn_dept" href="javascript:void(0);" onclick="javascript:load_branch_departments('<?php //echo Utilities::encrypt($d->getId()); ?>');">
           		Departments : <?php //echo $count_departments; ?>
           </a>-->
           <?php if($total > 0){ ?>
               <a id="btn_groups" title="Click to manage teams / groups" class="btn btn-primary btn-small" href="javascript:void(0);" onclick="javascript:load_department_teams_groups('<?php echo Utilities::encrypt($d->getId()); ?>');">
               	   <?php if($count_groups > 0){ ?>
	                   Group(s) : <?php echo $count_groups; ?>
                   <?php } ?>
                   <?php if($count_teams > 0){ ?>
                    	Team(s) : <?php echo $count_teams; ?>
                    <?php } ?>                   
               </a>
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