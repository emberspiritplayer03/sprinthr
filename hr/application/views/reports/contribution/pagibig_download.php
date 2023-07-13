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
    <td bgcolor="#CCCCCC"><b>PagIBIG ID/RTN</b></td>
    <td bgcolor="#CCCCCC"><b>Account No</b></td>
    <td bgcolor="#CCCCCC"><b>Membership Program</b></td>
    <td bgcolor="#CCCCCC"><b>Lastname</b></td>
    <td bgcolor="#CCCCCC"><b>Firstname</b></td>
    <td bgcolor="#CCCCCC"><b>Name Extension</b></td>
    <td bgcolor="#CCCCCC"><b>Middlename</b></td>    

    <td bgcolor="#CCCCCC"><b>Department</b></td>    
    <td bgcolor="#CCCCCC"><b>Section</b></td>    
    <td bgcolor="#CCCCCC"><b>Employment Status</b></td>    

    <td bgcolor="#CCCCCC"><b>Period Covered</b></td>
    <td bgcolor="#CCCCCC"><b>EE Share</b></td>
    <td bgcolor="#CCCCCC"><b>ER Share</b></td>
    <td bgcolor="#CCCCCC"><b>Remarks</b></td>
  </tr>
<?php 
    $counter = 1;
    foreach($data as $d){ 
     // $lastname   = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
     // $firstname  = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

      $lastname = mb_convert_encoding($d['lastname'] , "HTML-ENTITIES", "UTF-8");
      $firstname = mb_convert_encoding($d['firstname'] , "HTML-ENTITIES", "UTF-8");
      $middlename = mb_convert_encoding($d['middlename'] , "HTML-ENTITIES", "UTF-8");


      //$middlename = strtr(utf8_decode($d['middlename']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      $name_extension = strtr(utf8_decode($d['extension_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

      $department = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
      $section    = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

      $period_covered = date("Ym",strtotime($d['period_start']));


      if($frequency_id == 2){

          $pagibig_er_contribution  = $d['pagibig_er_contribution'];
      }

      else{

        if($d['pagibig_er'] == 0 || $d['pagibig_er'] == null){
          $pagibig_er_contribution = $d['pagibig_er_contribution'];
          }else{
            $pagibig_er_contribution = $d['pagibig_er'];
          }

          $pagibig_er_contribution = $d['pagibig_er'];

      }

      
       if($d['pagibig_contribution'] == 0){ //if ee is zero set er to zero

               $pagibig_er_contribution = 0;

          }


      $total = $d['pagibig_contribution'] + $pagibig_er_contribution;
      $grand_total['pagibig_contribution'] += $d['pagibig_contribution'];
      $grand_total['pagibig_employer']     += $pagibig_er_contribution;
      //old  
      // $total = $d['pagibig_contribution'] + $d['pagibig_employer'];
      // $grand_total['pagibig_contribution'] += $d['pagibig_contribution'];
      // $grand_total['pagibig_employer']     += $d['pagibig_employer'];     
      //old 
      //$grand_total['total'] += $total;
    //pagibig_er
?>      
      <tr>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_code']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['pagibig_number']; ?></td>
        <td style="mso-number-format:'\@';"></td>
        <td style="mso-number-format:'\@';"></td>
        <td style="mso-number-format:'\@';"><?php echo $lastname; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $firstname; ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($name_extension, MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo $middlename; ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($department,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo mb_convert_case($section,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['status']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $period_covered; ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['pagibig_contribution'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($pagibig_er_contribution,2); ?></td>
        <td style="mso-number-format:'\@';"></td>
      </tr>       
<?php $counter++; } ?>  
      <tr>
        <td colspan="12"><b>Total</b></td>
        <?php foreach( $grand_total as $value ){ ?>
          <td style="mso-number-format:'\@';"><b><?php echo number_format($value,2); ?></b></td>
        <?php } ?>
      </tr> 
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=pagibig.xls");  
  header("Pragma: no-cache");
  header("Expires: 0");
?>