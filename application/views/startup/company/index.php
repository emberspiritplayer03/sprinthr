<style>
div.action-buttons{ padding-right:10px;padding-top:10px; width:50%;}
</style>
<script type="text/javascript">
	$(function() {
		var hash = window.location.hash;
		var tab = 0;
		if(hash == "") {tab = 0;} 
		else if(hash == "#branch_department") {tab = 1;}
		else if(hash == "#employee") {
			tab = 2;
			load_employee();
		}
		else if(hash == "#default_schedule_policy") {
			tab = 3;
			load_schedule_startup();
		}
		else if(hash == "#payroll_settings") {tab = 4;}
		
		$("#tabs").tabs({'selected':tab});
	});
	
	function select_tab(selected) {
		$("#tabs").tabs({'selected':selected});
	}
</script>
<!--<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />-->
<div id="startup_tabs_container">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1" id="tipsy_company_profile" title="Setup company details - Company Name, Address, Contact Numbers, etc.">Company Profile</a></li>
            <li><a href="#tabs-2" id="tipysy_department" title="Setup company structure - Branches and Departments">Branch and Department</a></li>
            <li class="employee_tab"><a href="#tabs-3" id="tipysy_employee" title="Add / Import company employees" onclick="javascript:load_employee();">Employee</a></li>            

				<?php if($mod_package['dtr']) { ?>            
            <li><a href="#tabs-4" onclick="javascript:load_schedule_startup();" id="tipsy_schedule" title="Define company schedules and basic policy like leave and grace period">Default Schedule and Policy</a></li>
				<?php } ?>            
            
            <?php if($mod_package['payroll']) { ?>
            	<li><a href="#tabs-5" id="tipsy_payroll_settings" title="Define / Set deductions and pay frequency">Payroll Settings</a></li>
            <?php } ?>
        </ul>    
        <div id="tabs-1">
           <div id="c-info"></div>
           <div class="startup_footer_button" align="right">
                <a href="#branch_department" onclick="javascript:select_tab(1);" class="gray_button title_back_button" style="padding-left:17px;">Next Tab <span>&raquo;</span></a>
           </div>
        </div>
        
        <div id="tabs-2">
            <div id="c-department"></div>
           <div class="startup_footer_button pull-right" align="right">
                <a href="#employee" onclick="javascript:select_tab(2);" class="gray_button title_back_button">Next Tab <span>&raquo;</span></a>
           </div>
            <div class="startup_footer_button pull-left" align="right">
                <a href="#company_profile" onclick="select_tab(0);" class="gray_button title_back_button no-margin"><span>&laquo;</span> Previous Tab</a>
           </div>
           <div class="clear"></div>
        </div>
        
        <div id="tabs-3">
           <div id="c-employee"></div>
           <?php if($mod_package['dtr']) { ?>             
           <div class="startup_footer_button pull-right" align="right">
                <a href="#default_schedule_policy" onclick="select_tab(3);" class="gray_button title_back_button">Next Tab <span>&raquo;</span></a>
           </div>
           <?php } else{ ?>
           <div class="startup_footer_button pull-right" align="right">
                <a class="btn btn-success" href="javascript:void(0);" onclick="javascript:updateStartupXml();"><i class="icon-ok icon-white"></i> <strong>Done</strong></a>
           </div>
           <?php } ?>
           <div class="startup_footer_button pull-left" align="right">
                <a href="#branch_department" onclick="select_tab(1);" class="gray_button title_back_button no-margin"><span>&laquo;</span> Previous Tab</a>
           </div>
           <div class="clear"></div>
        </div>
        
        <?php if($mod_package['dtr']) { ?>             
        <div id="tabs-4">
            <div id="c-default-schedule"></div>
           
           <div class="startup_footer_button pull-left" align="right">
                <a href="#employee" onclick="select_tab(2);" class="gray_button title_back_button no-margin"><span>&laquo;</span> Previous Tab</a>
           </div>
           
            <?php if(!$mod_package['payroll']) { ?>
            <div class="startup_footer_button pull-right" align="right">
                <a class="btn btn-success" href="javascript:void(0);" onclick="javascript:updateStartupXml();"><i class="icon-ok icon-white"></i> <strong>Done</strong></a>
            </div>
           <?php } else { ?>
           <div class="startup_footer_button pull-right" align="right">
                <a href="#payroll_settings" onclick="select_tab(4);" class="gray_button title_back_button">Next Tab <span>&raquo;</span></a>
           </div>
           <?php } ?>
           <div class="clear"></div>
        </div>
        <?php } ?>
        
        <?php if($mod_package['payroll']) { ?>
        	<div id="tabs-5">
                <div id="c-payroll-settings-startup"></div>
                <div class="startup_footer_button pull-right" align="right">
                    <a class="btn btn-success" href="javascript:void(0);" onclick="javascript:updateStartupXml();"><i class="icon-ok icon-white"></i> <strong>Done</strong></a>
                </div>
                <div class="startup_footer_button pull-left" align="right">
                    <a href="#default_schedule_policy" onclick="select_tab(3);" class="gray_button title_back_button no-margin"><span>&laquo;</span> Previous Tab</a>
               </div>
               <div class="clear"></div>
            </div>
        <?php } ?>
        
    </div>
    <div class="clear"></div>
</div><!-- #startup_tabs_container -->
<div class="clear"></div>
<?php include_once('includes/modal_forms.php'); ?>

<script>
$(function() {	  	
	$('#tipsy_company_profile').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#tipysy_department').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#tipysy_employee').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#tipsy_schedule').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#tipsy_payroll_settings').tipsy({trigger: 'focus',html: true, gravity: 's'}); 
  });
load_company_info();
load_company_structure();
//load_employee();
//load_schedule_startup();
load_payroll_settings_startup();
</script>