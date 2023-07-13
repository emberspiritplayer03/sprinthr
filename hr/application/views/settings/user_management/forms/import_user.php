<style>
  div.textboxlist{display:inline-block !important;}
  span#username-info-box{display:inline-block;width:auto;margin-left:6px;}
</style>
<script>
$(function(){ 
  var jqAction  = jQuery.noConflict();  

  $('#import_user_form').validationEngine({scroll:false});   
  $('#import_user_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          showUserRoleContainer();
          load_user_management_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Importing...');
      }
  });

  $("#btn-user-cancel").click(function(){
    showUserRoleContainer();
  });

});
</script>e
<div id="formcontainer">
  <form id="import_user_form" name="import_user_form" method="post" enctype="multipart/form-data" action="<?php echo url("settings/_import_employee_user_excel"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Import User</h3>
        <div id="form_main">        
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2">

                  <tr>
                       <td style="width:15%" align="left" valign="middle">Role</td>
                       <td style="width:15%" align="left" valign="middle">: 
                          <select id="role" name="role" style="width:312px;" class="validate[required] input-large">
                            <?php foreach($roles as $role){ ?>
                              <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                            <?php } ?>
                          </select>                          
                        </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">&nbsp;</td>
                       <td style="width:15%" align="left" valign="middle">&nbsp;
                          <input type="file" name="employee_user_file" id="employee_user_file" /><br />
                          <small>
                            <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/user_management/employee_user_template.xls"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download sample template</a>
                          </small>
 
                       </td>
                  </tr>            
                </table>
            </div>            
            <div id="form_default" class="form_action_section">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td class="field_label">&nbsp;</td>
                        <td>
                        <input type="submit" value="Save" class="curve blue_button" />
                        <a id="btn-user-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

