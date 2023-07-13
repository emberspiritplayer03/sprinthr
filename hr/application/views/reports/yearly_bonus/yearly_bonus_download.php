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
    <td bgcolor="#CCCCCC"><b>Cutoff Given</b></td>
    <td bgcolor="#CCCCCC"><b>Emplyoee Code</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Name</b></td>
    <td bgcolor="#CCCCCC"><b>Department Name</b></td>
    <td bgcolor="#CCCCCC"><b>Sections</b></td>
    <td bgcolor="#CCCCCC"><b>Status</b></td>
    <td bgcolor="#CCCCCC"><b>Position</b></td>
    <td bgcolor="#CCCCCC"><b>Total basic pay amount</b></td>
    <td bgcolor="#CCCCCC"><b>Total absent amount</b></td>    
    <td bgcolor="#CCCCCC"><b>13th month</b></td>    
  </tr>
    <?php
      $grand_total_bonus  = 0;
      $grand_total_absent = 0;
      $grand_total_basic  = 0;
      foreach( $data as $key => $subData ){        
        foreach( $subData as $subKey => $d ){
           $lastname  = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
           $firstname = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
           $department = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
           $section    = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');          

          $grand_total_bonus  += $d['yearly_bonus'] - $d['tax_bonus_service_award'];
          $grand_total_absent += $d['total_absent_amount'];
          $grand_total_basic  += $d['total_basic_pay'];
    ?>
      <tr>        
        <td style="mso-number-format:'\@';"><?php echo $d['cutoff_start_date'] . ' to ' . $d['cutoff_end_date']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_code']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($firstname . " " . $lastname,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($department,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($section,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_status']; ?></td>        
        <td style="mso-number-format:'\@';"><?php echo $d['position']; ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['total_basic_pay'],2); ?></td>        
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['total_absent_amount'],2); ?></td>        
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['yearly_bonus'] - $d['tax_bonus_service_award'],2); ?></td>        
      </tr> 
    <?php 
        }
      }
    ?>
    <tr>
      <td colspan="7">Total</td>
      <td style="mso-number-format:'\@';text-align:right;"><?php echo $grand_total_basic; ?></td>
      <td style="mso-number-format:'\@';text-align:right;"><?php echo $grand_total_absent; ?></td>
      <td style="mso-number-format:'\@';text-align:right;"><?php echo $grand_total_bonus; ?></td>
    </tr>   
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=yearly_bonus.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>