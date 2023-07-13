<style>
.alert-danger h4, .alert-error h4 {
    color: #B94A48;
    font-size:17.5px;
}
.alert-error img{
    margin-right:5px;
}
</style>
<script>
$(document).ready(function() {
    $("#login_form").validationEngine({scroll:false});
    $('#login_form').ajaxForm({
        success:function(o) {
            if (o.is_success == 1) {
                window.location = base_url+'login<?php echo $url_param; ?>';
            } else {
                closeDialog('#' + DIALOG_CONTENT_HANDLER);
                showAlertBox(); 
                $("#error_message").html(o.message);    
                $("#username").val("");
                $("#password").val("");
            }
        },
        dataType:'json',
        beforeSubmit: function() {
            showLoadingDialog('Validating...');
        }
    }); 
}); 

function hideAlertBox(){
    $("#error_message").fadeOut(800);
}

function showAlertBox(){
    $("#error_message").fadeIn(800);
}
</script>
<div id="login_wrapper">
    <!--<div align="center"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/logo.png" border="0" /></div>-->
    <div id="login_container">
        <div class="login_content">
            <form id="login_form" method="post" action="<?php echo url('login/_login'); ?>">
                <div class="top_title"><a class="loggedin_actionout" href="<?php echo BASE_FOLDER; ?>index.php/login/logout"><i class="icon-off icon-white"></i>  Logout</a><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/login/account_login_title.png" border="0" /></div>
                <div class="bottom_div"></div>
                <div class="gleent_logo"></div>
                <label class="input_container loggedin">
                <div align="center" class="user_selection">
                <div align="center" style="font-size:16px;">Welcome! <strong><?php echo $hdr_username; ?></strong></div><br />
                    <?php if( is_array($hdr_hr_actions) || $hdr_hr_actions == $hdr_hr_override_access ){ ?>
                        <a class="blue_button" href="<?php echo $hr_url; ?>"><i class="icon-briefcase icon-white"></i> Human Resource</a>
                    <?php } ?>                    
                    <?php if( (is_array($hdr_dtr_actions) && trim($hdr_dtr_actions[0]['action']) != Sprint_Modules::PERMISSION_04 ) || $hdr_dtr_actions == $hdr_dtr_override_access){ ?>
                        <a class="blue_button" href="<?php echo $dtr_url; ?>"><i class="icon-list-alt icon-white"></i> DTR</a>
                    <?php } ?>

                    <?php if( is_array($hdr_payroll_actions) || $hdr_payroll_actions == $hdr_payroll_override_access ){ ?>
                        <a class="blue_button" href="<?php echo $payroll_url; ?>"><i class="icon-list-alt icon-white"></i> Payroll</a>
                    <?php } ?>

                    <?php if( is_array($hdr_employee_actions) ){ ?>
                        <a class="blue_button" href="<?php echo $employee_url; ?>"><i class="icon-list-alt icon-white"></i> Employee</a>
                    <?php } ?>

                    <div class="clear"></div>
                </div>
                </label>
            </form>
        </div>
    </div>
</div>
