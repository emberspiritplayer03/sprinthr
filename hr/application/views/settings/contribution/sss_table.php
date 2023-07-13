<style>.inplace_field {width:75%;}
#sss_table{
    overflow-x:scroll;
    overflow-y:hidden;
}
</style>
<script>
$(function(){
    $(".btn-edit-contribution").click(function(){
        var eid  = $(this).attr("id");
        var type = $("#sss_type").val();
        editContribution(eid,type);
    })

    $(".sss-import-btn").click(function(){
        importSSSTable();
    });

    var oTable = $('#dtSSS').dataTable( {
       "aoColumns": [              
            {sWidth: '3%',sClass:'dt_small_font dt_center',bSortable:false},    
            {sWidth: '30%',sClass:'dt_small_font'},                         
            {sWidth: '30%',sClass:'dt_small_font dt_center'},
            {sWidth: '50%',sClass:'dt_small_font dt_center'},
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

    /*$(".eip").editInPlace({
        callback: function(unused, enteredText) {

            return enteredText;
        },

    });*/
});
</script>

<a onclick="javascript:void(0);" class="sss-import-btn gray_button float-right" id="" href="javascript:void(0);"><i class="icon-excel icon-custom"></i> Import SSS Table </a>
<br />
<div class="table-container">
<input type="hidden" id="sss_type" value="sss" />
<table id="dtSSS" class="display">
<thead>
  <tr> 	
    <th valign="top"></th> 
    <th valign="top">Monthly Salary Credit</th>   
    <th valign="top">From Salary</th>    
    <th valign="top">To Salary</th>
    <th valign="top">Employee Share</th>
    <th valign="top">Company Share</th>   
    <th valign="top">Company EC</th>   
    <th valign="top">Provident EE</th>   
    <th valign="top">Provident ER</th>   
  </tr>
</thead>
    <?php 
		foreach ($sss as $s){    		
	?>
      <tr> 

       
        <td >
            <!-- <a href="javascript:void(0);" class="btn btn-edit-contribution btn-mini" id="<?php echo Utilities::encrypt($s->getId());?>" original-title="Edit">
                <i class="icon-pencil"></i>
            </a> -->
        </td>    
        <td valign="center" align="left" style="color:#333">
        	<p id="monthly_salaray_credit<?php echo Utilities::encrypt($s->getId());?>" class="eip"><?php echo number_format($s->getMonthlySalaryCredit(),2,".",","); ?></p>
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($s->getFromSalary(),2,".",","); ?>
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($s->getToSalary(),2,".",","); ?>        
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($s->getEmployeeShare(),2,".",","); ?>	
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($s->getCompanyShare(),2,".",","); ?>
        </td> 
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($s->getCompanyEc(),2,".",","); ?>
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($s->getProvidentEe(),2,".",","); ?>
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($s->getProvidentEr(),2,".",","); ?>
        </td>                     
      </tr>
    <?php } ?>   
</table>
</div>
<script>
//$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>