
<div id="import_employee_wrapper" style="display:none">
<?php include 'form/import_employee_evaluation.php'; ?>
</div>


<div id="employee_search_container">

<div id="employeesearchmain">
  <!-- DONT REMOVE THIS! --><div></div><!-- -->
  <div id="search_wrapper" class="employee_basic_search searchcnt">
        <input name="search" type="text" class="curve" id="search" size="100" />       
      
      <button type="submit" class="blue_button"  onclick="javascript:searchEmployee2();"><i class="icon-search icon-white"></i> Search</button>

    </div>
   
</div><!-- #employeesearchmain -->
</div><!-- #employee_search_container -->
</div>

<?php echo $btn_import_employee_eval; ?>
<br/><br/>



  <div id="employee_datatable_wrapper"></div>

<div id="employee_wrapper"></div>
<div id="confirmation"></div>


<script>
//load_total_search('nothing');
//load_employee_datatable('nothing');
load_view_all_employee_eval_datatable();

$(function() {   
  $('#btn_listview').tipsy({trigger: 'focus',html: true, gravity: 's'});   
  $('#btn_imageview').tipsy({trigger: 'focus',html: true, gravity: 's'}); 
  $('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});  
  $('#btn_viewallarchives').tipsy({trigger: 'focus',html: true, gravity: 's'});  
  });
</script>
<input type="hidden" name="employee_hash" id="employee_hash"/>

