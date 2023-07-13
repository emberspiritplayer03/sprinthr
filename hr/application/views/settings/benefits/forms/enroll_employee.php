<style>
.textboxlist{display:inline-block;}
.apply-to-container p{ font-size:12px;padding:8px;display: block;width:97%;height:20px;background-color:#E3E3E3;font-weight: bold}
.apply-to-container table{margin-left:37px;}
.custom-criteria-date-range-box{padding:7px;background-color: #e3e3e3;border:solid 1px #999999;margin-top: 7px;}
ul.benefit-criteria li{list-style: none;display: inline-block;width:231px;margin:10px;vertical-align: top;}
#employee-list, #exclude-employee-list{
  height: 220px;
  width: 220px;
  background-color:#ecf0f1;
  overflow-y:auto;
}
#employee-list ul{
  list-style-type:none;
}
#employee-list ul li, #exclude-employee-list ul li{
  padding:0;
  font-size: 0.75rem;
  background-color: #e4e4e4;
  border: 1px solid #aaa;
  border-radius: 4px;
  cursor: default;
  float: left;
  margin-right: 5px;
  margin-top: 5px;
  padding: 0 5px;
  cursor: pointer;
  min-width:50%;
}

#employee-list ul li::selection, #exclude-employee-list ul li::selection {
    color: none;
    background: none;
}
</style>
<script>
sessionStorage.setItem("employment_status_id", "")
$(function(){
  var jqAction  = jQuery.noConflict();  
  $(".cls-days-leave").hide();
  $(".cls-days-absent").hide();
  $(".cls-days-absent-and-leave").hide();

  $('#enroll_employee_form').validationEngine({scroll:false});   
  $('#enroll_employee_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          showBenefitsContainer();
          load_benefits_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });

  $("#btn-benefit-cancel").click(function(){   
    showBenefitsContainer();
  });

  $("#no-absent").click(function(){
    if( $(this).prop("checked") ){      
      $(".days-absent").prop("checked",false);
      $(".days-absent").prop("disabled",true);
      $("#id-days-absent").removeClass("validate[required,custom[number],min[1]]");
      $("#id-from-days-absent").removeClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-absent").removeClass("validate[required,custom[number],min[1]]");
      $("#id-days-absent").val("");
      $(".cls-days-absent").hide();
    }else{
      $(".days-absent").prop("disabled",false);      
      $(".cls-days-absent").hide();
    }
  });

  $(".days-absent").click(function(){
    if( $(this).prop("checked") ){
      $("#id-days-absent").addClass("validate[required,custom[number],min[1]]");
      $("#id-from-days-absent").addClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-absent").addClass("validate[required,custom[number],min[1]]");
      $(".cls-days-absent").show();

      $(".days-absent-and-leave").prop("checked",false);
      $(".days-absent-and-leave").prop("disabled",true);

    }else{      
      $(".days-absent-and-leave").prop("disabled",false);

      $("#id-days-absent").removeClass("validate[required,custom[number],min[1]]");
      $("#id-from-days-absent").removeClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-absent").removeClass("validate[required,custom[number],min[1]]");
      $(".cls-days-absent").hide();
    }
  });

  $(".days-absent-and-leave").click(function(){
    if( $(this).prop("checked") ){
      $(".days-absent").prop("checked",false);
      $(".days-absent").prop("disabled",true);
      $(".days-leave").prop("checked",false);
      $(".days-leave").prop("disabled",true);

      $("#id-days-absent-and-leave").addClass("validate[required,custom[number],min[0]]");
      $("#id-from-days-absent-and-leave").addClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-absent-and-leave").addClass("validate[required,custom[number],min[1]]");

      $(".cls-days-absent-and-leave").show();
      $(".cls-days-absent").hide();
      $(".cls-days-leave").hide();
    }else{
      $(".days-absent").prop("disabled",false);      
      $(".days-leave").prop("disabled",false);      

      $("#id-days-absent-and-leave").removeClass("validate[required,custom[number],min[0]]");
      $("#id-from-days-absent-and-leave").removeClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-absent-and-leave").removeClass("validate[required,custom[number],min[1]]");
      
      $(".cls-days-absent-and-leave").hide();
    }
  });

  $(".days-leave").click(function(){
    if( $(this).prop("checked") ){
      $(".days-absent-and-leave").prop("checked",false);
      $(".days-absent-and-leave").prop("disabled",true);

      $("#id-days-leave").addClass("validate[required,custom[number],min[1]]");   
      $("#id-from-days-leave").addClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-leave").addClass("validate[required,custom[number],min[1]]");   
      $(".cls-days-leave").show();
    }else{      
      $(".days-absent-and-leave").prop("disabled",false);

      $("#id-days-leave").removeClass("validate[required,custom[number],min[1]]");
      $("#id-from-days-leave").removeClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-leave").removeClass("validate[required,custom[number],min[1]]");   
      $(".cls-days-leave").hide();
    }
  });

  $("#no-leave").click(function(){
    if( $(this).prop("checked") ){      
      $(".days-leave").prop("checked",false);    
      $(".days-leave").prop("disabled",true);
      $("#id-days-leave").removeClass("validate[required,custom[number],min[1]]");
      $("#id-from-days-leave").removeClass("validate[required,custom[number],min[1]]");
      $("#id-to-days-leave").removeClass("validate[required,custom[number],min[1]]");   
      $("#id-days-leave").val("");
      $(".cls-days-leave").hide();
    }else{
      $(".days-leave").prop("disabled",false);
      $(".cls-days-leave").hide();
    }
  });

  var t = new $.TextboxList('#employee_id', {
    unique: true,
    plugins: {
      autocomplete: {
        minLength: 2,       
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'autocomplete/ajax_get_employees_not_enrolled_to_benefit_id?eid=<?php echo $eid; ?>'}
      }
  }});

  var t = new $.TextboxList('#employment_status_id', {
    unique: true,
    plugins: {
      autocomplete: {
        minLength: 2,       
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'autocomplete/ajax_get_employment_status_not_enrolled_to_benefit_id?eid=<?php echo $eid; ?>'}
      }
  }});

  var t = new $.TextboxList('#dept_section_id', {
    unique: true,
    plugins: {
      autocomplete: {
        minLength: 2,       
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'autocomplete/ajax_get_dept_section_not_enrolled_to_benefit_id?eid=<?php echo $eid; ?>'}
      }
  }});
  
  $("#apply_to_all_employees").change(function(){
     var apply_to = $(this).val();    
     if(apply_to == "<?php echo $all_yes ?>"){
      $(".apply-to-tr").hide();
     }else{
      $(".apply-to-tr").show();
     }
  });
  
  //function that will fetch emploiyees based on selected employment status
  const fetchEmployeeByEmploymentStatus = (employment_status) =>{
    $.get(base_url + 'settings/get_employee_by_employment_status',{employment_status},
        (data)=>{
          var employees = JSON.parse(data);
          // _employee-container  
          if(employees!==null){
            if(employees.length > 0){
              let output = "";
              employees.forEach(employee=>{
                output += "<li id='employee_"+employee.id+"' onclick='addExcludeEmployee("+employee.id+", this)'>"+employee.name+"</li>";
              })
              $('#_employee-container').html(output);
            }
          }else{
            $('#_employee-container').empty();
          }
          
        }
      );
  }

  $("#exclude-employee-panel").hide();
  $(".exclude-employee-container").hide();
  $("#employment_status_exclude_employee").change(()=>{
    if($("#employment_status_exclude_employee").is(":checked")){
      let employment_status = $("#employment_status_id").val();
      fetchEmployeeByEmploymentStatus(employment_status);
      $("#exclude-employee-panel").show();
    }else{  
      $("#exclude-employee-panel").hide();
      $("#excluded-employee-container").empty();
    }
  })

  
  
  $('#employee-status-container').bind('DOMSubtreeModified', function(){
    if($("#employment_status_id").val() !== ""){
      $(".exclude-employee-container").show();
    }
    else{
      $(".exclude-employee-container").hide();
      $("#employment_status_exclude_employee").prop('checked', false);
      $("#exclude-employee-panel").hide();
    } 
  });

  //Onchange for employment_status_id
  setInterval(()=>{
    let employment_status = $("#employment_status_id").val();
    
    if(sessionStorage.getItem("employment_status_id") !== employment_status){
      sessionStorage.setItem("employment_status_id", employment_status)
      if($("#employment_status_exclude_employee").is(":checked")){
        fetchEmployeeByEmploymentStatus(employment_status);
      }
    }
   
  }, 500);
});


</script>
<div id="formcontainer">
  <form id="enroll_employee_form" name="enroll_employee_form" autocomplete="off" method="POST" action="<?php echo url("settings/enroll_to_benefit"); ?>">
  <input type="hidden" id="eid" name="eid" value="<?php echo $eid; ?>" />
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Enroll to Benefit</h3>
        <div id="form_main">
          
            <div id="form_default">   
                <p style="font-weight:bold;font-size:11px;">Note : Applying to all employees will remove all previously enrolled employees</p>   
               <table width="100%" border="0" cellspacing="1" cellpadding="2">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Benefit Name</td>
                       <td style="width:15%" align="left" valign="middle">: 
                        <input class="text-input" type="text" readonly="readonly" value="<?php echo $benefit_name; ?>" />
                       </td>
                  </tr>   
                  <tr>
                    <td colspan="2">
                        <div class="apply-to-container">
                          <p>Apply benefit to :</p>
                            <table style="width:90%">
                              <tr class="apply-to-tr">
                                   <td style="width:15%" align="left" valign="middle">Employee </td>
                                   <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" />                        
                                   </td>
                              </tr>
                              <tr class="apply-to-tr">
                                   <td style="width:15%" align="left" valign="middle">Department / Section </td>
                                   <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="dept_section_id" id="dept_section_id" value="" />                        
                                   </td>
                              </tr>
                              <tr class="apply-to-tr" id="employee-status-container" >
                                   <td style="width:15%" align="left" valign="middle">Employment status </td>
                                   <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="employment_status_id" id="employment_status_id" value="" />                        
                                   </td>
                              </tr>
                              <tr class="exclude-employee-container">
                                   <td style="width:15%" align="left" valign="middle"></td>
                                   <td style="width:15%" align="left" valign="middle">
                                   <label class="checkbox">Exclude Employees<input id="employment_status_exclude_employee" type="checkbox" name="employment_status_exclude_employee" /></label>                        
                                   </td>
                              </tr>
                              <tr id="exclude-employee-panel">
                                   <td style="width:15%" align="left" valign="middle">
                                    Employees:
                                    <div id="employee-list">
                                        <ul id="_employee-container">
                                          
                                        </ul>
                                    </div>
                                   </td>
                                   <td style="width:15%" align="left" valign="middle">
                                    Excluded:
                                      <div id="exclude-employee-list">
                                        <ul id="excluded-employee-container">

                                        </ul>
                                      </div>
                                   </td>
                              </tr>
                              <tr>
                                   <td style="width:15%" align="left" valign="middle">Apply to all employees</td>
                                   <td style="width:15%" align="left" valign="middle">: 
                                      <select id="apply_to_all_employees" name="apply_to_all_employees" style="width:29%;">
                                        <option value="<?php echo $all_yes; ?>"><?php echo $all_yes; ?></option>
                                        <option value="<?php echo $all_no; ?>" selected="selected"><?php echo $all_no; ?></option>
                                      </select>
                                    </td>
                              </tr>
                            </table>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <div class="apply-to-container">
                        <p>Criteria (optional)</p>
                        <ul class="benefit-criteria">
                          <?php 
                            foreach($criteria_options as $c){ 
                              $ref_id = str_replace(" ", "-", $c);
                              $ref_id = strtolower($ref_id);
                          ?>
                            <li><label class="checkbox"><input id="<?php echo $ref_id; ?>" type="checkbox" name="criteria[<?php echo $c; ?>]" /><?php echo $c; ?></label></li>
                          <?php } ?>

                          <?php 
                            foreach( $custom_criteria_options as $cc ){ 
                              $ref_id = str_replace(" ", "-", $cc);
                              $ref_id = strtolower($ref_id);
                              $obj_id = "id-" . str_replace(" ","-",trim(strtolower($cc)));
                              $obj_class = "cls-{$ref_id}";
                              $obj_id_from = "id-from-{$ref_id}";
                              $obj_id_to   = "id-to-{$ref_id}";
                          ?>
                            <li class="form-inline"><label class="checkbox"><input class="<?php echo $ref_id; ?>" type="checkbox" name="custom_criteria[<?php echo $cc; ?>]" /><?php echo $cc; ?></label><input id="<?php echo $obj_id; ?>" name="custom_criteria_value[<?php echo $cc; ?>]" style="width:18%;margin-left:6px;" type="text" placeholder="Value" />
                              <div class="custom-criteria-date-range-box <?php echo $obj_class; ?>">
                                Covered Days : <input id="<?php echo $obj_id_from; ?>" type="text" name="custom_criteria_from[<?php echo $cc; ?>]" placeholder="From" style="width:15%;" /> 
                                to <input id="<?php echo $obj_id_to; ?>" type="text" name="custom_criteria_to[<?php echo $cc; ?>]" placeholder="To" style="width:15%;" />
                              </div>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
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
                        <a id="btn-benefit-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>
