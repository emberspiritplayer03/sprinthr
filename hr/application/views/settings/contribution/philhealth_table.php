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
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
            {sWidth: '50%',sClass:'dt_small_font dt_center'}                                                    
        ],
        'bProcessing':false,        
        "bAutoWidth": true,
        "bInfo":false,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": false
    });

    $(".btn-edit-contribution").click(function(){
        var eid  = $(this).attr("id");
        var type = $("#philhealth_type").val();
        editContribution(eid,type);
    })
});

$(document).ready(function(){ 
  
});
</script>
<!--
<a onclick="javascript:void(0);" class="philhealth-import-btn gray_button float-right" id="" href="javascript:void(0);"><i class="icon-excel icon-custom"></i> Import Philhealth Table </a>-->
<br />
<div class="table-container">
<input type="hidden" id="philhealth_type" value="philhealth" />
<table id="dtPhilhealth" class="display">
<thead>
  <tr>
    <th valign="top"></th> 
    <th valign="top">Salary From</th>   
    <th valign="top">Salary To</th>    
    <th valign="top">Multiplier Employee</th>
    <th valign="top">Multiplier Employer</th>
    <th valign="top">Is Fixed</th>
  </tr>
</thead>
    <?php 
        foreach ($philhealth as $p){
    ?>
      <tr> 
        <td >
            <!--
            <a href="javascript:void(0);" class="btn btn-edit-contribution btn-mini" id="<?php echo Utilities::encrypt($p->getId());?>" original-title="Edit">
                <i class="icon-pencil"></i>
            </a>-->
        </td>       
        <td valign="center" align="left" style="color:#333">
            <?php echo number_format($p->getSalaryFrom(),2,".",","); ?>         
        </td>            
        <td valign="center" align="left" style="color:#333">
            <?php
                if($p->getSalaryTo() == 0){
                 echo 'Over';
                }else{
                 echo number_format($p->getSalaryTo(),2,".",","); 
                }
            ?>          
        </td>  
        <td valign="center" align="left" style="color:#333">
            <?php echo number_format($p->getMultiplierEmployee(),2,".",","); ?>         
        </td>
        <td valign="center" align="left" style="color:#333">
            <?php echo number_format($p->getMultiplierEmployer(),2,".",","); ?>         
        </td> 
        <td valign="center" align="left" style="color:#333">
            <?php echo $p->getIsFixed(); ?>
        </td>                       
      </tr>
    <?php } ?>   
</table>
</div>