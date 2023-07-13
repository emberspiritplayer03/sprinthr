<script>
$(function(){
  $("#tax_date_from").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true,
    onSelect: function(date){
         $("#tax_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
      }
  });

  $("#tax_date_to").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true   
  });  

  $(".contri-tax-search-keywords-employee-container").hide();

  $("#contri-tax-all").click(function(){
    var search_by = $("#contri-tax-search-by").val();
    if( $(this).is(':checked') ){
      if( search_by == 'department' ){
        $(".contri-tax-search-keywords-department-container").hide();
      }else{
        $(".contri-tax-search-keywords-employee-container").hide();
      }
    }else{
      if( search_by == 'department' ){
        $(".contri-tax-search-keywords-department-container").show();
      }else{
        $(".contri-tax-search-keywords-employee-container").show();
      }
    }
  });

  $("#contri-tax-search-by").change(function(){
    var search_by = $(this).val();
    if( search_by == 'department' ){
      $(".contri-tax-search-keywords-employee-container").hide();
      $(".contri-tax-search-keywords-department-container").show();
      $("span#contri-tax-all-caption").html("All Departments");
    }else{
      $(".contri-tax-search-keywords-department-container").hide();
      $(".contri-tax-search-keywords-employee-container").show();
      $("span#contri-tax-all-caption").html("All Employees");
    }
    $("#contri-tax-all").attr("checked", false);
  });

  $('#tax_form').validationEngine({scroll:false});     

  var emp = new $.TextboxList('#contri-tax-search-keywords-employee', {unique: true, plugins: {
      autocomplete: {
        minLength: 3,
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
      
      }
    }});

  var dept = new $.TextboxList('#contri-tax-search-keywords-department', {unique: true, plugins: {
      autocomplete: {
        minLength: 3,
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'autocomplete/ajax_get_department_autocomplete'}
      
      }
    }});
});
</script>
<h2><?php echo $title;?></h2>

<form id="tax_form" name="form1" method="post" action="<?php echo url($action); ?>">
<div id="form_main" class="employee_form">
  <div id="form_default">
  <table width="100%">
    <tr>
      <td class="field_label">From:</td>
      <td><input class="text-input validate[required]" type="text" name="date_from" id="tax_date_from" /></td>
    </tr>
    <tr>
      <td class="field_label">To:</td>
      <td><input class="text-input validate[required]" class="text-input" type="text" name="date_to" id="tax_date_to" /></td>
    </tr>
    <tr>
      <td class="field_label">Search By</td>
      <td>
        <select id="contri-tax-search-by" name="contri-search-by">
          <option value="department">Department</option>
          <option value="employee">Employee</option>
        </select>
      </td>
    </tr>
    <tr>
      <td class="field_label"></td>
      <td>
        <div class="contri-tax-search-keywords-employee-container">
          <input class="text-input" type="text" name="contri_search_keywords_employee" id="contri-tax-search-keywords-employee" />
        </div>
        <div class="contri-tax-search-keywords-department-container">
          <input class="text-input" type="text" name="contri_search_keywords_department" id="contri-tax-search-keywords-department" />
        </div>        
        <label class="checkbox">
          <input type="checkbox" name="contri_all" id="contri-tax-all" class="contri-all"><span id="contri-tax-all-caption">All Departments</span>
        </label>
      </td>
    </tr>
    <!-- <tr>
      <td class="field_label">Submission Date:</td>
      <td><input class="text-input" type="text" name="submission_date" id="submission_date" /></td>
    </tr> -->
  </table>
  </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
      <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
