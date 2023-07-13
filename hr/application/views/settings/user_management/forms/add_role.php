<style>
#form_default textarea{ width:100%;height:75px;}
.accordion-heading{background-color:#198cc9;}
a.accordion-toggle{color:#FFFFFF;font-size:14px;text-decoration:none;}
.accordion-inner{background-color:#E3E3E3;}
.mod-action, .sub-mod-action{width:200px;}
ul.sub-mod-list, ul.mod-list{list-style: none;}
ul.sub-mod-list{margin-left:68px;}
ul.sub-mod-list li{margin-left:10px;margin-bottom:10px;}
.mod-caption{width:28%;display:inline-block;}
.mod-input{width:200px;display:inline;}
li.sub-module{background-color:#ffffff;}
li.main-module{background-color:#198cc9;color: #ffffff;height:29px;margin-bottom: 5px;padding:11px;font-weight: bold;}
li.enable-disable-module{background-color:#575F6B;}
</style>
<script>
$(function(){
  var no_access = "<?php echo $no_access; ?>";
  var jqAction  = jQuery.noConflict();  

  $('#add_role_frm').validationEngine({scroll:false});   
  $('#add_role_frm').ajaxForm({
      success:function(o) {        
        
        dialogOkBox(o.message,{});
        showUserRoleContainer();
        load_roles_dt();

        $('#token').val(o.token);
      },
      dataType:'json',
      beforeSerialize : function() {
        $(".remove-main-element").empty(); 
        $(".remove-sub-element").empty(); 
      },
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });

  $("#btn-role-cancel").click(function(){
    showUserRoleContainer();
  });

  $(".mod-action").click(function(){
    var mod_action = $(this).val();
    if( mod_action == no_access ){            
      $(this).parent("div").parent("li").next("li.sub-module").addClass("remove-sub-element").hide();
    }else{
      $(this).parent("div").parent("li").next("li.sub-module").removeClass("remove-sub-element").show();
    }
  });

  $("#enable-hr-module").click(function(){
    if( $(this).is(':checked') ){
      $(".hr-module-list").show();
      $(".hr-module-list").removeClass("remove-main-element");
    }else{
      $(".hr-module-list").hide();
      $(".hr-module-list").addClass("remove-main-element");
    }
  });

  $("#enable-payroll-module").click(function(){
    if( $(this).is(':checked') ){
      $(".payroll-module-list").show();
      $(".payroll-module-list").removeClass("remove-main-element");
    }else{
      $(".payroll-module-list").hide();
      $(".payroll-module-list").addClass("remove-main-element");
    }
  });

  $("#enable-dtr-module").click(function(){
    if( $(this).is(':checked') ){
      $(".dtr-module-list").show();
      $(".dtr-module-list").removeClass("remove-main-element");
    }else{
      $(".dtr-module-list").hide();
      $(".dtr-module-list").addClass("remove-main-element");
    }
  });

  $("#enable-employee-module").click(function(){
    if( $(this).is(':checked') ){
      $(".employee-module-list").show();
      $(".employee-module-list").removeClass("remove-main-element");
    }else{
      $(".employee-module-list").hide();
      $(".employee-module-list").addClass("remove-main-element");
    }
  });

   $("#enable-audit_trail-module").click(function(){
    if( $(this).is(':checked') ){
      $(".audit_trail-module-list").show();
      $(".audit_trail-module-list").removeClass("remove-main-element");
    }else{
      $(".audit_trail-module-list").hide();
      $(".audit_trail-module-list").addClass("remove-main-element");
    }
  });

});
</script>
<div id="formcontainer">
  <form id="add_role_frm" name="add_role_frm" autocomplete="off" method="POST" action="<?php echo url("settings/save_role"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Add Role</h3>
        <div id="form_main">
          
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Role Name</td>
                       <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="role_name" id="role_name" value="" /></td>
                  </tr>
                  <tr>
                    <td style="width:15%" align="left" valign="middle">Description</td>
                    <td style="width:85%" align="left" valign="middle">
                        : <textarea class="text-input" name="role_description"></textarea>
                        <div id="_schedule_loading_wrapper" style="display:inline; margin-left:10px;"></div>
                        <div id="show_specific_schedule_wrapper"></div>
                    </td>
                  </tr>
                </table>
            </div>            
            <p><b>Specify below what modules that this role can do</b></p>
            <hr />
            <div class="role-modules-list">
                <div class="accordion" id="accordion1">
                    <div class="accordion-group">
                        <div class="accordion-heading">                            
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                            <b>HR Modules</b>
                            </a>
                        </div>
                        <div id="collapseOne" class="accordion-body collapse in">
                            <div class="accordion-inner">                                
                                <?php include_once('_mod_hr.php'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                            <b>DTR Module</b>
                            </a>
                        </div>
                        <div id="collapseTwo" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <?php include_once('_mod_dtr.php'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
                            <b>Payroll Modules</b>
                            </a>
                        </div>
                        <div id="collapseThree" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <?php include_once('_mod_payroll.php'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion4" href="#collapseFour">
                            <b>Employee Modules</b>
                            </a>
                        </div>
                        <div id="collapseFour" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <?php include_once('_mod_employee.php'); ?>
                            </div>
                        </div>
                    </div>
                    <?php if($user_position == 'Super Admin'){?>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion5" href="#collapseFive">
                            <b>Audit Trail</b>
                            </a>
                        </div>
                        <div id="collapseFive" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <?php include_once('_mod_audit_trail.php'); ?>
                            </div>
                        </div>
                    </div>
                  <?php }?>
                </div>
            </div>

            <div id="form_default" class="form_action_section">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td class="field_label">&nbsp;</td>
                        <td>
                        <input type="submit" value="Save" class="curve blue_button" />
                        <a id="btn-role-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

