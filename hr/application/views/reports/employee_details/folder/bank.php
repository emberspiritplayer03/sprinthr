<h3 class="section_title"><?php echo $title_banks; ?></h3>
<div id="emergency_contacts_table_wrapper">
<table id="hor-minimalist-b" summary="Employee Pay Sheet" border="0">
      <thead>
        <tr>
          <th align="left" valign="middle" scope="col">Bank Name</th>
          <th align="left" valign="middle" scope="col">Account</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($banks as $b) { ?>
        <tr>
          <td align="left" valign="top"><?php echo $b->getBankName(); ?></td>
          <td align="left" valign="top"><?php echo $b->getAccount(); ?></td>
        </tr>
       <?php 
	  	 $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="2" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>