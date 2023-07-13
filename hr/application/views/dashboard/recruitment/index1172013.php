<div id="hrplist_container">
<div class="container_12">
	<div class="col_1">
        <div class="inner">
            <div class="section reminder_area">
                <h2 class="sectiontab_title"><span class="icongear"></span>Number of Applicants By Year 
                  <select name="year" id="year" onchange="javascript:loadTabularTotalEmployeesByYear();">
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
                <div id="employee_by_year_wrapper">
                <?php include 'includes/no_applicant_by_year.php'; ?>
                </div>
                
            </div>
          
        </div>
  	</div>
 
</div>