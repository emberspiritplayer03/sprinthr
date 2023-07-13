<?php include('includes/header.php');?>
<div id="main" class="fullwidth">
    <div id="content">
        <div class="mtcntnr">
            <h1 class="module_title"><i class="mticon_img"></i><?php echo $page_title . $module_title;?>
                <a id="request_overtime_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_overtime_form();"><strong>+</strong><b>Request Overtime</b></a>
                <a id="overtime_back" href="javascript:void(0);" onclick="javascript:back_to_list();" class="gray_button title_back_button" style="display:none;"><i></i>Back</a>
                <select id="department" name="department" style="width:220px;" onchange="javascript:load_overtime_list_dt_withselectionfilter();">
                <option value="">-- Select Department --</option>
                    <?php foreach($department as $d): ?>
                        <option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo $d->getTitle(); ?></option>
                    <?php endforeach; ?>
                </select>
            </h1><div class="mtshad"></div></div>
        <div id="main">
            <div class="module_content">
                <div id="message_container" style="margin-top: 20px; padding: 0 .7em; width:30%; display:none" class="ui-state-highlight ui-corner-all"> 
                    <div><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
                    <span class="message"></span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:$('#message_container').hide()" style="float:right" title="Close"></a></div>
                </div><!-- #message_container -->
                <?php $this->showContent();?>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php');?>


