<style>
.textboxlist_requestors .textboxlist-bits{height:120px;width:105%;}
.textboxlist{width:295px;display:inline-block;}
</style>
<script>
$(function(){  
  //var jqAction  = jQuery.noConflict(); 
  $('#add_ip_address_form').validationEngine({scroll:false});   
  $('#add_ip_address_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          $("#btn-add-ip-address-cancel").trigger('click');
          load_ip_address_list_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });

  var t = new $.TextboxList('#employee_id', {unique: true,max:1,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete_not_in_allowed_ip'}

    }
  }});

  $("#btn-add-ip-address-cancel").click(function(){     
    $(".data-table-container").show();
    $(".ip-address-form-container").hide();
    $('.ip-address-form-container').html('');
  });
});
</script>
<div id="formcontainer">
  <form id="add_ip_address_form" name="add_ip_address_form" autocomplete="off" method="POST" action="<?php echo url("settings/save_ip_address"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Add IP Address</h3>
        <div id="form_main">
          
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">IP Address</td>
                       <td style="width:15%" align="left" valign="middle">: 
                        <input class="validate[required] text-input" type="text" name="ip_address" id="ip_address" value="" />
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Employee Name</td>
                       <td style="width:15%" align="left" valign="middle">: 
                        <input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" />
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
                        <a id="btn-add-ip-address-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

