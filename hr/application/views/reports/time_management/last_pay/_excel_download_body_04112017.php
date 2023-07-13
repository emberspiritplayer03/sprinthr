<style type="text/css">
<!--table
  {mso-displayed-decimal-separator:"\.";
  mso-displayed-thousand-separator:"\,";}
@page
  {margin:.75in .7in .75in .7in;
  mso-header-margin:.3in;
  mso-footer-margin:.3in;}
-->

tr
  {mso-height-source:auto;}
col
  {mso-width-source:auto;}
br
  {mso-data-placement:same-cell;}
.style0
  {mso-number-format:General;
  text-align:general;
  vertical-align:bottom;
  white-space:nowrap;
  mso-rotate:0;
  mso-background-source:auto;
  mso-pattern:auto;
  color:black;
  font-size:11.0pt;
  font-weight:400;
  font-style:normal;
  text-decoration:none;
  font-family:Calibri, sans-serif;
  mso-font-charset:0;
  border:none;
  mso-protection:locked visible;
  mso-style-name:Normal;
  mso-style-id:0;}
td
  {mso-style-parent:style0;
  padding-top:1px;
  padding-right:1px;
  padding-left:1px;
  mso-ignore:padding;
  color:black;
  font-size:11.0pt;
  font-weight:400;
  font-style:normal;
  text-decoration:none;
  font-family:Calibri, sans-serif;
  mso-font-charset:0;
  mso-number-format:General;
  text-align:general;
  vertical-align:bottom;
  border:none;
  mso-background-source:auto;
  mso-pattern:auto;
  mso-protection:locked visible;
  white-space:nowrap;
  mso-rotate:0;}
.xl65
  {mso-style-parent:style0;
  font-weight:700;}
.xl66
  {mso-style-parent:style0;
  font-size:10.0pt;
  font-weight:700;
  font-family:Arial, sans-serif;
  mso-font-charset:0;
  text-align:left;}
.xl67
  {mso-style-parent:style0;
  color:windowtext;
  font-size:10.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;}
.xl68
  {mso-style-parent:style0;
  text-align:left;}
.xl69
  {mso-style-parent:style0;
  color:windowtext;
  font-size:10.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;
  text-align:left;}
.xl70
  {mso-style-parent:style0;
  text-align:right;}
.xl71
  {mso-style-parent:style0;
  border-top:none;
  border-right:none;
  border-bottom:.5pt solid windowtext;
  border-left:none;}
.xl72
  {mso-style-parent:style0;
  color:windowtext;
  font-size:10.0pt;
  font-weight:700;
  font-family:Arial, sans-serif;
  mso-font-charset:0;}
.xl73
  {mso-style-parent:style0;
  font-size:10.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;}
.xl74
  {mso-style-parent:style0;
  font-weight:700;
  border-top:none;
  border-right:none;
  border-bottom:.5pt solid windowtext;
  border-left:none;}
.xl75
  {mso-style-parent:style0;
  color:windowtext;
  font-size:10.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;
  mso-number-format:"\[ENG\]\[$-409\]mmmm\\ d\\\,\\ yyyy\;\@";
  text-align:left;}




</style>

<table border=0 cellpadding=0 cellspacing=0 width=940 style='border-collapse:
 collapse;table-layout:fixed;width:706pt'>
 <col width=64 span=2 style='width:48pt'>
 <col width=180 style='mso-width-source:userset;mso-width-alt:6582;width:135pt'>
 <col width=250 style='mso-width-source:userset;mso-width-alt:9142;width:188pt'>
 <col width=94 style='mso-width-source:userset;mso-width-alt:3437;width:71pt'>
 <col width=96 style='mso-width-source:userset;mso-width-alt:3510;width:72pt'>
 <col width=64 span=3 style='width:48pt'>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl66 colspan=3 width=308 style='height:15.0pt;mso-ignore:
  colspan;width:231pt'>LAGUNA DAI-ICHI, INC.</td>
  <td width=250 style='width:188pt'></td>
  <td width=94 style='width:71pt'></td>
  <td width=96 style='width:72pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 colspan=4 style='height:15.0pt;mso-ignore:colspan'>RESIGNED
  EMPLOYEES ACCOUNTABILITY SUMMARY</td>
  <td colspan=5 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 colspan=9 style='height:15.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'>Name</td>
  <td></td>
  <td class=xl67>:</td>
  <td class=xl65><?= $d['lastname']; ?>, <?= $d['firstname']; ?> <?= $d['middlename'][0]; ?>.</td>
  <td></td>
  <td colspan=3 style='mso-ignore:colspan'>last salary - <?= $data['date_last_salary']; ?></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'>Position</td>
  <td></td>
  <td class=xl67>:</td>
  <td><?= $d['position']; ?></td>
  <td></td>
  <td colspan=4 style='mso-ignore:colspan'>last attendance - <?= $data['last_attendance']; ?></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 colspan=2 style='height:15.0pt;mso-ignore:colspan'>Badge
  No.</td>
  <td class=xl67>:</td>
  <?php
    if($e->getEmployeeCode()) {
      $employee_code = $e->getEmployeeCode();
    }
  ?>
  <td class=xl68><?php echo $employee_code; ?></td>
  <td colspan=5 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 colspan=2 style='height:15.0pt;mso-ignore:colspan'>Date
  Hired</td>
  <td class=xl67>:</td>
  <td class=xl75><?= date('F d, Y',strtotime($d['hired_date'])); ?></td>
  <td colspan=5 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 colspan=2 style='height:15.0pt;mso-ignore:colspan'>Date
  Resigned</td>
  <td class=xl67>:</td>
  <td class=xl75><?= ($d['resignation_date'] !== '0000-00-00' ? date('F d, Y',strtotime($d['resignation_date'])) : '0000-00-00'); ?></td>
  <td colspan=5 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 colspan=2 style='height:15.0pt;mso-ignore:colspan'>Department</td>
  <td class=xl67>:</td>
  <td><?= $d['department']; ?></td>
  <td colspan=5 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 colspan=2 style='height:15.0pt;mso-ignore:colspan'>Tax Status</td>
  <td class=xl67>:</td>
  <td>
    <?php 
        if($data['withheld_tax'] <= 0) {
            echo "Z";
        }elseif($d['number_dependent'] == 0) {
            if($d['marital_status'] == "Single" || $d['marital_status'] == "Separated" || $d['marital_status'] == "Widowed") {
                echo "S";
            }elseif($d['marital_status'] = "Married") {
                echo "ME";
            }
        }elseif($d['number_dependent'] >= 1) {
            if($d['number_dependent'] > 4) {
                $d['number_dependent'] = 4;
            }

            if($d['marital_status'] == "Single" || $d['marital_status'] == "Separated" || $d['marital_status'] == "Widowed") {
                echo "S".$d['number_dependent'];
            }elseif($d['marital_status'] = "Married") {
                echo "ME".$d['number_dependent'];
            }
        }
    ?>
  </td>
  <td colspan=5 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'>Rate</td>
  <td></td>
  <td class=xl67>:</td>
  <td colspan=2 style='mso-ignore:colspan'>
    <?= $data['employee_rate']; ?>
  </td>
  <td colspan=4 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 colspan=9 style='height:15.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 colspan=3 style='height:15.0pt;mso-ignore:colspan'>1.
  Gross Earnings Computation</td>
  <td colspan=6 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Basic Pay</td>
  <td class=xl69></td>
  <td></td>
  <td align=right><?= $data['basic_pay']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Night Differential</td>
  <td class=xl67><?= $data['night_differential_hrs']; ?></td>
  <td></td>
  <td align=right><?php echo  number_format($data['night_differential_amount'],2); ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Overtime Pay</td>
  <td class=xl67><?= $data['overtime_pay_label']; ?></td>
  <td></td>
  <td align=right><?= number_format($data['overtime_pay_amount'],2); ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Others</td>
  <td class=xl69></td>
  <td></td>
  <td class=xl70><?php echo $data['other_earnings_amount']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Absent/ TRD/UT</td>
  <td class=xl67><?= $data['absent_late_undertime_label']; ?></td>
  <td></td>
  <td class=xl71 align=right><?= $data['absent_late_undertime_amount']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Gross Pay</td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td align=right><?php echo ($data['total_earnings'] - $data['absent_late_undertime_amount']); ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 colspan=9 style='height:15.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Less: Gov't. &amp; Taxes</td>
  <td colspan=6 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Withholding Taxes</td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td align=right><?= $data['withheld_tax']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67>SSS</td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl70><?= $data['sss']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67>Philhealth</td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl70><?= $data['philhealth']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67>Pag-ibig</td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl71 align=right><?= $data['pagibig']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Total Deductions</td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td align=right>
    <?php //echo $data['total_deductions']; ?>
    <?php echo $tdeduct = ($data['withheld_tax'] + $data['sss'] + $data['philhealth'] + $data['pagibig']); ?>
  </td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl72>Net Pay</td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl65 align=right><?php echo ($data['total_earnings'] - $tdeduct); ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=40 style='height:30.0pt;mso-xlrowspan:2'>
  <td height=40 colspan=9 style='height:30.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 colspan=2 style='height:15.0pt;mso-ignore:colspan'>2. 13th Month Pay</td>
  <td colspan=7 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 align=right style='height:15.0pt'></td>
  <td colspan=2 style='mso-ignore:colspan'><?= date('Y'); ?> Basic Pay</td>
  <td class=xl67></td>
  <td align=right></td>
  <td class=xl71 style='mso-ignore:colspan'><?= $data['basic_pay']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 align=right style='height:15.0pt'></td>
  <td colspan=2 style='mso-ignore:colspan'>Total</td>
  <td class=xl67></td>
  <td align=right></td>
  <td style='mso-ignore:colspan'><?php echo $data['month_13th_amount']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>

 </tr>
 <tr height="20" style="height:15.0pt">
  <td height="20" style="height:15.0pt"></td>
  <td class="xl67"></td>
  <td align="right" style="mso-ignore:colspan" colspan="3"></td>
  <td align="right" class="xl65"></td>
  <td colspan="3" style="mso-ignore:colspan"></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 colspan=9 style='height:15.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 colspan=2 style='height:15.0pt;mso-ignore:colspan'>3. Other Deduction</td>
  <td colspan=7 style='mso-ignore:colspan'></td>
 </tr>
 <?php foreach($data['other_deductions_array'] as $label => $value) { ?>
     <tr height=20 style='height:15.0pt'>
      <td height=20 style='height:15.0pt'></td>
      <td class=xl67><?= $label; ?></td>
      <td colspan=2 style='mso-ignore:colspan'></td>
      <td  align=right></td>
      <td class=xl71  style='mso-ignore:colspan'><?= $value; ?></td>
      <td colspan=3 style='mso-ignore:colspan'></td
     </tr>
 <?php } ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67>Others</td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td  align=right></td>
  <td class=xl71  style='mso-ignore:colspan'><?= $data['other_deductions_amount']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl67>Total</td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl65 align=right><?= $data['total_other_deductions_amount']; ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 colspan=9 style='height:15.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl72 style='mso-ignore:colspan'>Grand Total</td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl74 align=right><?php echo ($data['net_pay'] + $data['month_13th_amount']); ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=40 style='height:30.0pt;mso-xlrowspan:2'>
  <td height=40 colspan=9 style='height:30.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 colspan=2 style='height:15.0pt;mso-ignore:colspan'>Prepared by:</td>
  <td></td>
  <td>Checked by:</td>
  <td></td>
  <td>Approved by:</td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=40 style='height:30.0pt;mso-xlrowspan:2'>
  <td height=40 colspan=9 style='height:30.0pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'><?= $prepared_by_d['firstname']; ?> <?= $prepared_by_d['lastname']; ?></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl65><?= $checked_by_d['firstname']; ?> <?= $checked_by_d['lastname']; ?></td>
  <td></td>
  <td class=xl65 colspan=2 style='mso-ignore:colspan'><?= $approved_by_d['firstname']; ?> <?= $approved_by_d['lastname']; ?></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'><?= $prepared_by_d['position']; ?></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td><?= $checked_by_d['position']; ?></td>
  <td></td>
  <td colspan=2 style='mso-ignore:colspan'><?= $approved_by_d['position']; ?></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=180 style='width:135pt'></td>
  <td width=250 style='width:188pt'></td>
  <td width=94 style='width:71pt'></td>
  <td width=96 style='width:72pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
 </tr>
 <![endif]>
</table>