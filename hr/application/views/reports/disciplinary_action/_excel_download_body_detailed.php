<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee ID</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>

         <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>

        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employement Status</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Memo Title</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Offense Date</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Offense Description</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Remarks</span></strong></td>
    </tr>
    <?php  foreach ($disciplinary_action as $key => $value) {


            $employee_name = mb_convert_encoding($value['employee_name'] , "HTML-ENTITIES", "UTF-8");

            if(!empty($value['project_site_id']) || $value['project_site_id'] != 0){
                        $project = G_Project_Site_Finder::findById($value['project_site_id']);
                        if($project){
                            $project_site_name = $project->getprojectname();
                        }
                        else{
                            $project_site_name = "";
                        }
                    }
                    else{
                        $project_site_name = "";
                    }

     ?>
     <tr>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['employee_code']; ?></td>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $employee_name; ?></td>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['department_name']; ?></td>

        <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>

        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['section_name']; ?></td>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['position']; ?></td>

        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['status']; ?></td>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['title']; ?></td>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['date_of_offense']; ?></td>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['offense_description']; ?></td>
        <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['remarks']; ?></td>
     </tr>
    <?php } ?>
</table>