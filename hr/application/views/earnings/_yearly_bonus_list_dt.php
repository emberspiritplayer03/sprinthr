<script>
function checkAction()
{       
    var chkAction = $("#chkAction").val();  
    if(chkAction == ''){
        return false;
    }else{
        return true;
    }   
}

$(document).ready(function(){ 
  var oTable = $('#dtEarnings').dataTable( {
       "aoColumns": [                       
            //{sWidth: '2%',sClass:'dt_small_font'},                         
            {sWidth: '40%',sClass:'dt_small_font'},                         
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
          
          //  {sWidth: '20%',sClass:'dt_small_font dt_center'},           
          
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
            {sWidth: '20%',sClass:'dt_small_font dt_center'},
            //{sWidth: '20%',sClass:'dt_small_font dt_center'},
            {sWidth: '20%',sClass:'dt_small_font dt_center'}            
        ],
        'bProcessing':true,         
        "bAutoWidth": true,
        "bInfo":true,
        "bJQueryUI": true,
        "aaSorting": [[ 2, "asc" ]],    
        "sPaginationType": "full_numbers",
        "bPaginate": true
    });
});
</script>
<div class="table-container">
<table id="dtEarnings" class="display">
<thead>
  <tr>
    <!-- <th valign="top"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th> -->
    <th valign="top">Cutoff</th>        
    <th valign="top">ID</th>   
    <th valign="top">Name</th>       
    <th valign="top">Employee Status</th>       
    <th valign="top">Department</th>   
    <!--<th valign="top">Section</th>   -->
    <th valign="top">Position</th>  
    <th valign="top">Basic Pay</th>        
    <th valign="top">Absent</th>                 
    <th valign="top">Percentage</th>                 
    <!-- <th valign="top">Tax</th>  -->                
    <th valign="top">13th Month</th>        
  </tr>
</thead>
    <?php foreach( $yearly_bonus_data as $key => $yb ){ ?>
        <?php foreach( $yb as $subKey => $subValue ){ ?>
          <tr>                  
            <!-- <td valign="center" align="left" style="color:#333"><input type="checkbox" name="dtChk[]" value="<?php //echo $yb['employee_pkid']; ?>"></td> -->  
            <?php 
                $lastname  = strtr(utf8_decode($subValue['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
                $firstname = strtr(utf8_decode($subValue['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
                $section    = strtr(utf8_decode($subValue['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            ?>
            <td valign="center" align="left" style="color:#333"><?php echo $key; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['employee_code']; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo mb_convert_case($firstname . ' ' . $lastname,  MB_CASE_TITLE, "UTF-8"); ?></td>              
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['employee_status']; ?></td>                   
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['department_name']; ?></td>                   
           <!-- <td valign="center" align="left" style="color:#333"><?php echo mb_convert_case($section,  MB_CASE_TITLE, "UTF-8"); ?></td>                   -->
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['position']; ?></td>                           
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['total_basic_pay']; ?></td>         
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['deducted_amount']; ?></td>         
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['percentage']; ?></td>         
            <!-- <td valign="center" align="left" style="color:#333"><?php echo $subValue['tax']; ?></td>    -->      
            <td valign="center" align="left" style="color:#333"><?php echo $subValue['total_bonus_amount']; ?></td>         
          </tr>
        <?php } ?>
    <?php } ?>    
</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>