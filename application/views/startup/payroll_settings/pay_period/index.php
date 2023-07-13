<style>
table.formtable td{
	padding:5px 8px !important;
}
</style>
<div class="div_table_border">
<table class="formtable" width="100%" border="0">
<thead>
	<th width="30%">Pay Period Code</th>
    <th width="30%">Pay Period Name</th>
    <th width="20%">Cut Off</th>
    <th width="30%">Is Default</th>
</thead>
<?php foreach($payroll_period as $p){ ?>
  <tr>
    <td><strong><?php echo $p->getPayPeriodCode(); ?></strong></td>
    <td>
    	<?php echo $p->getPayPeriodName(); ?>
    	&nbsp;<a class="link_option" href="javascript:void(0)" onclick="javascript:load_edit_pay_period(<?php echo "'".Utilities::encrypt($p->getId())."'";?>);" title="Edit"><i class="icon-edit"></i> Edit</a>       
    </td>
    <td>
    	<?php echo '<span class="label label-info">' . $p->getCutOff() . '</span>' ?>
    </td>
    <td>
    	<?php 
			if($p->getIsDefault() == G_Settings_Pay_Period::IS_DEFAULT){
				echo "<b>Default</b>";
			}else{
		?>
        	<a class="btn btn-mini link_option" href="javascript:setDefaultPayPeriod('<?php echo Utilities::encrypt($p->getId()); ?>');"><i class="icon-star-empty"></i>Set as Default</a>   
        <?php
			}
		?>
        
    </td>   
  </tr>
<?php } ?>
</table>
</div>
<div id="payperiod_form"></div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>

<?php 
	chdir(dirname(__FILE__));
	include_once('../includes/modal_forms.php'); 
?>