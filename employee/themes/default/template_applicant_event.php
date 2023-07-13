<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/png" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME;?>/images/favicon.ico">
<title><?php echo $GLOBALS['lang_general']['title']; ?></title>
<?php echo $meta_tags;?>
<?php Loader::get();?>
</head>

<body>
<script language="javascript">
if (typeof lockScreen == "function") {
  lockScreen();
}
</script>
<div id="wrapper">
    <div id="header_container">
        <div id="top_navigation">
            <div align="right"><span>Howdy, <strong><a class="account_name" href="#">Admin</a><?php //echo $_SESSION['summit_hr']['firstname'];?> <?php //echo $_SESSION['summit_hr']['lastname'];?></strong><a class="logout" href="<?php echo url('login/logout');?>">Log Out</a></span></div>
        </div><!-- #top_navigation -->
        <div id="header">
            <div class="logo_container">
                <a href="#"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/logo.png" border="0" /></a>
            </div>            
        </div><!-- #header -->
         <div id="menu"> 
            <ul>      
                <li class="<?php echo $dashboard; ?>"><a href="<?php echo url('dashboard');  ?>"><span>Dashboard</span></a></li>
                <li class="<?php echo $recruitment; ?>" ><a href="<?php echo url('recruitment');  ?>"><span>Recruitment<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                    <ul class="submenu">
                    <li><a href="<?php echo url('recruitment/job_vacancy'); ?>">Job Vacancy</a></li>
                        <li><a href="<?php echo url('recruitment/candidate'); ?>">Candidate</a>
                          
                        </li>
                        <li><a href="<?php echo url('recruitment/examination'); ?>">Examination</a></li>
                    </ul>
                    <!-- .submenu -->
                </li>
                <li class="<?php echo $employee; ?>" ><a href="<?php echo url('employee');  ?>"><span>Employee<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>  
                      <ul class="submenu">
                    <!--  <li><a href="<?php echo url('employee'); ?>">Employee</a></li>
                        <li><a href="<?php echo url('schedule'); ?>">Schedule</a></li>
                        <li><a href="<?php echo url('leave'); ?>">Leave</a></li>
                        <li><a href="<?php echo url('attendance'); ?>">Attendance</a></li>
                        <li><a href="<?php echo url('performance'); ?>">Performance</a></li> -->
                    </ul>
                    
                    <!-- .submenu -->
              </li>
              <li class="<?php echo $reports; ?>"><a href="<?php echo url('reports');  ?>"><span>Reports</span></a></li>
              <li class="<?php echo $settings; ?>"><a href="<?php echo url('settings/company');  ?>"><span>Settings<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                     <ul class="submenu">
                      <li><a href="<?php echo url('settings/company'); ?>">Company Structure</a></li>
                        <li><a href="<?php echo url('settings/branch'); ?>">Branch</a></li>
                        <li><a href="<?php echo url('settings/job'); ?>">Job</a></li>
                        <li><a href="<?php echo url('settings/user_management'); ?>">Users Management</a></li>
                        <li><a href="<?php echo url('settings/contribution'); ?>">Contribution</a></li>
                        <li><a href="<?php echo url('settings/examination_template'); ?>">Examination Template</a></li>
                        <li><a href="<?php echo url('settings/performance_template'); ?>">Performance Template</a></li>
                        <li><a href="<?php echo url('settings/options'); ?>">Options</a></li>
                    </ul><!-- .submenu -->
        </li>
                <li class="<?php echo $help; ?>"><a href="#"><span>Help</span></a></li>
            </ul>
        </div><!-- #menu -->
    </div><!-- #header_container -->
    <div id="wrap">
        <div id="container">
            <div id="submenu">
                <ul class="ulmenu">
             <h2>Applicant Event</h2>
              <li class="selected">
                  <div class="tabtitle"><a id="personal_information_tab"  style="cursor:pointer"  >Action <img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" /></a></div>
                  
                  <ul id="personal_information_submenu" class="ulsubmenu">
                  <?php if($_GET['status']=='application_submitted') { ?>
                       <li id="application_history_nav" class="left_nav"><a href="#application_history" onclick="javascript:hashClick('#application_history');">Interview</a></li>
                      <li id="personal_details_nav" class="left_nav"><a href="#personal_details" onclick="javascript:hashClick('#personal_details');">Offer a Job</a></li>
                      <li id="requirements_nav" class="left_nav"><a href="#requirements" onclick="javascript:hashClick('#requirements');">Rejected</a></li>
                      <li id="examination_nav" class="left_nav"><a href="#examination" onclick="javascript:hashClick('#examination');">Hired</a></li>
                 <?php } ?>
                 <?php if($_GET['status']=='interview') { ?>
                       <li id="application_history_nav" class="left_nav"><a href="#application_history" onclick="javascript:hashClick('#application_history');">Interview</a></li>
                      <li id="personal_details_nav" class="left_nav"><a href="#personal_details" onclick="javascript:hashClick('#personal_details');">Offer a Job</a></li>
                      <li id="requirements_nav" class="left_nav"><a href="#requirements" onclick="javascript:hashClick('#requirements');">Rejected</a></li>
                      <li id="examination_nav" class="left_nav"><a href="#examination" onclick="javascript:hashClick('#examination');">Hired</a></li>
                 <?php } ?>
                 
                 <?php if($_GET['status']=='offer_job') { ?>
                       <li id="application_history_nav" class="left_nav"><a href="#application_history" onclick="javascript:hashClick('#application_history');">Decline Offer</a></li>
                      <li id="personal_details_nav" class="left_nav"><a href="#personal_details" onclick="javascript:hashClick('#personal_details');">Reject</a></li>
                      <li id="requirements_nav" class="left_nav"><a href="#requirements" onclick="javascript:hashClick('#requirements');">Hired</a></li>
                 <?php } ?>
                 

                 
                  </ul>
                </li>
                </ul>
            </div>
            <div id="content">
            <?php echo $title; ?>
                <?php $this->showContent();?>
            </div>
            <div class="clear"></div>
        </div><!-- #container -->
    </div><!-- #wrap -->
    <div id="backgroundPopup"></div>
    <div id="footer_container">
        <div id="footer">
            <p><?php echo $GLOBALS['lang_general']['footer_title']; ?></p>
            <small><?php echo $GLOBALS['lang_general']['copyright_statement']; ?></small>
        </div><!-- #footer -->
    </div><!-- #footer_container -->
</div><!-- #wrapper -->
</body>
</html>

