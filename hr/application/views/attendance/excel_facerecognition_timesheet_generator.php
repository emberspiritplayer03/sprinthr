<?php ob_start();
//echo "<pre>";
//print_r($_FILES);
//echo "</pre>";

$myFile = $_FILES['txt_file']['tmp_name'];
$fh = fopen($myFile, 'r');
?>
<table >
	<tr>
        <td>Employee Code </td>
        <td>Employee Name</td>
        <td>Type</td>
        <td>Date & Time</td>
    </tr> 
<?php  $x = 1; while(!feof($fh)) { ?>
  <?php if($x == 2){   $this_line = fgets($fh);   
  					     $new_attendance =  str_replace('time="', "", str_replace('" id="', ",", str_replace('" name="', ",", str_replace('" workcode="" status="', ",", str_replace('" card_src="from_check"', "", $this_line))))) . "<br />";  $attendance = explode(",", $new_attendance); }else{  $this_line = fgets($fh); $attendance = "";  $x=2;}   
			if($attendance!= ""){      
				if(isset($attendance[1])){ ?>                 
        <tr>
        	<td><?php if($attendance[1]){ echo $attendance[1];} ?></td>
            <td>
				<?php 
					if($attendance[1]){ 
						$e = G_Employee_Finder::findByEmployeeCode($attendance[1]);
						if($e){
							$ename = $e->getFirstname() . " " . $e->getLastname();						
						}else{$ename = "";}
						echo $ename;
					} 
				?>
            </td>
        	<td><?php if($attendance[3]){ if($attendance[3]==1){echo "In";}else{echo "Out";} } ?></td>
            <td>
				<?php 
					if($attendance[0]){ 
						$inout = date("m/d/Y G:i",strtotime($attendance[0]));
						echo $inout;
					} 
				?>
            </td>
        </tr> 
  <?php  		} 
  			} 
  		} //while ?>
  </table>
<?php fclose($fh); ?>
<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=attendance_generator.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
