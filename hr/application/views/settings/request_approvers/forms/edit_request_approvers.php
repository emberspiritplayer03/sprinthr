<style>
.textboxlist_requestors .textboxlist-bits{height:120px;width:105%;}
.textboxlist{width:295px;display:inline-block;}
</style>
<script>
$(function(){  
  //var jqAction  = jQuery.noConflict(); 
  $('#edit_request_approvers_form').validationEngine({scroll:false});   
  $('#edit_request_approvers_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          hideEditRequestApprovers();
          load_request_approvers_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Updating...');
      }
  });

  var t_requestors = new $.TextboxList('#requestors_id', {unique: true,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_unique_requestors_autocomplete'}

    }
  }});

 <?php echo $ini_requestors_script; ?>

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
    hideEditRequestApprovers();
  });
});
</script>
<div id="formcontainer">
  <form id="edit_request_approvers_form" name="edit_request_approvers_form" autocomplete="off" method="POST" action="<?php echo url($action); ?>">
  <input type="hidden" name="eid" value="<?php echo $eid; ?>" />
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Edit Request Approvers</h3>
        <div id="form_main">
          
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Title</td>
                       <td style="width:15%" align="left" valign="middle">: 
                        <input class="validate[required,max[50]] text-input" type="text" name="request_title" id="request_title" value="<?php echo $header['title'] ?>" />
                       </td>
                  </tr>                  
               </table>
               <div class="append-container">
                  <?php include_once('_edit_request_approvers.php'); ?>
               </div>
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr class="tr-insert-approver">
                        <td style="width:15%" align="left" valign="middle"></td>
                        <td style="width:15%" align="left" valign="middle"><a href="javascript:void(0);" id="btn-insert-approver"><i class="icon-plus-sign"></i>Insert approver</a></td>
                  </tr>
                  
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Requestors</td>
                       <td style="width:15%" align="left" valign="middle" class="textboxlist_requestors">: 
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

