<div class="box-wrapper">
    <div class="row-fluid">   

        <div class="span9">
            <div class="leave_credits_box">
                <?php if($leave_available) { ?>
                <?php foreach($leave_available as $key => $value) { ?>
                    <div class="lc-box-container">
                        <div class="box-header"><?php echo $value['name'];?> Credit</div>
                        <div class="box-content"><h2><?php echo $value['no_of_days_available'];?></h2></div>
                    </div>
                <?php } ?>
                <?php }else{ ?>
                    <div class="lc-box-container">
                        <div class="box-header">Leave Credit</div>
                        <div class="box-content"><h2>0</h2></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php if($request_needs_approval > 0) { ?>
        <div class="span3">
            <div class="leave_approval_box">
                <div class="nya-box-container">
                    <div class="box-header"><b>Needs Your Approval</b></div>
                    <div class="box-content">
                        <h2 id="btn-notification-counter"><?php echo $request_needs_approval;?></h2>
                        <a class="add_button big_button" href="<?php echo url('dashboard/for_approval');?>">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div class="span3">
            <div class="leave_approval_box">
                <div class="nya-box-container">
                    <div class="box-header"><b>Needs Your Approval</b></div>
                    <div class="box-content">
                        <h2 id="btn-notification-counter">0</h2>
                        <!-- <a class="add_button big_button" href="javascript:void(0);">No Request</a> -->
                        <div class="alert alert-info">No Request</div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>