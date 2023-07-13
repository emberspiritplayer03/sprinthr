<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
    <tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Resignation Date</span></strong></td>                   
    </tr>
    <?php foreach($data as $d){ ?>
    <?php 
        //$employee_name   = strtr(utf8_decode($d['employee_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $department_name = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $section_name = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $position     = strtr(utf8_decode($d['position']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

        $employee_name = mb_convert_encoding($d['employee_name'] , "HTML-ENTITIES", "UTF-8");

        if(!empty($d['project_site_id']) || $d['project_site_id'] != 0){
            $project = G_Project_Site_Finder::findById($d['project_site_id']);
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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $d['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $employee_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($department_name,  MB_CASE_TITLE, "UTF-8"); ?></td>
             <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>
             
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($section_name,  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $position; ?></td>      
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $d['resignation_date']; ?></td>                  
        </tr>
    <?php } ?>
</table>