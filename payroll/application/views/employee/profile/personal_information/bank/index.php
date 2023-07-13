<h2 class="field_title"><?php echo $title_bank; ?><a class="add_button" id="direct_deposit_add_button_wrapper" href="javascript:loadDirectDepositAddForm();"><strong>+</strong><b>Add Account</b></a></h2>
<div id="direct_deposit_edit_form_wrapper"></div>
<div id="direct_deposit_add_form_wrapper" style="display:none"><?php include 'form/bank_add.php'; ?></div>
<div id="direct_deposit_delete_wrapper"></div>
<div id="direct_deposit_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Bank Name</th>
          <th width="150" scope="col">Account</th>
          <th width="109" scope="col">Account Type</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($banks as $key=>$e) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadDirectDepositEditForm('<?php echo $e->id; ?>');"><?php echo $e->bank_name; ?></a></td>
          <td><?php echo $e->account; ?></td>
          <td><?php echo $e->account_type; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="3"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>