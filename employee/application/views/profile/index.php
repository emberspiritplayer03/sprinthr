<style>
.profile-hdr{padding:8px;background-color:#198cc9;color:#ffffff;margin-top:10px;}
.employee_profile_photo{width:140px;margin-right:16px;margin-top:14px;}
</style>
<div id="form_main">  
	<?php 
		$employee_name  = ucwords($e->getLastName() . " " . $e->getFirstName() . ", " . $e->getMiddleName());
		$employee_id    = $e->getEmployeeCode();
		$birthdate      = $e->getBirthdate();
		$marital_status = $e->getMaritalStatus();
		$number_dependents = $e->getNumberDependent();		
		
		$contactAr = array();
		if( !empty($c) ){
			$address     = $c->getAddress();
			$contactAr[] = $c->getHomeTelephone();
			$contactAr[] = $c->getMobile();
			$contactAr[] = $c->getWorkTelephone();
			$emailAr[]   = $c->getWorkEmail();
			$emailAr[]   = $c->getOtherEmail();
		}

		$contacts = implode(",", $contactAr);
		$emails   = implode(",", $emailAr);

		$sss 		= $e->getSSSNumber();
		$philhealth = $e->getPhilHealthNumber();
		$pagibig    = $e->getPagibigNumber();
		$tin        = $e->getTinNumber();
		$date_hired = $e->getHiredDate();
	?>
	<div id="form_default">
		<h2>Personal Details</h2>  
		<div class="pull-left">
			<table>
				 <tr>
				       <td class="field_label">Name </td>
				       <td>
				       		: <input class="input-large" type="text" readonly="readonly" value="<?php echo $employee_name; ?>" style="width:292px;" />
				       </td>
				     </tr>   	
				<tr>
					<td class="field_label">Employe ID </td>
					<td>
						: <input class="input-large" type="text" readonly="readonly" value="<?php echo $employee_id; ?>" style="width:292px;" />
					</td>
				</tr> 
				<tr>
					<td class="field_label">Bithdate </td>
					<td>
						: <input class="input-large" type="text" readonly="readonly" value="<?php echo $birthdate; ?>" style="width:292px;" />
					</td>
				</tr>
				<tr>
					<td class="field_label">Marital Status </td>
					<td>
						: <input class="input-large" type="text" readonly="readonly" value="<?php echo $marital_status; ?>" style="width:292px;" />
					</td>
				</tr>
				<tr>
					<td class="field_label">Number of dependents </td>
					<td>
						: <input class="input-large" type="text" readonly="readonly" value="<?php echo $number_dependents; ?>" style="width:292px;" />
					</td>
				</tr>
			</table>
		</div>
		<div class="pull-right employee_profile_photo"><img src="<?php echo $e_photo; ?>" /></div>
		<div class="clear"></div>
	</div>
	<div id="form_default">
		<h2>Contact Details</h2>
		<table>
			<tr>
				<td class="field_label">Address </td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $address; ?>" style="width:292px;" />
				</td>
			</tr>
			<tr>
				<td class="field_label">Email</td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $emails; ?>" style="width:292px;" />
				</td>
			</tr>
			<tr>
				<td class="field_label">Contact Number </td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $contacts; ?>" style="width:292px;" />
				</td>
			</tr>	
		</table>
	</div>
	<div id="form_default">
		<h2>Employment Details</h2>
		<table>	
			<tr>
				<td class="field_label">SSS Number </td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $sss; ?>" style="width:292px;" />				
				</td>
			</tr>
			<tr>
				<td class="field_label">TIN Number </td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $tin; ?>" style="width:292px;" />
				</td>
			</tr>
			<tr>
				<td class="field_label">Pagibig Number </td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $pagibig; ?>" style="width:292px;" />
				</td>
			</tr>
			<tr>
				<td class="field_label">Philhealth Number </td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $philhealth; ?>" style="width:292px;" />
				</td>
			</tr>
			<tr>
				<td class="field_label">Date hired </td>
				<td>
					: <input class="input-large" type="text" readonly="readonly" value="<?php echo $date_hired; ?>" style="width:292px;" />
				</td>
			</tr>
		</table>
	</div>
	</div>
</div>