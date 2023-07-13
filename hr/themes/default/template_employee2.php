<?php include('includes/header.php');?>
<div id="content" class="sidebar_left">
	<div class="mtcntnr">
    	<?php if($can_manage) { ?>
       	 <div style="float:right; padding:5px 0;">
         	<a class="gray_button" href="javascript:void(0);" onclick="javascript:_printEmployeeDetails('<?php echo $employee_id; ?>');"><i class="icon-print"></i> Print Employee Details</a>           
         </div>
         <div id="print_employee_details_modal_wrapper"></div>
        <?php } ?>
		<h1 class="module_title"><i class="mticon_img icon_employee"></i><?php echo $page_title;?><?php //echo $page_subtitle;?><a class="gray_button title_back_button" href="javascript:history.go(-1)"><i></i>Back</a></h1><div class="mtshad"></div>        
        <div class="clear"></div>
    </div>
    <div class="holder_sidecontent clearfix">
		<?php include('includes/submenu_employee.php');?>
        <div class="sidebar_maincontent">
        	<div class="maincontent">
                <div id="message_container" style="width:auto; display:none" class="ui-state-highlight ui-corner-all message_box"> 
                    <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
                    <span class="message"></span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:$('#message_container').hide()" style="float:right" title="Close"></a>
                </div>
	            <?php $this->showContent();?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php include('includes/footer.php');?>