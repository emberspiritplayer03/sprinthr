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
//print_r($kpi);
 ?>
<!--<div id="dialog_performance_form"></div>
<div id="dropholder"><a class="dropbutton" onclick="javascript:loadGoto();" href="#">Goto</a>
<div id="dropcontent" class="dropcontent" >
<a href="#">Profile</a><br />
<a href="#">Performance </a></div>
</div>-->

<!--<table width="351" border="0" cellpadding="3" cellspacing="2">
  <tr>
    <td width="164"><strong>Peformance Title</strong></td>
    <td width="171"><?php echo $performance_title; ?>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Employee</strong></td>
    <td><?php echo $employee_name; ?></td>
  </tr>
  <tr>
    <td><strong>Reviewer</strong></td>
    <td><?php echo $reviewer_name; ?></td>
  </tr>
  <tr>
    <td><strong>Period From</strong></td>
    <td><?php echo $employee->period_from; ?></td>
  </tr>
  <tr>
    <td><strong>Period To</strong></td>
    <td><?php echo $employee->period_to; ?></td>
  </tr>
  <tr>
    <td><strong>Due Date</strong></td>
    <td><?php echo $employee->due_date; ?></td>
  </tr>
</table>-->
<div id="employee_search_container">
    <div class="employee_form_summary" id="formwrap">
        <div class="inner_form wider" id="form_main">
            <div id="form_default">
                <!--<div class="action_holder action_holder_right">
                    <div id="dropholder" class="dropright btn-group pull-right"><a class="gray_button dropbutton" href="javascript:void(0);"><span><span class="dark_gear"></span></span></a>
                        <ul class="dropdown-menu"><li><a onclick="javascript:hideApplicantSummary()" href="javascript:void(0);"><i class="icon-chevron-up"></i> Hide</a></li></ul>
                    </div>
                </div>-->
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
<?php if($summary){ ?>
	<div class="performance_average text-center">
    	<h1>Average: <strong class="blue"><?php echo $performance_average; ?>%</strong></h1>
    </div>
    <div class="performance_ratings">
        <ul class="inline">        
		<?php 
			foreach($summary as $key => $value){
				if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_1]) !== false){
					$class = 'class="performance_one"';
				}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_2]) !== false){
					$class = 'class="performance_two"';
				}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_3]) !== false){
					$class = 'class="performance_three"';
				}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_4]) !== false){
					$class = 'class="performance_four"';
				}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_5]) !== false){
					$class = 'class="performance_five"';
				}
        ?>       	
            <li <?php echo $class; ?>>
            	<div class="star_rating"></div>
            	<h3>
					<?php
						$v = explode(":",$value);						
						echo $v[0] . ': <strong class="label">' . $v[1] . '</strong>';
					?>                
                </h3>
            </li>
        <?php } ?>
        <div class="clear"></div>
        </ul>
        <div class="clear"></div>
    </div>
<?php } ?>
<div class="clear"></div>
<form id="performance_evaluation_form" name="performance_evaluation_form" method="post" action="<?php echo url('performance/_save_evaluation'); ?>">
  <input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="performance_id" value="<?php echo $performance_id; ?>">
<input type="hidden" name="employee_performance_id" value="<?php echo $employee_performance_id; ?>">
<div class="section_container">
  <table width="100%" class="formtable table_border">
  	<thead>
    <tr>
      <th width="30%" class="vertical-middle"><h2 class="no-margin"><i class="icon-list-alt icon-fade vertical-middle"></i> <small>Criteria</small></h2></th>
      <th width="37%" class="text-center"><strong style="font-size:14px;"><i class="icon-star icon-fade"></i> Rating(s)</strong></th>
      <th width="31%" align="center" class="text-center vertical-middle"><i class="icon-comment icon-fade"></i> Comment</th>
    </tr>
    </thead>
    <?php 
	$x=1;
	
	$total = count($kpi);
	while($x<=$total){  ?>
    <tr>
      <td align="left">
      <input type="hidden" id="id_<?php echo $value->id; ?>" name="id_<?php echo $value->id; ?>" value="<?php echo $value->id; ?>">
      <input type="hidden" id="title_<?php echo $value->id; ?>" name="title_<?php echo $value->id; ?>" value="<?php echo $value->title; ?>">
      <input type="hidden" id="desc_<?php echo $value->id; ?>" name="desc_<?php echo $value->id; ?>" value="<?php echo $value->description; ?>">

	  <h4 class="blue"><?php echo $kpi['kpi_'.$x]['title']; ?></h4>
      <?php echo $kpi['kpi_'.$x]['desc']; ?></td>
      <?php 
	  	if($kpi['kpi_'.$x]['result']=='Outstanding') {
			$class_performance_style = 'class_five';
		}else if ($kpi['kpi_'.$x]['result']=='Exceeds Expectation') {
			$class_performance_style = 'class_four';
		}else if ($kpi['kpi_'.$x]['result']=='Meets Expectations') {
			$class_performance_style = 'class_three';
		}else if ($kpi['kpi_'.$x]['result']=='Needs Improvement') {
			$class_performance_style = 'class_two';
		}else if ($kpi['kpi_'.$x]['result']=='Does not Meet Minimum Standards') {
			$class_performance_style = 'class_one';
		}
	  ?>
      <td align="left" class="text-center vertical-middle <?php echo $class_performance_style; ?>"><strong style="font-size:13px;"><?php echo $kpi['kpi_'.$x]['result']; ?></strong></td>
      <td align="left"><?php echo (is_array($kpi['kpi_'.$x]['comment'])==1)? '&nbsp;': $kpi['kpi_'.$x]['comment'] ; ?></td>
    </tr>
    <?php 
	$x++;
	} ?>
  </table>
</div>
<div align="center"><button class="blue_button" type="submit"  name="button" id="button"><i class="icon-ok icon-white"></i> Done</button></div>
</form>
