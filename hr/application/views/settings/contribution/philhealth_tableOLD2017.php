<script>
$(function(){
 
    $(".philhealth-import-btn").click(function(){
        importPhilHealthTable();
    });

    var oTable = $('#dtPhilhealth').dataTable( {
       "aoColumns": [   
            {sWidth: '3%',sClass:'dt_small_font dt_center',bSortable:false},                
            {sWidth: '30%',sClass:'dt_small_font'},                         
            {sWidth: '30%',sClass:'dt_small_font dt_center'},
            {sWidth: '50%',sClass:'dt_small_font dt_center'},
            {sWidth: '50%',sClass:'dt_small_font dt_center'},
            {sWidth: '50%',sClass:'dt_small_font dt_center'},
            {sWidth: '50%',sClass:'dt_small_font dt_center'},
            {sWidth: '50%',sClass:'dt_small_font dt_center'}                                                                
        ],
        "bProcessing":true,
        "bServerSide":false,
        "bAutoWidth": true,
        "bInfo":false,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": true
    });


    $(".btn-edit-contribution").click(function(){
        var eid  = $(this).attr("id");
        var type = $("#philhealth_type").val();
        editContribution(eid,type);
    })
});
</script>
<a onclick="javascript:void(0);" class="philhealth-import-btn gray_button float-right" id="" href="javascript:void(0);"><i class="icon-excel icon-custom"></i> Import PhilHealth Table </a>
<br />
<div class="table-container">
<input type="hidden" id="philhealth_type" value="philhealth" />
<table id="dtPhilhealth" class="display">
<thead>
  <tr> 	
    <th valign="top"></th>  
    <th valign="top">Salary Base</th>   
    <th valign="top">Salary Bracket</th>    
    <th valign="top">From Salary</th>
    <th valign="top">To Salary</th>
    <th valign="top">Monthly Contribution</th>   
    <th valign="top">Employee Share</th>   
    <th valign="top">Company Share</th>   
  </tr>
</thead>
    <?php 
		foreach ($philhealth as $p){		
	?>
      <tr>
        <td >
            <a href="javascript:void(0);" class="btn btn-edit-contribution btn-mini" id="<?php echo Utilities::encrypt($p->getId());?>" original-title="Edit">
                <i class="icon-pencil"></i>
            </a>
        </td>       
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($p->getSalaryBase(),2,".",","); ?>
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($p->getSalaryBracket(),2,".",","); ?>
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($p->getFromSalary(),2,".",","); ?>        
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($p->getToSalary(),2,".",","); ?>	
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($p->getMonthlyContribution(),2,".",","); ?>
        </td> 
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($p->getEmployeeShare(),2,".",","); ?>
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($p->getCompanyShare(),2,".",","); ?>
        </td>                     
      </tr>
    <?php } ?>   
</table>
</div>
<script>
//$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>