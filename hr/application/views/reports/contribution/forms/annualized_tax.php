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

  var emp = new $.TextboxList('#contri-tax-search-keywords-employee', {unique: true, max: 1, plugins: {
      autocomplete: {
        minLength: 3,        
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
      
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
      <td class="field_label">Year</td>
      <td>           
          <select style="width:20%;" name="start_year" id="start_year">
          <?php for($x = $start_year; $x <= $end_year; $x++){ ?>
            <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
          <?php } ?>                                      
          </select>          
       </td>
    </tr>       
    <tr>
      <td class="field_label">Enter employee name</td>
      <td>
        <div class="contri-tax-search-keywords-employee-container">
          <input class="text-input" type="text" name="contri_search_keywords_employee" id="contri-tax-search-keywords-employee" />
        </div>        
      </td>
    </tr>  
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
