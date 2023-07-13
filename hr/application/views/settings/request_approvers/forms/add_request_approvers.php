<style>
.textboxlist_requestors .textboxlist-bits{height:120px;width:105%;}
.textboxlist{width:295px;display:inline-block;}
</style>
<script>
$(function(){  
  //var jqAction  = jQuery.noConflict(); 
  $('#add_request_approvers_form').validationEngine({scroll:false});   
  $('#add_request_approvers_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          hideAddRequestApprovers();
          load_request_approvers_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });

  var t = new $.TextboxList('#requestors_id', {unique: true,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_unique_requestors_autocomplete'}

    }
  }});

  $("#btn-insert-approver").click(function(){
    var level     = $('.tr-approvers').length;  
    var new_level = level + 1;
    var new_element_id = "approvers-level-" + new_level;
    $(".append-container").append("<div id='" + new_element_id + "'></div>");
    $('#' + new_element_id).html(loading_image);
    $.get(base_url + 'settings/_load_add_approvers',{level:level},function(o) {
      $('#' + new_element_id).html(o);    
    });     
  });

  $("#btn-request-approvers-cancel").click(function(){     
    hideAddRequestApprovers();
  });
});
</script>
<div id="formcontainer">
  <form id="add_request_approvers_form" name="add_request_approvers_form" autocomplete="off" method="POST" action="<?php echo url("settings/save_request_approvers"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Add Request Approvers</h3>
        <div id="form_main">
          
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Title</td>
                       <td style="width:15%" align="left" valign="middle">: 
                        <input class="validate[required,max[50]] text-input" type="text" name="request_title" id="request_title" value="" />
                       </td>
                  </tr>
                  <?php include_once('_add_request_approvers.php'); ?>
               </table>
               <div class="append-container"></div>
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr class="tr-insert-approver">
                        <td style="width:15%" align="left" valign="middle"></td>
                        <td style="width:15%" align="left" valign="middle"><a href="javascript:void(0);" id="btn-insert-approver"><i class="icon-plus-sign"></i>Insert approver</a></td>
                  </tr>
                  
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Requestors</td>
                       <td style="width:15%" align="left" valign="middle" class="textboxlist_requestors"> 
                          <input class="validate[required] text-input" type="text" name="requestors_id" id="requestors_id" value="" />
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
                        <a id="btn-request-approvers-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

