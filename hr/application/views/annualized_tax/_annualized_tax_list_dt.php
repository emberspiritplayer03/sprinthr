<script>
$(document).ready(function(){ 
  var oTable = $('#dtAnnualTax').dataTable( {
	   "aoColumns": [   			   		                        
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '30%',sClass:'dt_small_font dt_center'},
            {sWidth: '30%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},                       
	 	],
		'bProcessing':true, 		
		"bAutoWidth": true,
		"bInfo":true,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],	
		"sPaginationType": "full_numbers",
		"bPaginate": true
	});
});
</script>
<div class="table-container">
<table id="dtAnnualTax" class="display" style="display:block;overflow:auto;">
<thead>
  <tr>  	
    <th valign="top">ID</th>   
    <th valign="top">Name</th>       
    <th valign="top">Position</th>          
    <th valign="top">Employment Status</th>    
    <th valign="top">Section</th>          
    <th valign="top">Department</th>          
    <th valign="top">Gross Income Tax</th>   
    <th valign="top">Personal Exemption</th>   
    <th valign="top">Taxable Income</th>      
    <th valign="top">Tax Due</th>   
    <th valign="top">Tax Withheld Payroll</th> 
    <th valign="top">Tax Refund</th>   
  </tr>
</thead>
    <?php foreach( $tax_data as $key => $d ){ ?>
        <tr>                              
            <?php 
                $lastname  = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
                $firstname = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');               
                $position  = strtr(utf8_decode($d['position']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');               
                $employment_status  = strtr(utf8_decode($d['employee_status']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');               
                $section   = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');               
                $department   = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');               
            ?>
            <td valign="center" align="left" style="color:#333"><?php echo $d['employee_code']; ?></td>              
            <td valign="center" align="left" style="color:#333"><?php echo mb_convert_case($lastname . ' ' . $firstname,  MB_CASE_TITLE, "UTF-8"); ?></td>              
            <td valign="center" align="left" style="color:#333"><?php echo $position; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo $employment_status; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo $section; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo $department; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo number_format(floatval($d['gross_income_tax']),2); ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo number_format(floatval($d['less_personal_exemption']),2); ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo number_format(floatval($d['taxable_income']),2); ?></td>              
            <?php if( isset($d['tax_withheld_payroll']) && $d['tax_withheld_payroll'] > 0 ) { ?>
                    <td valign="center" align="left" style="color:#333"><?php echo number_format(floatval($d['tax_due']),2); ?></td>  
            <?php } else { ?>
                    <td valign="center" align="left" style="color:#333"><?php echo number_format(0,2); ?></td>  
            <?php } ?>
            <td valign="center" align="left" style="color:#333"><?php echo number_format(floatval($d['tax_withheld_payroll']),2); ?></td>  
            <?php if( isset($d['tax_withheld_payroll']) && $d['tax_withheld_payroll'] > 0 ) { ?>
                    <td valign="center" align="left" style="color:#333"><?php echo number_format(floatval($d['tax_refund_payable']),2); ?></td>  
            <?php } else { ?>
                    <td valign="center" align="left" style="color:#333"><?php echo number_format(0,2); ?></td>  
            <?php } ?>
        </tr>
    <?php } ?>    
</table>
</div>
