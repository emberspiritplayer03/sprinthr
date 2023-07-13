<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table.tbl-border td { border:1px solid #666666;}
p{font-size: 14px;font-weight: bold;}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td><?php echo $header1; ?></td></tr>
</table>

<table class="tbl-border" width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>          
    <td bgcolor="#CCCCCC"><b>Given to</b></td>
    <td bgcolor="#CCCCCC"><b>Earnings</b></td>
    <td bgcolor="#CCCCCC"><b>Is Taxable</b></td>    
    <td bgcolor="#CCCCCC"><b>Amount</b></td>    
  </tr>
<?php 
    $counter = 1;
    $grand_total = 0;
    foreach($data as $d){ 
      $object_description = strtr(utf8_decode($d['object_description']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      $title              = strtr(utf8_decode($d['title']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');      
      $grand_total += $d['amount'];
?>      
      <tr>        
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($object_description, MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($title, MB_CASE_TITLE, "UTF-8"); ?></td>                
        <td style="mso-number-format:'\@';"><?php echo $d['is_taxable']; ?></td>        
        <td style="mso-number-format:'\@';"><?php echo number_format($d['amount'],2); ?></td>
      </tr>       
<?php $counter++; } ?>  
      <tr>
        <td colspan="3"><b>Total</b></td>
        <td style="mso-number-format:'\@';text-align:right;"><b><?php echo number_format($grand_total,2); ?></b></td>        
      </tr> 
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=other_earnings.xls");  
  header("Pragma: no-cache");
  header("Expires: 0");
?>