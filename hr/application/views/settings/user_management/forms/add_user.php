<style>
  div.textboxlist{display:inline-block !important;}
  span#username-info-box{display:inline-block;width:auto;margin-left:6px;}
</style>
<script>
$(function(){ 
  var jqAction  = jQuery.noConflict();  

  $('#add_user_form').validationEngine({scroll:false});   
  $('#add_user_form').ajaxForm({
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
        showLoadingDialog('Saving...');
      }
  });

  $("#btn-user-cancel").click(function(){
    showUserRoleContainer();
  });
  
  validateUsername("username","username-info-box");  

  var t = new $.TextboxList('#employee_id', {unique: true,max:1,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}

    }
  }});
});
</script>
<div id="formcontainer">
  <form id="add_user_form" name="add_user_form" autocomplete="off" method="POST" action="<?php echo url("settings/save_user"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Add User</h3>
        <div id="form_main">
          
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Employee Name</td>
                       <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" /></td>
                  </tr>
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
                       <td style="width:15%" align="left" valign="middle">Username</td>
                       <td style="width:15%" align="left" valign="middle">: 
                          <input class="validate[required,custom[onlyLetterNumber],minSize[4]] input-large" type="text" name="username" id="username" value="" /><br />
                          <span id="username-info-box"></span>
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Password</td>
                       <td style="width:15%" align="left" valign="middle">: <input class="validate[required,minSize[6]] input-large" type="password" name="password" id="password" value="" /></td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Retype Password</td>
                       <td style="width:15%" align="left" valign="middle">: <input class="validate[required,equals[password]] input-large" type="password" name="repassword" id="repassword" value="" /></td>
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

