<?php include('includes/employee_summary.php'); ?>
<h3 class="section_title"><?php echo $title_banks; ?></h3>
<div id="emergency_contacts_table_wrapper">
<table width="858" id="hor-minimalist-b" summary="Employee Pay Sheet" border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Loan Type</th>
          <th width="150" align="left" valign="middle" scope="col">Balance</th>
          <th width="150" align="left" valign="middle" scope="col">Total Amount</th>
          <th width="150" align="left" valign="middle" scope="col">Deduction Type</th>
          <th width="150" align="left" valign="middle" scope="col">No. of Installment</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($loans as $l=>$val) { ?>
        <tr>
          <td align="left" valign="top"><?php echo $val['loan_type']; ?></td>
          <td align="left" valign="top"><?php echo $val['balance']; ?></td>
          <td align="left" valign="top"><?php echo number_format($val['loan_amount'],2,'.',','); ?></td>
          <td align="left" valign="top"><?php echo $val['deduction_type']; ?></td>
          <td align="left" valign="top"><?php echo $val['no_of_installment']; ?></td>
        </tr>
       <?php 
	  	 $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="6" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>