<?php include_once('header_excel.php'); ?>
<div style="width:80%">   
	<br/>
	<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:536pt; line-height:12pt;">
		<tr>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Description</span></strong></td>
	    </tr>
		<?php 
			foreach($data as $key => $value){
		?>
	    	<tr>
	            <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['employee_name']; ?></td>
	            <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['department_name']; ?></td>
	            <td align="left" valign="top" style="border-bottom:none;">
		            <?php
		            	if($value['requirement_id'] != ""){				
							$requirements = G_Employee_Requirements_Finder::findById($value['requirement_id']);
							if($requirements) {
								$requirements_arr = unserialize($requirements->getRequirements());
						        foreach($requirements_arr as $req_key => $req_val) {
						            if($req_val != "on") {
						            	$req_key = str_replace("_", " ", $req_key);
						            	$req_key = ucfirst($req_key);
						                $new_value_arr[] = $req_key;
						            }
						        }

						        $new_value = implode(", ",$new_value_arr);
						        echo $new_value;
							}else{
								echo  '<span style="color:red">No requirements set.</span>';
							}
					        
						}else{
							echo '<span style="color:red">No requirements set.</span>';
						}	
		            ?>
	            </td>
          
	        </tr>
	    <?php } ?>

	</table>
</div>
<?php include_once('footer_excel.php'); ?>