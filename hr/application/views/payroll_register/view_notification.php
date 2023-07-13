<?php if( isset($cutoff_01) && isset($cutoff_02) ) { ?>
		<script>
		$(function(){
		    loadViewNotificationItemListByMonth('<?php echo Utilities::encrypt($n->getId()); ?>', '<?php echo $cutoff_01; ?>', '<?php echo $cutoff_02; ?>');
		    $('.btn-refresh').click(function(){
		    	loadViewNotificationItemListByMonth('<?php echo Utilities::encrypt($n->getId()); ?>', '<?php echo $cutoff_01; ?>', '<?php echo $cutoff_02; ?>');
		    });
		})
		</script>
<?php } else { ?>
		<script>
		$(function(){
		    loadPayrollViewNotificationItemList('<?php echo Utilities::encrypt($n->getId()); ?>', '<?php echo $from; ?>', '<?php echo $to; ?>');
		    $('.btn-refresh').click(function(){
		    	loadPayrollViewNotificationItemList('<?php echo Utilities::encrypt($n->getId()); ?>', '<?php echo $from; ?>', '<?php echo $to; ?>');
		    });
		})
		</script>
<?php } ?>

<div class="pull-left">
	<h2><?php echo $n->getEventType();?> <a class="btn btn-refresh" style="padding:3px 7px 3px 7px" title="Refresh"><i class="icon-refresh"> </i> </a></h2>
</div>

<div class="pull-right"><a href="<?php echo url('notifications/generate_excel?eid='.Utilities::encrypt($n->getId()));?>" class="gray_button"><i class="icon-excel icon-custom"></i> Download in Excel</a></div>

<div class="clear"></div>

<div id="view_notification_item_list_wrapper2"></div>

