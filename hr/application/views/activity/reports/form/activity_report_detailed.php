<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table.tbl-border td { border:1px solid #666666;}
p{font-size: 14px;font-weight: bold;}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td colspan="4"><p><?php echo $header1; ?></p></td></tr>
  <tr><td colspan="4"><p><?php echo $header2; ?> (Detailed)</p></td></tr>
</table>

<?php

$start = $from;
$end = $to;

?>

<table class="tbl-border" width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>       
    <td bgcolor="#CCCCCC"><b>Employee Code</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Name</b></td>
    <td bgcolor="#CCCCCC"><b>Project Site</b></td>
    <td bgcolor="#CCCCCC"><b>Designation</b></td>
    <td bgcolor="#CCCCCC"><b>Activity</b></td>
    <td bgcolor="#CCCCCC"><b>Activity Date In</b></td>
     <td bgcolor="#CCCCCC"><b>Activity Time In</b></td>
     <td bgcolor="#CCCCCC"><b>Activity Date Out</b></td>
      <td bgcolor="#CCCCCC"><b>Activity Time Out</b></td>
    <!-- <td bgcolor="#CCCCCC" colspan="<?php echo count($dates); ?>"><b><center>Time Roll</center></b></td>-->
    <td bgcolor="#CCCCCC"><b>Total Raw Worked Hrs</b></td>
    <td bgcolor="#CCCCCC"><b>Total Deductible Hrs</b></td>
    <td bgcolor="#CCCCCC"><b>Total Worked Hrs</b></td>
    <td bgcolor="#CCCCCC"><b>Total Amount</b></td>
  </tr>
   
   <?php

      foreach ($data as $key => $project) { //main

          foreach($project as $p => $pp){

                $subtotal['raw_worked_hrs'] = 0;
                $subtotal['deductible_hrs'] = 0;
                $subtotal['total_worked_hrs'] = 0;
                $subtotal['total_amount'] = 0;

               foreach ($pp as $d => $value) { //
                 
                    $e = G_Employee_Finder::findById($value['employee_id']);
                    $employee_name = $e->getName();
                    $employee_code = $e->getEmployeeCode();


                     $project_site = G_Project_Site::find($value['project_site_id']);
                      if($project_site){
                        $project_site_name = $project_site->getName();
                      }

                      $a = G_Employee_Activities_Finder::findById($value['employee_activity_id']);
                      if($a){
                         $designation = G_Activity_Category_Finder::findById($a->getActivityCategoryId());
                         $skill = G_Activity_Skills_Finder::findById($a->getActivitySkillsId());
                      }
                      
                      if($designation){
                         $designate =  $designation->getActivityCategoryName();
                      }

                      if($skill){
                        $skills = $skill->getActivitySkillsName();
                      }

                      $activity_date_in = date("Y-m-d",strtotime($value['activity_in'])); 
                      $activity_time_in = date("g:i A",strtotime($value['activity_in'])); 
                      $activity_date_out = date("Y-m-d",strtotime($value['activity_out'])); 
                      $activity_time_out = date("g:i A",strtotime($value['activity_out'])); 

                      $raw_worked_hrs = $value['activity_raw_worked_hrs'];
                      $deductible_hrs = $value['activity_deductible_break_hrs'];
                      $total_worked_hrs = $value['activity_total_worked_hrs'];
                      $total_amount = $value['total_amount_worked'];
                      

                      $subtotal['raw_worked_hrs'] += $raw_worked_hrs;
                      $subtotal['deductible_hrs'] += $deductible_hrs;
                      $subtotal['total_worked_hrs'] += $total_worked_hrs;
                      $subtotal['total_amount'] += $total_amount;

                    ?>

                    <tr>
                     <td><?php echo $employee_code; ?></td>
                     <td><?php echo strtoupper($employee_name); ?></td>
                     <td><?php echo $project_site_name; ?></td>
                     <td><?php echo $designate; ?></td>
                     <td><?php echo $skills; ?></td>
                     <td><?php echo $activity_date_in; ?></td>
                     <td><?php echo $activity_time_in; ?></td>
                     <td><?php echo $activity_date_out; ?></td>
                     <td><?php echo $activity_time_out; ?></td>
                     <td><?php echo $raw_worked_hrs; ?></td>
                     <td><?php echo $deductible_hrs; ?></td>
                     <td><?php echo $total_worked_hrs; ?></td>
                     <td><?php echo number_format($total_amount,2); ?></td>
                   </tr>




                <?php
               }

               ?>

               <tr style="background: #ccc">
                <td colspan="9"><b>TOTAL:</b></td>
                <td><b><?php echo $subtotal['raw_worked_hrs']; ?></b></td>
                 <td><b><?php echo $subtotal['deductible_hrs']; ?></b></td>
                  <td><b><?php echo $subtotal['total_worked_hrs']; ?></b></td>
                   <td><b><?php echo  number_format($subtotal['total_amount'],2); ?></b></td>
               </tr>

        
     <?php

              $grand_total['activity_raw_worked_hrs'] += $subtotal['raw_worked_hrs'];
              $grand_total['activity_deductible_hrs'] += $subtotal['deductible_hrs'];
               $grand_total['activity_worked_hrs'] += $subtotal['total_worked_hrs'];
               $grand_total['activity_amount'] += $subtotal['total_amount'];
          }


        
      } //end main
   ?>

    <tr style="background: #fff">
      <td colspan="13">&nbsp;</td>
    </tr>

  <!-- Grand Total-->
     <tr style="background: #ccc">
        <td colspan="9"><b>GRAND TOTAL: </b></td>
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