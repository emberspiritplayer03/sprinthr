<div id="hrplist_container">
<div class="container_12">
	<div class="col_1">
        <div class="inner">
            <div class="section reminder_area">
            	<div class="btn-group float-right">
                    <a title="Tabular View" id="btn_listview" class="btn btn-small" href="javascript:loadTabularTotalApplicantByYear();">&nbsp;&nbsp;<i class="icon-align-justify"></i>&nbsp;&nbsp;</a>
                    <a title="Graphical View" id="btn_imageview" class="btn btn-small" href="javascript:loadGraphicalTotalApplicantByYear();">&nbsp;&nbsp;<i class="icon-picture"></i>&nbsp;&nbsp;</a>                    
                </div>
                <input type="hidden" name="recruitment_action_holder" id="recruitment_action_holder" value="tabular" />
                <h2 class="sectiontab_title"><span class="icongear"></span>Number of Applicants By Year 
                  <select name="year" id="year" onchange="javascript:recruitmentSummaryAction();">
                  <?php 
				  $x=1;
				  $year = date("Y");
				  while($x<=10) { ?>
                    <option value="<?php echo $year; ?>" ><?php echo $year; ?></option>
                    <?php  
					$x++;
					$year--;
					 ?>
                  <?php } ?>
                  </select>
                </h2>
                <div id="employee_by_year_wrapper"></div>
                
            </div>
          
        </div>
  	</div>
 
</div>
<script>
loadTabularTotalApplicantByYear();
$(function() {	 
 	$('#btn_listview').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_imageview').tipsy({trigger: 'focus',html: true, gravity: 's'});	
  });
</script>