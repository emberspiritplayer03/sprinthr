<h3 class="section_title">Bi-Monthly</h3>
<table width="100%" border="1">
<thead>
    <th width="11%">Name</th>
    <th width="31%">Payday Breakdown</th>
    <th width="15%">Base salary credit</th>
    <th style="text-align:center;" width="13%">Is Taxable</th>
    <th style="text-align:center;" width="7%">Status</th>
    <th></th>
</thead>
<?php foreach($deductions as $d): ?>
<?php $b = explode(':',$d['breakdown']); ?>
    <tr>
        <td><?php echo $d['name']; ?></td>
        <td><?php echo $b[0] . '% (1st Cut-Off) : ' . $b[1] . '% (2nd Cut-Off)'; ?></td>
        <td style="text-align:center;"><?php echo $salary_credit_options[$d['salary_credit']]; ?></td>
        <td style="text-align:center;"><?php echo $d['is_taxable']; ?></td>    
        <td style="text-align:center;"><?php echo ($d['is_active'] == G_Settings_Deduction_Breakdown::YES ? 'Active' : 'Inactive'); ?></td>
        <td valign="middle">
            <div class="i_container">
                <ul class="dt_icons" style="margin:0 42px;"> 
                    <li>
                        <a id="tipsy" class="ui-icon ui-icon-pencil" href="javascript:void(0)" onclick="javascript:editDeductionBreakdown('<?php echo $d['id'];?>');" title="Edit"></a>
                    </li>
                    <?php if($d['is_active'] == G_Settings_Deduction_Breakdown::YES) { ?> 
                        <li>   
                            <a id="tipsy" class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:_deactivateDeductionBreakdown('<?php echo $d['id'];?>')" title="Deactivate"></a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a id="tipsy" class="ui-icon ui-icon-check" href="javascript:void(0)" onclick="javascript:_activateDeductionBreakdown('<?php echo $d['id'];?>')" title="Activate"></a>
                        </li>
                    <?php } ?>                               
                </ul>
            </div>      
        </td>
    </tr>
<?php endforeach; ?>
</table>

<br>

<h3 class="section_title">Weekly</h3>
<table width="100%" border="1">
<thead>
    <th width="11%">Name</th>
    <th width="31%">Payday Breakdown</th>
    <th width="15%">Base salary credit</th>
    <th style="text-align:center;" width="13%">Is Taxable</th>
    <th style="text-align:center;" width="7%">Status</th>
    <th></th>
</thead>
<?php foreach($weekly_deductions as $wd): ?>
<?php $b = explode(':',$wd['breakdown']); ?>
    <tr>
        <td><?php echo $wd['name']; ?></td>
        <td><?php echo $b[0] . '% (1st Cut-Off) : ' . $b[1] . '% (2nd Cut-Off) : ' . $b[2] . '% (3rd Cut-Off) : ' . $b[3] . '% (4th Cut-Off)'; ?></td>
        <td style="text-align:center;"><?php echo $salary_credit_options[$wd['salary_credit']]; ?></td>
        <td style="text-align:center;"><?php echo $wd['is_taxable']; ?></td>    
        <td style="text-align:center;"><?php echo ($wd['is_active'] == G_Settings_Weekly_Deduction_Breakdown::YES ? 'Active' : 'Inactive'); ?></td>
        <td valign="middle">
            <div class="i_container">
                <ul class="dt_icons" style="margin:0 42px;"> 
                    <li>
                        <a id="tipsy" class="ui-icon ui-icon-pencil" href="javascript:void(0)" onclick="javascript:editWeeklyDeductionBreakdown('<?php echo $wd['id'];?>');" title="Edit"></a>
                    </li>
                    <?php if($wd['is_active'] == G_Settings_Weekly_Deduction_Breakdown::YES) { ?> 
                        <li>   
                            <a id="tipsy" class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:_deactivateWeeklyDeductionBreakdown('<?php echo $wd['id'];?>')" title="Deactivate"></a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a id="tipsy" class="ui-icon ui-icon-check" href="javascript:void(0)" onclick="javascript:_activateWeeklyDeductionBreakdown('<?php echo $wd['id'];?>')" title="Activate"></a>
                        </li>
                    <?php } ?>                               
                </ul>
            </div>      
        </td>
    </tr>
<?php endforeach; ?>
</table>


<br/>


<h3 class="section_title">Monthly</h3>
<table width="100%" border="1">
<thead>
    <th width="11%">Name</th>
    <th width="31%">Payday Breakdown</th>
    <th width="15%">Base salary credit</th>
    <th style="text-align:center;" width="13%">Is Taxable</th>
    <th style="text-align:center;" width="7%">Status</th>
    <th></th>
</thead>
<?php foreach($monthly_deductions as $d): ?>
<?php $b = explode(':',$d['breakdown']); ?>
    <tr>
        <td><?php echo $d['name']; ?></td>
        <td><?php echo $b[0] . '% ( Cut-Off )'; ?></td>
        <td style="text-align:center;"><?php echo $salary_credit_options[$d['salary_credit']]; ?></td>
        <td style="text-align:center;"><?php echo $d['is_taxable']; ?></td>    
        <td style="text-align:center;"><?php echo ($d['is_active'] == G_Settings_Monthly_Deduction_Breakdown::YES ? 'Active' : 'Inactive'); ?></td>
        <td valign="middle">
            <div class="i_container">
                <ul class="dt_icons" style="margin:0 42px;"> 
                    <li>

                        <a id="tipsy" class="ui-icon ui-icon-pencil" href="javascript:void(0)" onclick="javascript:editMonthlyDeductionBreakdown('<?php echo $d['id'];?>');" title="Edit"></a>
                    </li>
                    <?php if($d['is_active'] == G_Settings_Monthly_Deduction_Breakdown::YES) { ?> 
                        <li>   
                            <a id="tipsy" class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:_deactivateMonthlyDeductionBreakdown('<?php echo $d['id'];?>')" title="Deactivate"></a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a id="tipsy" class="ui-icon ui-icon-check" href="javascript:void(0)" onclick="javascript:_activateMonthlyDeductionBreakdown('<?php echo $d['id'];?>')" title="Activate"></a>
                        </li>
                    <?php } ?>                               
                </ul>
            </div>      
        </td>
    </tr>
<?php endforeach; ?>
</table>


<!--fixed contri pagibig enabled -->
<div style="margin-top: 50px">
    <h3 class="section_title">Enabled Contributions Settings</h3>
     <div id="fixed_wrapper"></div>
</div>




<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
load_fixed_contribution();
</script>
