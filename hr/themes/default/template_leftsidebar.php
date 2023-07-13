<?php include('includes/header.php');?>
<div id="content" class="sidebar_left">
	<div class="mtcntnr">
		<h1 class="module_title"><i class="mticon_img icon_settings"></i><?php echo $page_title;?><?php echo $page_subtitle;?>
       <?php if($module == 'leave') { ?>
		   <?php if($recent){ ?>
           		<a class="gray_button title_back_button" href="<?php echo url('leave'); ?>"><i></i>Back</a>
                    <?php echo $btn_request_leave;?>
                    <?php //if($hdr_is_cutoff_period_lock == "No"){ ?>
                        <?php echo $btn_import_leave;?> 
                    <?php//} ?>
            <?php } ?>  
            <?php if($type){ ?>
                <?php echo $btn_add_leave_type;?>
                <!--<a class="add_button" id="import_leave_button_wrapper" href="#" onClick="javascript:importLeave();" ><i class="icon-arrow-left"></i> Import Leave Type</a>-->
            <?php } ?>
            
        <?php }elseif($module == 'overtime') { ?>
			<?php if($recent){ ?>
                <a class="gray_button title_back_button" href="<?php echo url('overtime'); ?>"><i></i>Back</a>
                <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
                    <?php echo $btn_request_overtime; ?>
                    <?php echo $btn_import_overtime; ?>
                <?php } ?>
            <?php } ?>
        <?php }elseif($module == 'undertime') { ?>
        	<?php if($recent){ ?>
                <a class="gray_button title_back_button" href="<?php echo url('undertime'); ?>"><i></i>Back</a>                
                <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
                    <a id="request_undertime_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_undertime_form('<?php echo $from_period; ?>','<?php echo $to_period; ?>');"><strong>+</strong><b>Request Undertime</b></a>
                    <a id="import_undertime" class="add_button pull-right" href="javascript:void(0)" onclick="javascript:importUndertime();"><i class="icon-arrow-left"></i> Import Undertime</a>                    
                <?php } ?>                              
            <?php } ?>           
        <?php }elseif($module == 'earnings') { ?>
        	<a id="import_ot" class="gray_button title_back_button" href="<?php echo url('earnings'); ?>"><i></i>Back</a>
        	 <?php if($recent){ ?>
             	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
	                <a id="add_earning_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_add_earnings_form('<?php echo $eid; ?>');"><strong>+</strong><b>Add Earnings</b></a>                
                    <a id="import_undertime" class="add_button pull-right" href="javascript:void(0)" onclick="javascript:importEarnings('<?php echo $eid; ?>');"><i class="icon-arrow-left"></i> Import Earnings</a>                    
                <?php } ?>
            <?php } ?>
        <?php }elseif($module == 'deductions') { ?>
        	<a id="import_ot" class="gray_button title_back_button" href="<?php echo url('deductions'); ?>"><i></i>Back</a>
        	 <?php if($recent){ ?>
             	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
	                <a id="add_deduction_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_add_deductions_form('<?php echo $eid; ?>');"><strong>+</strong><b>Add Deductions</b></a>                
                    <a id="import_undertime" class="add_button pull-right" href="javascript:void(0)" onclick="javascript:importDeductions('<?php echo $eid; ?>');"><i class="icon-arrow-left"></i> Import Deductions</a>                    
                <?php } ?>
            <?php } ?>
                        
        <?php }elseif($module == 'ob'){ ?>
        	<a id="import_ot" class="gray_button title_back_button" href="<?php echo url('ob'); ?>"><i></i>Back</a>
        	<?php if($pendings){ ?>
            	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
					<?php echo $btn_request_ob; ?>
                    <?php echo $btn_import_ob; ?>
                <?php } ?>
            <?php } ?>            
		<?php }elseif($module =='loan'){ ?>
			<?php if($e_history){ ?>
            		<a class="gray_button title_back_button" href="<?php echo url('loan/history'); ?>"><i></i>Back</a>                
            <?php } ?>
			<?php if($recent){ ?>
				<?php if($l_details){ ?>
	               <!-- <a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_load_add_payment_form();"><strong>+</strong><b>Add Payment</b></a>-->
                    <?php
						$hash =  Utilities::createHash($gel->getId());
						$id   =  Utilities::encrypt($gel->getId());
					?>
                    <a class="gray_button title_back_button" href="<?php echo url('loan'); ?>"><i></i>Back</a>                
                    <a style="float:right;" id="request_leave_button" class="blue_button" href="<?php echo url('reports/download_loan?hid=' . $id . "&hash=" . $hash); ?>"><i class="icon-download-alt icon-white"></i> Download</a> 
                <?php }else{ ?>
                	<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_loan_form();"><strong>+</strong><b>Add New Deduction</b></a>     
                <?php } ?>
            <?php } ?>
            
            <?php if($type == 'loan_type'){ ?>
            	<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_add_loan_type_form();"><strong>+</strong><b>Add Deduction Type</a>
              
            <?php }elseif($type == 'loan_deduction_type'){ ?>
            	<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_add_loan_deduction_type_form();"><strong>+</strong><b>Add Loan Deduction Type</a>
            <?php } ?>

            
                 <?php }elseif($module == 'activity'){ ?>
            <?php if($employee_activities){ ?>
                
                <?php echo $btn_add_employee_activity; ?>
                
                <?php echo $btn_import_employee_activity; ?>
                <?php echo $btn_generate_employee_activity; ?>

            <?php } ?>     
            <?php if($activities){?>
               
                <?php echo $btn_add_activity; ?>
            <?php } ?>     
            <?php if($designations){ ?>
               
                <?php echo $btn_add_designation; ?>
            <?php } ?>        

             <?php if($project_sites){ ?>
                <a id="import_ot" class="gray_button title_back_button" href="javascript:history.go(-1)"><i></i>Back</a>
                <?php echo $btn_add_project_site; ?>
            <?php } ?>        

        <?php } ?>
        
        </h1><div class="mtshad"></div>
    </div>
    <div class="holder_sidecontent clearfix">    	
		<?php include('includes/submenu_leftsidebar.php');?>
        <div class="sidebar_maincontent">
        	<div class="maincontent">
                <div id="message_container" style="width:auto; display:none" class="ui-state-highlight ui-corner-all message_box"> 
                    <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
                    <span class="message"></span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:$('#message_container').hide()" style="float:right" title="Close"></a>
                </div>
	            <?php $this->showContent();?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php include('includes/footer.php');?>