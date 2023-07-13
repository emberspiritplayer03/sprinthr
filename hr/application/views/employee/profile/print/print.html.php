<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>

<?php 
$a 		= "on";
$module = $data['module'];
if($data['all_modules'] == $a) {
	include('personal_information_section.php');
	echo '<br/><br/>';
	include('employment_information_section.php');
	echo '<br/><br/>';
	include('qualification_section.php');
} else {
	if($data['personal_information_section'] == $a) {
		include('personal_information_section.php');
		echo '<br/><br/>';
	} else {
		if($module['personal_details'] == $a) {
			include('personal_details.php');
			echo '<br/><br/>';
		} 
		
		if($module['contact_details'] == $a) {
			include('contact_details.php');
			echo '<br/><br/>';
		} 
		
		if($module['emergency_contacts'] == $a) {
			include('emergency_contacts.php');
			echo '<br/><br/>';
		} 
		
		if($module['dependents'] == $a) {
			include('dependents.php');
			echo '<br/><br/>';
		} 
		
		if($module['bank'] == $a) {
			include('banks.php');
			echo '<br/><br/>';
		}
	}
	
	
	if($data['employment_information_section'] == $a) {
		include('employment_information_section.php');
		echo '<br/><br/>';
	} else {
		if($module['employment_status'] == $a) {
			include('employment_status.php');
			echo '<br/><br/>';
		} else if($module['compensation'] == $a) {
			include('compensation.php');
			echo '<br/><br/>';
		} else if($module['contract'] == $a) {
			include('contract.php');
			echo '<br/><br/>';
		} else if($module['contribution'] == $a) {
			include('contribution.php');
			echo '<br/><br/>';
		} else if($module['performance'] == $a) {
			include('performance.php');
			echo '<br/><br/>';
		} else if($module['training'] == $a) {
			include('training.php');
			echo '<br/><br/>';
		} else if($module['memo'] == $a) {
			include('memo.php');
		} else if($module['requirement'] == $a) {
			include('requirements.php');
			echo '<br/><br/>';
		} else if($module['supervisor'] == $a) {
			include('supervisor.php');
			echo '<br/><br/>';
		} else if($module['leave'] == $a) {
			include('leave.php');
			echo '<br/><br/>';
		} else if($module['deduction'] == $a) {
			include('loan_list.php');
			echo '<br/><br/>';
		}
	}
	
	if($data['qualification_section'] == $a) {
		include('qualification_section.php');
		echo '<br/><br/>';
	} else {
		if($module['work_experience'] == $a) {
			include('work_experience.php');
			echo '<br/><br/>';
		} else if($module['education'] == $a) {
			include('education.php');
			echo '<br/><br/>';
		} else if($module['skills'] == $a) {
			include('skills.php');
			echo '<br/><br/>';
		} else if($module['language'] == $a) {
			include('language.php');
			echo '<br/><br/>';
		} else if($module['license'] == $a) {
			include('license.php');
			echo '<br/><br/>';
		}
	}
}

/*header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");*/

header("Content-type: application/x-msexcel"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
//header("Expires: 0");
?>