<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">    
    <tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Dept/Section</strong></td>        
        <td align="center" colspan="2" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Regular/Proby</strong></td>        
        <td align="center" colspan="2" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Full Time</strong></td>    
        <td align="center" colspan="2" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Contractual</strong></td>    
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>&nbsp;</strong></td>           
    </tr>    
    <tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>&nbsp;</strong></td>        
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Male</strong></td>        
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Female</strong></td>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Male</strong></td>        
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Female</strong></td>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Male</strong></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Female</strong></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Total</strong></td>           
    </tr>    
    <?php $total = array(); ?>
    <?php foreach($data as $dept_key => $r) { ?>
    <?php
        $regular_proby_male   = 0;
        $regular_proby_female = 0;
        $fulltime_male        = 0;
        $fulltime_female      = 0;
        $contractual_male     = 0;
        $contractual_female   = 0;

        foreach($r as $rkey => $d) {
            $sub_total      = 0;
            if($rkey == 'Regular' || $rkey == 'Probationary') {
                $regular_proby_male   += $d['Male'][0]['total_employees'] + $d['Male'][0]['total_others'];
                $regular_proby_female += $d['Female'][0]['total_employees'] + $d['Female'][0]['total_others'];
            }
            if($rkey == 'Full Time') {
                $fulltime_male        += $d['Male'][0]['total_employees'] + $d['Male'][0]['total_others'];
                $fulltime_female      += $d['Female'][0]['total_employees'] + $d['Female'][0]['total_others'];
            }
            if($rkey == 'Contractual') {
                $contractual_male        += $d['Male'][0]['total_employees'] + $d['Male'][0]['total_others'];
                $contractual_female      += $d['Female'][0]['total_employees'] + $d['Female'][0]['total_others'];
            }

            if($rkey == 'previous') {
                if($rkey == 'Regular' || $rkey == 'Probationary') {
                    $regular_proby_male   += $d['Male'][0]['total_employees'] + $d['Male'][0]['total_others'];
                    $regular_proby_female += $d['Female'][0]['total_employees'] + $d['Female'][0]['total_others'];
                }                
            }

            $sub_total += $regular_proby_male + $regular_proby_female + $fulltime_male + $fulltime_female + $contractual_male + $contractual_female;
        }
    ?>
    <?php if($sub_total > 0) { ?>
    <?php
        $dept_section = explode("-", $dept_key);
        $department = G_Company_Structure_Finder::findById($dept_section[0]);
        $section    = G_Company_Structure_Finder::findById($dept_section[1]);        
    ?>
    <tr>
        <?php if($department && $section) {?>
                <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $department->getTitle() . "-" . $section->getTitle(); ?></td>
        <?php } else { ?>
                <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $dept_key; ?></td>
        <?php } ?>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $regular_proby_male; ?></td>        
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $regular_proby_female; ?></td>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $fulltime_male; ?></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $fulltime_female; ?></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $contractual_male; ?></td>  
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $contractual_female; ?></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $sub_total; ?></td>           
    </tr>    
    <?php } ?>  
    <?php
        $total['all'] += $sub_total;
    ?>  
    <?php } ?>
    <tr>
        <td align="right" colspan="7" valign="middle" style="width:90pt; vertical-align:middle;">Total:</td>        
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><?php echo $total['all']; ?></td>           
    </tr>    
     
</table>