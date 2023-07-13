<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
    <tr>
        <td colspan="5">&nbsp;</td>
        <td colspan="<?php echo $colspan;?>" align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee</strong></td>
        <td colspan="<?php echo $colspan;?>" align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employer</strong></td>
        <td colspan="<?php echo $colspan;?>" align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Total</strong></td>
    </tr>
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <?php if($show_sss) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>SSS</span></strong></td>
        <?php } ?>
        <?php if($show_pagibig) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>Pagibig</span></strong></td>
        <?php } ?>
        <?php if($show_philhealth) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>Philhealth</span></strong></td>
        <?php } ?>   
        <?php if($show_sss) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>SSS</span></strong></td>
        <?php } ?>
        <?php if($show_pagibig) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>Pagibig</span></strong></td>
        <?php } ?> 
        <?php if($show_philhealth) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>Philhealth</span></strong></td>
        <?php } ?> 
        <?php if($show_sss) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>SSS</span></strong></td>
        <?php } ?>
        <?php if($show_pagibig) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>Pagibig</span></strong></td>
        <?php } ?> 
        <?php if($show_philhealth) { ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong>Philhealth</span></strong></td>
        <?php } ?>
    </tr>
	<?php 
		foreach($ee_er_contribution as $key => $a){
            $total_sss          = $a['ee_sss'] + $a['er_sss'];
            $total_pagibig      = $a['ee_pagibig'] + $a['er_pagibig'];
            $total_philhealth   = $a['ee_philhealth'] + $a['er_philhealth'];
	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $key; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['department_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['section_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['position']; ?></td>
            <?php if($show_sss) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($a['ee_sss'],2); ?></td>
            <?php } ?>
            <?php if($show_pagibig) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($a['ee_pagibig'],2); ?></td>
            <?php } ?>
            <?php if($show_philhealth) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($a['ee_philhealth'],2); ?></td>
            <?php } ?>   
            <?php if($show_sss) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($a['er_sss'],2); ?></td>
            <?php } ?>
            <?php if($show_pagibig) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($a['er_pagibig'],2); ?></td>
            <?php } ?> 
            <?php if($show_philhealth) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($a['er_philhealth'],2); ?></td>
            <?php } ?>
            <?php if($show_sss) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($total_sss,2); ?></td>
            <?php } ?>
            <?php if($show_pagibig) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($total_pagibig,2); ?></td>
            <?php } ?> 
            <?php if($show_philhealth) { ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($total_philhealth,2); ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>