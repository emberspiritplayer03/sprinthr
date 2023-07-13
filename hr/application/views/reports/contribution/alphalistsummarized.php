<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table td { border:1px solid #666666;}
</style>
<h1><?php echo $header; ?></h1>

<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>    
    <!-- <td bgcolor="#e4f5ff">Year</td> -->
 
    <td bgcolor="#e4f5ff">Cutoff Date</td>
    <td bgcolor="#e4f5ff">Gross Pay</td>
    <td bgcolor="#e4f5ff">Taxable</td>
    <td bgcolor="#e4f5ff">Non Taxable</td>
    <td bgcolor="#e4f5ff">SSS</td>
    <td bgcolor="#e4f5ff">Philhealth</td>
    <td bgcolor="#e4f5ff">Pag ibig</td>
    <td bgcolor="#e4f5ff">TaxWithheld</td>
      
  </tr>
 <?php          
           
        ?>

<?php 
    
    
    $counter = 0;
    $total = array();
    foreach($data as $d){ 

         
           
        // $total['total_gross_pay']     = $total['total_gross_pay'] + ($d['total_gross_pay'] + $d['total_service_award_tax'] + $d['total_taxable_leave_converted']);
         $total['total_gross_pay']     = $total['total_gross_pay'] + ($d['total_gross_pay']);

        $total['total_taxable']     = $total['total_taxable'] + $d['total_taxable'];
        $total['total_nontaxable']    = $total['total_nontaxable'] + $d['total_nontaxable'];
        $total['total_sss']    = $total['total_sss'] + $d['total_sss'];
        $total['total_philhealth']   = $total['total_philhealth'] + $d['total_philhealth'];
        $total['total_pagibig']          = $total['total_pagibig'] + $d['total_pagibig'];
        $total['total_withheld_tax']   = $total['total_withheld_tax'] + $d['total_withheld_tax'];

 ?>
       
    <tr>        
     <!--    <td style="mso-number-format:'\@';"><?php echo date_format($d['cutoff_date'],"M"); ?></td> -->
        
    
        <td style="mso-number-format:'\@';"><?php echo $d['cutoff_date']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['total_gross_pay'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['total_taxable'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['total_nontaxable'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['total_sss'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['total_philhealth'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['total_pagibig'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['total_withheld_tax'],2); ?></td>
       
    </tr>
  

<?php $counter_rows++; } 
?>  
    <tr>
        <td colspan="1"><b>Total</b></td>
        <?php foreach( $total as $key => $value ){ ?>
            <td style="mso-number-format:'\@';text-align: right;"><b><?php echo number_format($value,2); ?></b></td>
        <?php } ?>
    </tr>  
</table>
<?php
header("Content-type: application/x-msexcel"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>
