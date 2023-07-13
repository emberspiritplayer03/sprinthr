<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table td { border:1px solid #666666;}
</style>
<h1><?php echo $header; ?></h1>

<?php if($type == "all"){ ?>
    <h3>Total Pending : <?php echo $summary['total_pending']; ?> | Total Submitted : <?php echo $summary['total_submitted']; ?></h3>
<?php }elseif($type == "pending"){ ?>
    <h3>Total Pending : <?php echo $summary['total_pending']; ?></h3>
<?php }elseif($type == "submitted"){ ?>
    <h3>Total Submitted : <?php echo $summary['total_submitted']; ?></h3>
<?php } ?>

<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#e4f5ff"></td>
    <td bgcolor="#e4f5ff">Employee Name</td>
    <td bgcolor="#e4f5ff">Cutoff Period</td>
    <td bgcolor="#e4f5ff">Tax Contribution</td>
  </tr>


<?php 
    $counter = 1;
    foreach($data as $d){ 
?>			
      <tr>
        <td style="mso-number-format:'\@';"><?php echo $counter; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_name']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['cutoff_period']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['tax_contribution']; ?></td>
      </tr>       
<?php $counter++; } ?>    
</table>
<?php
header("Content-type: application/x-msexcel"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
//header("Expires: 0");
?>