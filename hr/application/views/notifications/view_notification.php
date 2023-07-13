<script>
$(function(){
	// file : notifications.js
    loadViewNotificationItemList('<?php echo Utilities::encrypt($n->getId()); ?>', '<?php echo $from; ?>', '<?php echo $to; ?>');

    $('.btn-refresh').click(function(){
    	loadViewNotificationItemList('<?php echo Utilities::encrypt($n->getId()); ?>', '<?php echo $from; ?>', '<?php echo $to; ?>');
    });
})
</script>

<div class="pull-left">
	<h2><?php echo $n->getEventType();?> <a class="btn btn-refresh" style="padding:3px 7px 3px 7px" title="Refresh"><i class="icon-refresh"> </i> </a></h2>
</div>

<div class="pull-right"><a href="<?php echo url('notifications/generate_excel?eid='.Utilities::encrypt($n->getId()));?>" class="gray_button"><i class="icon-excel icon-custom"></i> Download in Excel</a></div>

<div class="clear"></div>

<div id="view_notification_item_list_wrapper"></div>

