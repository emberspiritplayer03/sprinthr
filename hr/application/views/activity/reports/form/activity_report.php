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

<?php

$start = $from;
$end = $to;
while ($start <= $end) {
       
        
       $dates[]=$start;
       $start = date('Y-m-d',strtotime($start.'1 day'));
    }

?>

<table class="tbl-border" width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>       
    <td bgcolor="#CCCCCC"><b>Employee Code</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Name</b></td>
    <td bgcolor="#CCCCCC"><b>Project Site</b></td>
    <td bgcolor="#CCCCCC" colspan="<?php echo count($dates); ?>"><b><center>Time Roll</center></b></td>
    <td bgcolor="#CCCCCC"><b>Total Raw Worked Hrs</b></td>
    <td bgcolor="#CCCCCC"><b>Total Deductible Hrs</b></td>
    <td bgcolor="#CCCCCC"><b>Total Worked Hrs</b></td>
    <td bgcolor="#CCCCCC"><b>Total Amount</b></td>
  </tr>


   <tr>       
        <td bgcolor="#CCCCCC"><b></b></td>
        <td bgcolor="#CCCCCC"><b></b></td>
        <td bgcolor="#CCCCCC"><b></b></td>
            <?php
                foreach($dates as $d){

                     $current = strtotime($d);
                     $date = date('m/d', $current);
                     $day = date("D",  $current);  ?> 
                    
          
       <td align="center" style="vertical-align:middle;"><strong><?='&#8205; '.$date?></strong><br/><strong><?php echo $day; ?></strong></td>
             <?php
                }
            ?>

       <td bgcolor="#CCCCCC"><b></b></td>
       <td bgcolor="#CCCCCC"><b></b></td>
        <td bgcolor="#CCCCCC"><b></b></td>
        <td bgcolor="#CCCCCC"><b></b></td>

   </tr>


 <?php

    //utilities::displayArray($data);
    foreach($data as $key => $d){ // main data array
         $e = G_Employee_Finder::findById($key);
          $employee_name = $e->getName();
          $employee_code = $e->getEmployeeCode();


          $subtotal['activity_raw_worked_hrs'] = 0;
          $subtotal['activity_deductible_hrs'] =0;
          $subtotal['activity_worked_hrs'] = 0;
          $subtotal['activity_amount'] = 0;


        foreach($d as $project_site => $p){ //project site

           $project_site = G_Project_Site::find($project_site);
              if($project_site){
                $project_site_name = $project_site->getName();
              }

?>

   <tr>
     <td><?php echo $employee_code; ?></td>
     <td><?php echo strtoupper($employee_name); ?></td>
     <td><?php echo $project_site_name; ?></td>

      <?php // timeroll

        foreach($dates as $timeroll){
          
          $timeroll_hrs = 0;

          foreach($p as $pp){



              if($pp['date'] == $timeroll){

                  $timeroll_hrs += $pp['activity_raw_worked_hrs'];

              }

          }

           echo '<td>'.$timeroll_hrs.'</td>';

        }

         $total_activity_raw_hrs = 0;
         $total_deductible_hrs = 0;
         $total_worked_hrs = 0;
         $total_amount = 0;

        foreach($p as $pp){

           $total_activity_raw_hrs += $pp['activity_raw_worked_hrs']; 
           $total_deductible_hrs += $pp['activity_deductible_break_hrs']; 
           $total_worked_hrs += $pp['activity_total_worked_hrs'];
           $total_amount += $pp['total_amount_worked']; 

          
           
        }
         $subtotal['activity_raw_worked_hrs'] += $total_activity_raw_hrs;
         $subtotal['activity_deductible_hrs'] += $total_deductible_hrs;
         $subtotal['activity_worked_hrs'] += $total_worked_hrs;
         $subtotal['activity_amount'] += $total_amount;

       



      ?>

      <td><?php echo  $total_activity_raw_hrs; ?></td>
      <td><?php echo  $total_deductible_hrs; ?></td>
      <td><?php echo  $total_worked_hrs; ?></td>
      <td><?php echo  number_format($total_amount,2); ?></td>
   </tr>

<?php
     }



?>
    <?php  $colspan = count($dates) + 3;   ?>
     <!-- total per project site-->
     <tr style="background: #ccc">
        <td colspan="<?php echo $colspan;  ?>"><b>TOTAL: </b></td>
        <td><b><?php echo  $subtotal['activity_raw_worked_hrs']; ?></b></td>
        <td><b><?php echo  $subtotal['activity_deductible_hrs']; ?></b></td>
        <td><b><?php echo  $subtotal['activity_worked_hrs']; ?></b></td>
        <td><b><?php echo  number_format($subtotal['activity_amount'],2); ?></b></td>
     </tr>



<?php

      //grandtotal
      $grand_total['activity_raw_worked_hrs'] +=  $subtotal['activity_raw_worked_hrs'];
      $grand_total['activity_deductible_hrs'] +=  $subtotal['activity_deductible_hrs'];
      $grand_total['activity_worked_hrs'] +=  $subtotal['activity_worked_hrs'];
      $grand_total['activity_amount'] +=  $subtotal['activity_amount'];
    } //end main foreach
 ?>

    <tr style="background: #fff">
      <td colspan="14">&nbsp;</td>
    </tr>

  <!-- Grand Total-->
     <tr style="background: #ccc">
        <td colspan="<?php echo $colspan;  ?>"><b>GRAND TOTAL: </b></td>
        <td><b><?php echo  $grand_total['activity_raw_worked_hrs']; ?></b></td>
        <td><b><?php echo  $grand_total['activity_deductible_hrs']; ?></b></td>
        <td><b><?php echo  $grand_total['activity_worked_hrs']; ?></b></td>
        <td><b><?php echo  number_format($grand_total['activity_amount'],2); ?></b></td>
     </tr>



  
</table>



<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=activity_report.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>  