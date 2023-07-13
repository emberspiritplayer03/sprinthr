<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/png" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME;?>/themes-images/favicon.ico">
<title>SprintHR &laquo; <?php echo strip_tags($page_title);?> <?php echo strip_tags($title);?></title>
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/bootstrap/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/bootstrap/bootstrap.css" />
<?php echo $meta_tags;?>
<?php Loader::get();?>
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/fonts.css" />
</head>
<body>
<script language="javascript">
	if (typeof lockScreen == "function") {
		lockScreen();
	}
</script>

<?php
    $is_dtr_notification_to_show = isset($_GET['dtr_popup_show']) ? $_GET['dtr_popup_show'] : true;
?>  

<script type="text/javascript">
var sync_attendance_interval = <?php echo $hdr_settings_sync_interval; ?>;
var is_dtr_notification_to_show = <?php echo $is_dtr_notification_to_show; ?>;
$(function() {      
    var button   = $('#dropButton');
    var box      = $('#dropBox');

    <?php echo $ini_modal_script; ?>

    //var form = $('#contDiv');
    button.removeAttr('href');
    button.mouseup(function(login) {
        box.toggle();
        button.toggleClass('active');
    });

    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#dropButton').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });

    function iniUserModal() {    
        blockPopUp(); 
        $("#<?php echo $ini_modal_wrapper; ?>").html(loading_image);
        $.post(base_url + 'initial_settings/ini_pay_period',{},function(o) {
            $("#<?php echo $ini_id_wrapper; ?>").html(o);                               
        })
        
        var $dialog = $('#<?php echo $ini_id_wrapper; ?>');
        $dialog.dialog("destroy");
        
        
        var $dialog = $('#<?php echo $ini_id_wrapper; ?>');
        $dialog.dialog({
            title: 'Initial Setup : Pay Period',
            resizable: false,
            position: [480,70],
            width: 340,        
            modal: true,
            close: function() {
                       $dialog.dialog("destroy");                      
                       disablePopUp();          
                    }   
                        
            }).dialogExtend({
            "maximize" : false,
            "minimize"  : false       
          }).show();        
    }

    function load_important_notifications() {
        $("#important_notifications").html(loading_image);
        $.post(base_url + 'notifications/_load_important_notification_list',{},function(o) {
            $("#important_notifications").html(o);                           
        })
        
        var $dialog = $('#important_notifications');
         $dialog.dialog("destroy");
         
        var $dialog = $('#important_notifications');
        $dialog.dialog({
            title: 'IMPORTANT NOTIFICATIONS',
            resizable: true,
            position: [270,50],
            width: 800,
            //height: 250,
            modal: false,
            close: function() {
                       $dialog.dialog("destroy");
                       $dialog.hide($.validationEngine.closePrompt('.formError',true)); 
                       //load_my_messages_list();               
                    }   
                        
            }).dialogExtend({
            "maximize" : false,
            "minimize"  : true,
            "dblclick" : "maximize",
            "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
          }).show();            
    }

    function load_schedule_notification(from, to) {
        $("#important_notifications").html(loading_image);
        $.post(base_url + 'notifications/_load_schedule_notification_list',{from:from,to:to},function(o) {
            $("#important_notifications").html(o);                           
        })
        
        var $dialog = $('#important_notifications');
         $dialog.dialog("destroy");
         
        var $dialog = $('#important_notifications');
        $dialog.dialog({
            title: 'IMPORTANT NOTIFICATIONS',
            resizable: true,
            position: [270,50],
            width: 800,
            modal: false,
            close: function() {
                       $dialog.dialog("destroy");
                       $dialog.hide($.validationEngine.closePrompt('.formError',true));              
                    }   
                        
            }).dialogExtend({
            "maximize" : false,
            "minimize" : true,
            "dblclick" : "maximize",
            "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
          }).show();         
    }

    function load_employee_notification() {
        $("#important_notifications").html(loading_image);
        $.post(base_url + 'notifications/_load_employee_notification_list',{},function(o) {
            $("#important_notifications").html(o);                           
        })
        
        var $dialog = $('#important_notifications');
         $dialog.dialog("destroy");
         
        var $dialog = $('#important_notifications');
        $dialog.dialog({
            title: 'IMPORTANT NOTIFICATIONS',
            resizable: true,
            position: [270,50],
            width: 800,
            modal: false,
            close: function() {
                       $dialog.dialog("destroy");
                       $dialog.hide($.validationEngine.closePrompt('.formError',true));              
                    }   
                        
            }).dialogExtend({
            "maximize" : false,
            "minimize" : true,
            "dblclick" : "maximize",
            "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
          }).show();           
    }

    function load_attendance_notification(from, to) {
        $("#important_notifications").html(loading_image);
        $.post(base_url + 'notifications/_load_attendance_notification_list',{from:from, to:to},function(o) {
            $("#important_notifications").html(o);                           
        })
        
        var $dialog = $('#important_notifications');
         $dialog.dialog("destroy");
         
        var $dialog = $('#important_notifications');
        $dialog.dialog({
            title: 'IMPORTANT NOTIFICATIONS',
            resizable: true,
            position: [270,50],
            width: 800,
            modal: false,
            close: function() {
                       $dialog.dialog("destroy");
                       $dialog.hide($.validationEngine.closePrompt('.formError',true));              
                    }   
                        
            }).dialogExtend({
            "maximize" : false,
            "minimize" : true,
            "dblclick" : "maximize",
            "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
          }).show();           
    }    

    function load_dtr_notification(from, to) {
        $("#important_notifications").html(loading_image);
        $.post(base_url + 'notifications/_load_dtr_notification_list',{from:from,to:to},function(o) {
            $("#important_notifications").html(o);                           
        })
        
        var $dialog = $('#important_notifications');
         $dialog.dialog("destroy");
         
        var $dialog = $('#important_notifications');
        $dialog.dialog({
            title: 'IMPORTANT NOTIFICATIONS',
            resizable: true,
            position: [270,50],
            width: 800,
            modal: false,
            close: function() {
                       $dialog.dialog("destroy");
                       $dialog.hide($.validationEngine.closePrompt('.formError',true));              
                    }   
                        
            }).dialogExtend({
            "maximize" : false,
            "minimize" : true,
            "dblclick" : "maximize",
            "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
          }).show();         
    }

    <?php if($HR_NOTIF_ENABLE == 'Yes') { ?>
            <?php if($is_enable_popup_notification && $count_schedule_notifications > 0) { ?>
                    <?php 
                        if( isset($_GET['cutoff_period']) )  {
                            $cutoff_period = explode('/', $_GET['cutoff_period']);
                            $from = isset($cutoff_period[0]) ? $cutoff_period[0] : '';
                            $to   = isset($cutoff_period[1]) ? $cutoff_period[1] : '';
                        }elseif( isset($_GET['month']) && isset($_GET['year']) ) {
                            $date_to_check  = $_GET['year'] . '-' . $_GET['month'] . '-' . 15;
                            $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date_to_check);
                            if($current_p) {
                                $from = $current_p['period_start'];
                                $to   = $current_p['period_end'];
                            }
                        } else {
                            $from = isset($_GET['from']) ? $_GET['from'] : '';
                            $to   = isset($_GET['to']) ? $_GET['to'] : '';
                        }
                    ?>   
                    
                    load_schedule_notification('<?php echo $from; ?>', '<?php echo $to; ?>');
            <?php }else if($is_enable_popup_notification && $count_employee_notifications > 0) { ?>
                    load_employee_notification();
            <?php }else if($is_enable_popup_notification) { ?>
                    <?php if($is_dtr_notification) { ?>
                            if(is_dtr_notification_to_show != false) {
                                <?php 
                                    if( isset($_GET['cutoff_period']) )  {
                                        $cutoff_period = explode('/', $_GET['cutoff_period']);
                                        $from = isset($cutoff_period[0]) ? $cutoff_period[0] : '';
                                        $to   = isset($cutoff_period[1]) ? $cutoff_period[1] : '';
                                    } else {
                                        $from = isset($_GET['from']) ? $_GET['from'] : '';
                                        $to   = isset($_GET['to']) ? $_GET['to'] : '';
                                    }
                                ?>                        
                                load_dtr_notification('<?php echo $from; ?>', '<?php echo $to; ?>');
                            }
                    <?php }else{ ?>
                            <?php 
                                if( isset($_GET['cutoff_period']) )  {
                                    $cutoff_period = explode('/', $_GET['cutoff_period']);
                                    $from = isset($cutoff_period[0]) ? $cutoff_period[0] : '';
                                    $to   = isset($cutoff_period[1]) ? $cutoff_period[1] : '';
                                } else {
                                    $from = isset($_GET['from']) ? $_GET['from'] : '';
                                    $to   = isset($_GET['to']) ? $_GET['to'] : '';
                                }
                            ?>
                            load_attendance_notification('<?php echo $from; ?>', '<?php echo $to; ?>');
                    <?php } ?>
            <?php } ?>
    <?php } ?>

    var employee_credits_list = "";
    <?php foreach($employee_with_leave_increase as $key => $value)  { ?>
        employee_credits_list += '<?php echo $value; ?> <br>'; 
    <?php } ?>

    <?php if($is_credit_upgraded) { ?>
        updateLeaveCreditNotification(employee_credits_list);
    <?php } ?>

});
</script>

<!-- NOTIFICATIONS -->
<script src="<?php echo MAIN_FOLDER; ?>application/scripts/notifications.js" type="text/javascript"></script>
<script src="<?php echo MAIN_FOLDER; ?>application/scripts/attendance_sync.js" type="text/javascript"></script>

<script src="<?php echo MAIN_FOLDER; ?>application/scripts/sync_data.js" type="text/javascript"></script>
<div id="sync_data_modal"></div>
<div id="leave_credit_notication"></div>
<?php echo $ini_modal_wrapper; ?>
<div id="wrapper">
	<div id="header_wrapper">
        <div id="header_container">        	
            <div id="header" class="clearfix">
                <div class="logo_container">
                	<a href="<?php echo url('schedule');?>">                    	
                    	<img src="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/themes-images/logo.png" border="0" alt="SprintHR" />
                    </a>
                </div>                
                <?php include('profile_info.php');?>
                <?php include('menu.php');?>
            </div><!-- #header -->
        </div><!-- #header_container -->
    </div><!-- #header_wrapper -->
    <div id="wrapcontainer">
    	<div id="container">
        	<div class="contshad contshadcor lefttop"></div>
            <div class="contshad contshadcor righttop"></div>
            <div class="contshad contshadcor leftbottom"></div>
            <div class="contshad contshadcor rightbottom"></div>
            <div class="contshad leftside"></div>
            <div class="contshad rightside"></div>
            <div class="contshad topside"></div>
            <div class="contshad bottomside"></div>