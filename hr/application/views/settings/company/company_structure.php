<script>
$(document).ready(function(){ 
  var oTable = $("#dtBranches").dataTable( {
	   "aoColumns": [   			   			  				
			{"bSortable": false,sWidth: "12%"},								
			{sWidth: "38%",sClass:"dt_small_font dt_center"},
			{sWidth: "15%",sClass:"dt_small_font dt_center"}													
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
<!-- <div>
	<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:addBranch(<?php echo $company_structure_id; ?>);"><strong>+</strong><b>Add New Branch</b></a>
</div> -->
<br />
<div class="table-container">
<table id="dtBranches" class="display">
<thead>
  <tr>
	<th valign="top"></th>
    <th valign="top">Branch Name</th>   
    <th valign="top"></th>    
  </tr>
</thead>	
    <?php foreach ($branches as $b){?>
      <tr>
        <td valign="middle">
            <div class="i_container">
            	<ul class="dt_icons">                	
                	<li>
                    	<a title="Edit" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:editBranch('<?php echo Utilities::encrypt($b->getId()); ?>');">
                        	<i class="icon-pencil"></i>
                        </a>
                    </li>  
<!--                     <li>
                    	<a title="Add Department" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:addNewDepartment('<?php echo Utilities::encrypt($b->getId()); ?>',1);">
                        	<i class="icon-plus"></i>
                        </a>
                    </li>  -->                                                       
                    <li>
                    	<a title="Archive" id="tipsy" class="btn btn-mini" href="javascript:void(0);" onclick="javascript:archiveCompanyBranch('<?php echo Utilities::encrypt($b->getId()); ?>');">
                        	<i class="icon-trash"></i>
                        </a>
                    </li> 
                </ul>
            </div>
         </td>         
        <td valign="center" align="left" style="color:#333"><?php echo $b->getName();?></td>   
        <td valign="center" align="left" style="color:#333">        	
			<?php
                //$count_branches    = G_Company_Structure_Helper::countTotalBranchesIsNotArchiveByParentIdAndCompanyBranchId($b->getCompanyStructureId(),$b->getId());
                $count_departments = G_Company_Structure_Helper::countTotalDepartmentsIsNotArchiveByParentIdAndCompanyBranchId($b->getCompanyStructureId(),$b->getId());				
                //$count_groups      = G_Company_Structure_Helper::countTotalGroupsIsNotArchiveByParentIdAndCompanyBranchId($b->getCompanyStructureId(),$b->getId());				
                //$count_teams       = G_Company_Structure_Helper::countTotalTeamsIsNotArchiveByParentIdAndCompanyBranchId($b->getCompanyStructureId(),$b->getId());				
            ?>
           <!-- <span class="label label-info">Branches : <?php //echo $count_branches; ?></span>-->
           <?php if($count_departments > 0){ ?>
               <a id="btn_dept" title="Click to manage departments" class="btn btn-primary btn-small btn_dept" href="javascript:void(0);" onclick="javascript:load_branch_departments('<?php echo Utilities::encrypt($b->getId()); ?>');">
                    Department(s) : <?php echo $count_departments; ?>
               </a>
           <?php } ?>
           <!--<?php //if($count_groups > 0){ ?>
               <a id="btn_groups" title="Click to manage groups" class="btn btn-primary btn-small" href="#">
                   Groups : <?php //echo $count_groups; ?>
               </a>
           <?php //} ?>
           <?php //if($count_teams > 0){ ?>
               <a id="btn_teams" title="Click to manage teams" class="btn btn-primary btn-small" href="#">
                   Teams : <?php //echo $count_teams; ?>
               </a>
           <?php //} ?>-->             
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