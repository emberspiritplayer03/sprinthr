<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table.tbl-border td { border:1px solid #666666;}
p{font-size: 14px;font-weight: bold;}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td colspan="4"><p><?php echo $header1; ?></p></td></tr>
  <tr><td colspan="4"><p><?php echo $header2; ?></p></td></tr>
</table>
<table class="tbl-border" width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>       
    <td bgcolor="#CCCCCC"><b>No</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Code</b></td>
    <td bgcolor="#CCCCCC"><b>Lastname</b></td>
    <td bgcolor="#CCCCCC"><b>Firstname</b></td>
    <td bgcolor="#CCCCCC"><b>Department</b></td>
    <td bgcolor="#CCCCCC"><b>Section</b></td>
    <td bgcolor="#CCCCCC"><b>Employment Status</b></td>
    <td bgcolor="#CCCCCC"><b>SSS No</b></td>
    <td bgcolor="#CCCCCC"><b>EC</b></td>
    <td bgcolor="#CCCCCC"><b>EE</b></td>
    <td bgcolor="#CCCCCC"><b>ER</b></td>
    <td bgcolor="#CCCCCC"><b>Mandatory Provident Fund - EE</b></td>
    <td bgcolor="#CCCCCC"><b>Mandatory Provident Fund - ER</b></td>
    <td bgcolor="#CCCCCC"><b>Total</b></td>
  </tr>
<?php 
    $counter = 1;
    foreach($data as $d){ 
    // $lastname  = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    //  $firstname = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      $lastname  = $d['lastname'];
      $firstname = $d['firstname'];

      $department = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      $section    = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      
      $total      = $d['company_share'] + $d['sss_contribution'] + $d['company_ec'] + $d['provident_ee'] + $d['provident_er'];
      //$total      = $d['company_share'] + $d['sss_contribution'];
      
      $grand_total['company_ec']       += $d['company_ec'];
      $grand_total['sss_contribution'] += $d['sss_contribution'];
      
      $grand_total['company_share']    += $d['company_share'];
      
      $grand_total['provident_ee']        += $d['provident_ee'];
      $grand_total['provident_er']        += $d['provident_er'];
      //$grand_total['company_share']    += $d['company_share'] - $d['company_ec'];
      
      $grand_total['total']            += $total;
?>			
      <tr>
        <td style="mso-number-format:'\@';"><?php echo $counter; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_code']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_encoding($lastname, "HTML-ENTITIES", "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_encoding($firstname, "HTML-ENTITIES", "UTF-8"); ?></td>

        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($department,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($section,  MB_CASE_TITLE, "UTF-8"); ?></td>

        <td style="mso-number-format:'\@';"><?php echo $d['status']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['sss_number']; ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['company_ec'],2); ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['sss_contribution'],2); ?></td>
        <?php
          $company_share_total = $d['company_share'];
          //$company_share_total = $d['company_share'] - $d['company_ec'];
        ?>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($company_share_total,2); ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['provident_ee'],2); ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['provident_er'],2); ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($total,2); ?></td>
      </tr>       
<?php $counter++; } ?>  
      <tr>
        <td colspan="8"><b>Total</b></td>
        <?php foreach( $grand_total as $value ){ ?>
          <td style="mso-number-format:'\@';text-align:right;"><b><?php echo number_format($value,2); ?></b></td>
        <?php } ?>
      </tr> 
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=sss_download.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>