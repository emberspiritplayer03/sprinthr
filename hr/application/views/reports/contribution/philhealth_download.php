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
    <td bgcolor="#CCCCCC"><b>Employee Code</b></td>
    <td bgcolor="#CCCCCC"><b>PH No</b></td>
    <td bgcolor="#CCCCCC"><b>Lastname</b></td>
    <td bgcolor="#CCCCCC"><b>FirstName</b></td>
    <td bgcolor="#CCCCCC"><b>MiddleName</b></td>
    <td bgcolor="#CCCCCC"><b>Department</b></td>
    <td bgcolor="#CCCCCC"><b>Section</b></td>
    <td bgcolor="#CCCCCC"><b>Employment Status</b></td>
    <td bgcolor="#CCCCCC"><b>Bday</b></td>
    <td bgcolor="#CCCCCC"><b>EE</b></td>
    <td bgcolor="#CCCCCC"><b>ER</b></td>    
    <td bgcolor="#CCCCCC"><b>Total</b></td>
  </tr>
<?php 
    $counter = 1;
    foreach($data as $d){
     // $lastname   = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    //  $firstname  = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    //  $middlename = strtr(utf8_decode($d['middlename']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');


      $lastname = mb_convert_encoding($d['lastname'] , "HTML-ENTITIES", "UTF-8");
      $firstname = mb_convert_encoding($d['firstname'] , "HTML-ENTITIES", "UTF-8");
      $middlename = mb_convert_encoding($d['middlename'] , "HTML-ENTITIES", "UTF-8");

      $department = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      $section    = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      //old
       //$company_share = 0;
      //new
      if($frequency_id == 2){ //for weekly

       $company_share = $d['philhealth_er_contribution'];
       $grand_total['philhealth_contribution'] += $d['philhealth_contribution'];

      }

      else{


         $company_share = $d['philhealth_er'];
   
          $grand_total['philhealth_contribution'] += $d['philhealth_contribution'];

          if($company_share == 0 || $company_share == null){
            $company_share = $d['philhealth_er'];
          }else{
             $company_share = $d['philhealth_er'];
          }
          

          $company_share = $d['philhealth_er'];

          if($d['philhealth_contribution'] == 0){

            $company_share = 0;
          }



      }
     

       $total      = $d['philhealth_contribution'] + $company_share;
      $grand_total['company_share']           += $company_share;      
      $grand_total['total'] += $total;
?>      
      <tr>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_code']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['philhealth_number']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $lastname; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $firstname; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $middlename; ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($department,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($section,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['status']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['birthdate']; ?></td>        
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($d['philhealth_contribution'],2); ?></td>
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($company_share,2); ?></td>        
        <td style="mso-number-format:'\@';text-align:right;"><?php echo number_format($total,2); ?></td>
      </tr>       
<?php $counter++; } ?>  
      <tr>
        <td colspan="9"><b>Total</b></td>
        <?php foreach( $grand_total as $value ){ ?>
          <td style="mso-number-format:'\@';text-align:right;"><b><?php echo number_format($value,2); ?></b></td>
        <?php } ?>
      </tr> 
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=philhealth.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>