<h2 class="field_title"><?php echo $title; ?></h2>
<div id="membership_edit_form_wrapper"></div>
<div id="membership_add_form_wrapper" style="display:none">
<?php 
include 'form/membership_add.php';
?>
</div>
<div id="membership_delete_wrapper"></div>
<a id="membership_add_button_wrapper" href="javascript:loadMembershipAddForm();">Add Membership</a>
<div id="membership_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Membership Type</th>
          <th width="150" scope="col">Membership</th>
          <th width="109" scope="col">Subscription Ownership</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($membership as $key=>$e) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadMembershipEditForm('<?php echo $e->id; ?>');">
		  <?php //echo $e = G
		  $e->membership_type_id; ?></a></td>
          <td><?php echo $e->relationship; ?></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->birthdate); ?></td>
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