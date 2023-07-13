<?php if($check_structure){ ?>
	 <?php if($mod_package_hr['schedule']) { ?>             
	    <div id="default_leave_wrapper_form_startup" style="display:none" >
	    <?php  include 'forms/add_leave_default.php'; ?>
	    </div>
    	<?php include_once('schedule.php'); ?>
    <?php } ?>    
      
    <?php if($mod_package['leave_request']) { ?>             
	    <div class="container_12">
			  <div class="col_1_2" style="width:100%; padding:0;"><div class="inner"><?php include_once('leave.php'); ?></div></div>
	        <!--<div class="col_1_2" style="width:50%; padding:0;"><div class="inner"><?php //include_once('basic_policy.php'); ?></div></div>-->
	        <div class="clear"></div>
	    </div>
    <?php } ?>
<?php 
	}else{ // if there is no company this will show
		include_once('no_company_structure.php');
	}
?>


