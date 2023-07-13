<script>
$(function(){
  var eid = "<?php echo $eid; ?>";
  loadEmployeeBenefits(eid);

});
</script>
<h2 class="field_title"><?php echo $title_dependent; ?>
<?php //echo $btn_add_benefit; ?>
</h2>
<div id="add_benefit_form_wrapper"></div>
<div id="benefit_table_wrapper"></div>