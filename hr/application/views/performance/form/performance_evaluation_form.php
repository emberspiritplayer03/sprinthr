<script>
$(document).ready(function() {

	$("#performance_evaluation_form").validationEngine({scroll:true});
});
	$('#performance_evaluation_form').ajaxForm({
		success:function(o) {
				
					dialogOkBox('Successfully Evaluated',{ok_url:"performance"});	
			
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	
	


</script>
<?php
//echo "<pre>";
//print_r($employee);
 ?>
<div id="dialog_performance_form"></div>
<div id="employee_search_container">
    <div class="employee_form_summary" id="formwrap">
        <div class="inner_form wider" id="form_main">
            <div id="form_default">
                <div class="action_holder action_holder_right">
                    <div id="dropholder" class="dropright btn-group pull-right"><a class="gray_button dropbutton" href="javascript:void(0);"><span><span class="dark_gear"></span></span></a>
                        <ul class="dropdown-menu"><li><a onclick="javascript:hideApplicantSummary()" href="javascript:void(0);"><i class="icon-chevron-up"></i> Hide</a></li></ul>
                    </div>
                </div>
                <h3 class="section_title">Employee Details</h3>
                <div class="clearright"></div>         
                <div class="float-left" style="width:50%">
                    <table>
                      <tbody><tr>
                        <td class="field_label">Peformance Title:</td>
                        <td><strong class="blue"><?php echo $performance_title; ?></strong></td>
                      </tr>
                      <tr>
                        <td class="field_label">Employee:</td>
                        <td><strong><?php echo $employee_name; ?></strong></td>
                      </tr>
                      <tr>
                        <td class="field_label">Reviewer:</td>
                        <td><?php echo $reviewer_name; ?></td>
                      </tr>              
                    </tbody></table>
                </div>
                <div class="float-left" style="width:50%;">
                    <table>
                        <tr>
                            <td class="field_label">Period From:</td>
                            <td><?php echo $employee->period_from; ?></td>
                          </tr>
                          <tr>
                            <td class="field_label">Period To:</td>
                            <td><?php echo $employee->period_to; ?></td>
                          </tr>
                          <tr>
                            <td class="field_label">Due Date:</td>
                            <td><?php echo $employee->due_date; ?> <i class="icon-calendar icon-fade"></i></td>
                          </tr>
                    </table>
                </div>                
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<form id="performance_evaluation_form" name="performance_evaluation_form" method="post" action="<?php echo url('performance/_save_evaluation'); ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="performance_id" value="<?php echo $performance_id; ?>">
<input type="hidden" name="employee_performance_id" value="<?php echo $employee_performance_id; ?>">
<div class="section_container">
  <table width="100%" class="formtable table_border">
  	<thead>
    <tr>
      <th width="28%" align="left" class="vertical-middle"><h2 class="no-margin"><i class="icon-list-alt icon-fade vertical-middle"></i> <small>Criteria</small></h2></th>
      <th width="14%" align="center" class="text-center vertical-middle performance_one"><strong style="font-size:14px;">Does not Meet<br />Minimum Standards</strong></th>
      <th width="12%" align="center" class="text-center vertical-middle performance_two"><strong style="font-size:14px;">Needs<br />Improvement</strong></th>
      <th width="12%" align="center" class="text-center vertical-middle performance_three"><strong style="font-size:14px;">Meets<br />Expectations</strong></th>
      <th width="12%" align="center" class="text-center vertical-middle performance_four"><strong style="font-size:14px;">Exceeds<br />Expectation</strong></th>
      <th width="12%" align="center" class="text-center vertical-middle performance_five"><strong style="font-size:14px;">Outstanding</strong></th>
      <th width="10%" align="center" class="text-center vertical-middle"><i class="icon-comment icon-fade"></i> Comment</th>
    </tr>
    </thead>
    <?php foreach($kpi as $key=>$value) {  ?>
    <tr>
      <td align="left">
      <input type="hidden" id="id_<?php echo $value->id; ?>" name="id_<?php echo $value->id; ?>" value="<?php echo $value->id; ?>">
      <input type="hidden" id="title_<?php echo $value->id; ?>" name="title_<?php echo $value->id; ?>" value="<?php echo $value->title; ?>">
      <input type="hidden" id="desc_<?php echo $value->id; ?>" name="desc_<?php echo $value->id; ?>" value="<?php echo $value->description; ?>">

	  <h4 class="blue"><?php echo $value->title; ?></h4>
	  <?php echo $value->description; ?></td>
      <td align="center" class="text-center vertical-middle performance_one"><label><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="1" class="validate[required]"></label></td>
      <td align="center" class="text-center vertical-middle performance_two"><label><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="2" class="validate[required]"></label></td>
      <td align="center" class="text-center vertical-middle performance_three"><label><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="3" class="validate[required]"></label></td>
      <td align="center" class="text-center vertical-middle performance_four"><label><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="4" class="validate[required]"></label></td>
      <td align="center" class="text-center vertical-middle performance_five"><label><input type="radio" name="rate_<?php echo $value->id; ?>" id="radio_<?php echo $value->id; ?>" value="5" class="validate[required]"></label></td>
      <td align="center"><textarea name="comment_<?php echo $value->id; ?>" id="comment_<?php echo $value->id; ?>" style="width:130px; max-width:130px; resize:vertical; height:40px;"></textarea></td>
    </tr>
    <?php } ?>
  </table>
</div>
<div align="center"><button class="blue_button" type="submit"  name="button" id="button"><i class="icon-ok icon-white"></i> Done</button></div>
</form>
