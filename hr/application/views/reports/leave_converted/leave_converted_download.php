<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table.tbl-border td { border:1px solid #666666;}
p{font-size: 14px;font-weight: bold;}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td colspan="4"><p><?php echo $header1; ?></p></td></tr>  
</table>
<br />
<table class="tbl-border" width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>           
    <td bgcolor="#CCCCCC"><b>Emplyoee Code</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Name</b></td>
    <td bgcolor="#CCCCCC"><b>Department Name</b></td>
    <td bgcolor="#CCCCCC"><b>Section</b></td>
    <td bgcolor="#CCCCCC"><b>Status</b></td>
    <td bgcolor="#CCCCCC"><b>Position</b></td>
    <td bgcolor="#CCCCCC"><b>Leave Name</b></td>    
    <td bgcolor="#CCCCCC"><b>Total Leave Converted</b></td>    
    <td bgcolor="#CCCCCC"><b>Amount</b></td>    
  </tr>
    <?php
      $grand_total_bonus  = 0;
      $grand_total_absent = 0;
      $grand_total_basic  = 0;
      foreach( $data as $key => $d ){        
          $lastname   = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
          $firstname  = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
          $department = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
          $section    = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');          
          $grand_total_amount  += $d['amount'];          
           
    ?>
      <tr>                
        <td style="mso-number-format:'\@';"><?php echo $d['employee_code']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($firstname . " " . $lastname,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($department,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($section,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_status']; ?></td>        
        <td style="mso-number-format:'\@';"><?php echo $d['position']; ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo $d['leave_name']; ?></td>        
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['total_leave_converted'],2); ?></td>        
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['amount'],2); ?></td>        
      </tr> 
    <?php } ?>
    <tr>
      <td colspan="8">Total</td>
      <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($grand_total_amount,2); ?></td>      
    </tr>   
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=leave_converted.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>