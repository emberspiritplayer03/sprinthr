<?php include('includes/header.php');?>
<div id="content" class="sidebar_left">
	<div class="mtcntnr">
    	<h1 class="module_title"><i class="mticon_img icon_employee"></i><?php echo $page_title;?><?php echo $page_subtitle;?>
        <?php if($leave){ ?>
            <a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_leave_form();"><strong>+</strong><b>Request Leave</b></a>
            <a class="add_button" id="import_leave_button_wrapper" href="#" onClick="javascript:importLeave();" ><i class="icon-arrow-left"></i> <b>Import Leave</b></a>
        <?php }else if($overtime){ ?>
       		<a id="request_overtime_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_overtime_form_clerk();"><strong>+</strong><b>Request Overtime</b></a>
            <a id="overtime_back" href="javascript:void(0);" onclick="javascript:back_to_list();" class="gray_button title_back_button"><i></i>Back</a>
			<select id="department" name="department" style="width:220px;" onchange="javascript:load_overtime_list_dt_withselectionfilter();">
            <option value="">-- Select Department --</option>
            	<?php foreach($department as $d): ?>
                	<option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo $d->getTitle(); ?></option>
                <?php endforeach; ?>
            </select>
        <?php } ?>       
        </h1><div class="mtshad"></div>
    </div>
	<div id="message_container" style="width:auto; display:none;" class="ui-state-highlight ui-corner-all message_box"> 
        <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
        <span class="message"></span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:$('#message_container').hide()" style="float:right" title="Close"></a>
    </div>
    <div id="main">
        <div class="module_content"><?php $this->showContent();?></div>
    </div>
</div>
<?php include('includes/footer.php');?>