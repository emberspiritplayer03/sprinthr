<script>
$(function(){

  $(".contri-all-confi").click(function(){
    var search_by = $("#contri-tax-search-by").val();
    if( $(this).is(':checked') ){
      $('.contri-all-nonconfi').prop('checked', false); 
      $(".list-all-employees-container").hide();
      $("#employee_n").hide();
      $("#signatory_n").hide();
    }else{
      $(".list-all-employees-container").show();
      $("#employee_n").show();
      $("#signatory_n").show();
    }
  });

   $(".contri-all-nonconfi").click(function(){
    var search_by = $("#contri-tax-search-by").val();
    if( $(this).is(':checked') ){
      $('.contri-all-confi').prop('checked', false); 
      $(".list-all-employees-container").hide();
      $("#employee_n").hide();
      $("#signatory_n").hide();
    }else{
      $(".list-all-employees-container").show();
      $("#employee_n").show();
      $("#signatory_n").show();
    }
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
        <select name="year_selected">
          <?php for($start = $start_year; $start<= date('Y'); $start++){ ?>
            <option value="<?php echo $start; ?>"><?php echo $start; ?></option>
          <?php } ?>
        </select>
      </td>
    </tr>     
    <tr>
      <td class="field_label"><div id="employee_n">Employee Name: </div></td>
      <td>
        <div class="list-all-employees-container">
          <input class="text-input" type="text" name="contri_search_keywords_employee" id="contri-tax-search-keywords-employee" />          
        </div>
        <label class="checkbox">
          <input type="checkbox" name="contri_all_confi" class="contri-all-confi"><span id="contri-tax-all-confi-caption">All Confi Employees</span>          
        </label>
        <label class="checkbox">          
          <input type="checkbox" name="contri_all_nonconfi" class="contri-all-nonconfi"><span id="contri-tax-all-nonconfi-caption">All Non-Confi Employees</span>
        </label>
      </td>
    </tr>   
    <tr>
      <td class="field_label">Employment Status</td>
      <td>
        <select name="employment_status_id">
          <option value="">All</option>
          <?php foreach($employment_status as $emp_s){ ?>
            <option value="<?php echo $emp_s->getId(); ?>"><?php echo $emp_s->getStatus(); ?></option>
          <?php } ?>
        </select>
      </td>
    </tr>     
    <tr>
      <td class="field_label"><div id="signatory_n">Signatory</div></td>
      <td>
        <div class="list-all-employees-container">
          <input class="text-input" type="text" name="form_signatory" value="Hideaki Kanashima" />          
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
