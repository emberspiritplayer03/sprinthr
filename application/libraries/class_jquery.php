<?php
/*Updated: January 5,2012
* By: Marvin Dungog
*
*/

class Jquery {
	
	//Usage: Jquery::loadTextBoxList();
	
	public static function loadMainSimpleCountdownTimer() {
		Loader::appMainScript('simple_countdowntimer/jquery.countdown.js');
		Loader::appMainStyle('assets/simple_countdowntimer/jquery.countdown.css');
	}
	
	public static function loadMainKdatatable() {
		Loader::appMainScript('kdatatable/jquery.kdatatable.js');
		Loader::appMainScript('kdatatable/jquery.fixheadertable.min.js');
		Loader::appMainScript('kdatatable/jquery.tablesorter.js');	
			
		Loader::appMainStyle('kdatatable/datatable.css');
		Loader::appMainStyle('kdatatable/sorter.css');
	}	
	
	public static function loadTextBoxList($element) {
		Loader::appStyle('assets/textboxlist/jquery.textboxlist.css');
		Loader::appScript('textboxlist/jquery.textboxlist.js');
	}
	
	public static function loadMainBootStrapDropDown() {
		Loader::appMainScript('bootstrap/jquery.bootstrap.core.js');		
		Loader::appMainScript('bootstrap/bootstrap-dropdown.js');
	}
	
	public static function loadMainTagBox() {
		Loader::appMainStyle('assets/tagbox/jquery.tagbox.css');
		Loader::appMainScript('tagbox/jquery.tagbox.js');
	}
	
	public static function loadMainTextBoxList($element) {
		Loader::appMainStyle('assets/textboxlist/jquery.textboxlist.css');
		Loader::appMainScript('textboxlist/jquery.textboxlist.js');
		
		//Loader::appMainStyle('assets/textboxlist/TextboxList.css');
		//Loader::appMainStyle('assets/textboxlist/TextboxList.Autocomplete.css');
		
		
		//Loader::appMainScript('textboxlist/GrowingInput.js');
		//Loader::appMainScript('textboxlist/TextboxList.js');
		//Loader::appMainScript('textboxlist/TextboxList.Autocomplete.js');
		//Loader::appMainScript('textboxlist/TextboxList.Autocomplete.Binary.js');
		
	}
	
	public static function loadMainUploadify() {
		Loader::appMainScript('uploadify/jquery.uploadify.v2.1.4.min.js');
		Loader::appMainScript('uploadify/swfobject.js');
		Loader::appMainStyle('assets/uploadify/uploadify.css');
	}	

	public static function loadMainBootStrapCollapsible() {
		Loader::appMainScript('bootstrap/jquery.bootstrap.core.js');	
		Loader::appMainScript('bootstrap/bootstrap-collapse.js');				
	}
	
	public static function loadUploadify() {
		Loader::appScript('uploadify/jquery.uploadify.v2.1.4.min.js');
		Loader::appScript('uploadify/swfobject.js');
		Loader::appStyle('assets/uploadify/uploadify.css');
	}
	
	public static function loadMainJTags() {
		Loader::appMainScript('jtags/jquery.tagsinput.js');
		Loader::appMainStyle('jtags/jquery.tagsinput.css');
	}
	
	public static function loadJTags() {
		Loader::appScript('jtags/jquery.tagsinput.js');
		Loader::appStyle('jtags/jquery.tagsinput.css');
	}
	
	public static function loadValidator() {
		Loader::appScript('jqueryform/jquery.form.js');
		Loader::appStyle('assets/validator/form_validator.css');
		Loader::appScript('validator/jquery.validate.js');

	}
	
	public static function loadMainValidator() {
		Loader::appMainScript('validator/jquery.validate.js');
		Loader::appMainScript('jqueryform/jquery.form.js');
		Loader::appMainStyle('assets/validator/form_validator.css');
		

	}
	
	
	
	public static function loadInlineValidation()
	{
		Loader::appScript('inlinevalidation/jquery.validationEngine-en.js');
		Loader::appScript('inlinevalidation/jquery.validationEngine.js');
		Loader::appStyle('assets/inlinevalidation/validationEngine.jquery.css');
	}
	
	public static function loadMainInlineValidation()
	{
		Loader::appMainScript('inlinevalidation/jquery.validationEngine-en.js');
		Loader::appMainScript('inlinevalidation/jquery.validationEngine.js');
		Loader::appMainStyle('assets/inlinevalidation/validationEngine.jquery.css');
	}
	
	public static function loadMainInlineValidation2()
	{
		Loader::appMainScript('inlinevalidation/jquery.validationEngine-en.js');
		Loader::appMainScript('inlinevalidation/jquery.validationEngine2.js');
		Loader::appMainStyle('assets/inlinevalidation/validationEngine.jquery.css');
	}
	
	public static function loadInlineValidation2()
	{
		Loader::appScript('inlinevalidation/jquery.validationEngine-en.js');
		Loader::appScript('inlinevalidation/jquery.validationEngine2.js');
		Loader::appStyle('assets/inlinevalidation/validationEngine.jquery.css');
	}
	
	
	public static function loadFileUpload() {
		Loader::appScript('jqueryfileupload/jquery.tmpl.min.js');
		Loader::appScript('jqueryfileupload/jquery.iframe-transport.js');
		Loader::appScript('jqueryfileupload/jquery.fileupload.js');
		Loader::appScript('jqueryfileupload/jquery.fileupload-ui.js');
		Loader::appScript('jqueryfileupload/application.js');
		Loader::appStyle('assets/jqueryfileupload/jquery.fileupload-ui.css');
	}
	
	public static function loadMainFileUpload() {
		Loader::appMainScript('jqueryfileupload/jquery.tmpl.min.js');
		Loader::appMainScript('jqueryfileupload/jquery.iframe-transport.js');
		Loader::appMainScript('jqueryfileupload/jquery.fileupload.js');
		Loader::appMainScript('jqueryfileupload/jquery.fileupload-ui.js');
		Loader::appMainScript('jqueryfileupload/application.js');
		Loader::appMainStyle('assets/jqueryfileupload/jquery.fileupload-ui.css');
	}
	
	public static function loadVisualLightBox() {
		Loader::appScript('visuallightbox/jquery.lightbox-0.5.min.js');
		
		Loader::appStyle('assets/visuallightbox/jquery.lightbox-0.5.css');
	}
	
	public static function loadMainVisualLightBox() {
		Loader::appMainScript('visuallightbox/jquery.lightbox-0.5.min.js');
		
		Loader::appMainStyle('assets/visuallightbox/jquery.lightbox-0.5.css');
	}
	
	public static function loadPrettyPhoto() {
		Loader::appScript('prettyphoto/jquery.prettyPhoto.js');
		
		Loader::appStyle('assets/prettyphoto/prettyPhoto.css');
	}
	
	public static function loadMainPrettyPhoto() {
		Loader::appMainScript('prettyphoto/jquery.prettyPhoto.js');
		
		Loader::appMainStyle('assets/prettyphoto/prettyPhoto.css');
	}
	
	public static function loadCDropDown() {
		Loader::appMainScript('cdropdown/modernizr.custom.79639.js');
		
		Loader::appMainStyle('cdropdown/font-awesome.css');
		Loader::appMainStyle('cdropdown/demo.css');
		Loader::appMainStyle('cdropdown/noJS.css');
		Loader::appMainStyle('cdropdown/style.css');
	}
	
	
	public static function loadDateRangePicker()
	{
		Loader::appScript('date_range_picker/jquery.daterangepicker.js');
		Loader::appStyle('assets/daterangepicker/ui.daterangepicker.css');
	}
	
	public static function loadMainDateRangePicker()
	{
		Loader::appMainScript('date_range_picker/jquery.daterangepicker.js');
		Loader::appMainStyle('assets/daterangepicker/ui.daterangepicker.css');
	}
	
	public static function loadJqueryFormSubmit()
	{
		Loader::appScript('jqueryform/jquery.form.js');
	}
	
	public static function loadMainJqueryFormSubmit()
	{
		Loader::appMainScript('jqueryform/jquery.form.js');
	}
	
	public static function loadModalExetend()
	{
		Loader::appScript('modalextend/jquery.dialogextend.js');
	}
	
	public static function loadMainModalExetend()
	{
		Loader::appMainScript('modalextend/jquery.dialogextend.js');
	}
	
	public static function loadTipsy() {
		Loader::appScript('jquerytipsy/jquery.tipsy.js');
		Loader::appStyle('assets/tipsy/tipsy.css');	
	}
	
	public static function loadMainTipsy() {
		Loader::appMainScript('jquerytipsy/jquery.tipsy.js');
		Loader::appMainStyle('assets/tipsy/tipsy.css');	
	}
	
	public static function loadJqueryDatatable() {
		Loader::appScript('jquerydatatable/jquery.dataTables.js');
		Loader::appStyle('jquerydatatable/demo_table_jui.css');
		Loader::appStyle('assets/jquerydatatable/demo_page.css');			
	}
	
	public static function loadRootJqueryDatatable() {
		Loader::appScript('jquerydatatable/jquery.dataTables.js');
		Loader::appStyle('assets/jquerydatatable/demo_table_jui.css');
		Loader::appStyle('assets/jquerydatatable/demo_page.css');			
	}
	
	public static function loadMainJqueryDatatable() {
		Loader::appMainScript('jquerydatatable/jquery.dataTables.js');
		//Loader::appMainStyle('jquerythemes/default/jquery.css');
		Loader::appMainStyle('assets/jquerydatatable/demo_table_jui.css');
		Loader::appMainStyle('assets/jquerydatatable/demo_page.css');			
	}

	public static function loadMainEditInPlace() {
		Loader::appMainScript('edit_in_place/jquery.editinplace.js');			
	}
	
	public static function loadAsyncTreeView() {
		Loader::appMainScript('jquerytreeview/jquery.cookie.js');
		Loader::appMainScript('jquerytreeview/jquery.treeview.js');
		Loader::appMainScript('jquerytreeview/jquery.treeview.async.js');
		Loader::appMainStyle('assets/jquerytreeview/jquery.treeview.css');
	}
	
	public static function loadTreeView() {
		Loader::appScript('jquerytreeview/jquery.cookie.js');
		Loader::appScript('jquerytreeview/jquery.treeview.js');
		Loader::appStyle('assets/jquerytreeview/jquery.treeview.css');
		
		write_script("
		$(document).ready(function(){		
			$('" . $element . "').treeview({
				persist: 'location',
				collapsed: true,
				unique: false
			});
		});
		");	
	}
	
	public static function loadMainTreeView() {
		Loader::appMainScript('jquerytreeview/jquery.cookie.js');
		Loader::appMainScript('jquerytreeview/jquery.treeview.js');
		Loader::appMainStyle('assets/jquerytreeview/jquery.treeview.css');
		
		write_script("
		$(document).ready(function(){		
			$('" . $element . "').treeview({
				persist: 'location',
				collapsed: true,
				unique: false
			});
		});
		");	
	}
	
	public static function loadJqTransform() {
		Loader::appScript('jqtransform/jquery.jqtransform.js');
		Loader::appStyle('assets/jqtransform/jqtransform.css');
	}
	
	public static function loadMainJqTransform() {
		Loader::appMainScript('jqtransform/jquery.jqtransform.js');
		Loader::appMainStyle('assets/jqtransform/jqtransform.css');
	}

	public static function loadFullCalendar() {
		Loader::appScript('fullcalendar/moment.min.js');
		Loader::appScript('fullcalendar/jquery.min.js');
		Loader::appScript('fullcalendar/fullcalendar.min.js');
		Loader::appStyle('assets/fullcalendar/fullcalendar.css');
		Loader::appStyle('assets/fullcalendar/fullcalendar.print.css');
	}
	
	public static function loadMainFullCalendar() {
		Loader::appMainScript('fullcalendar/moment.min.js');
		Loader::appMainScript('fullcalendar/jquery.min.js');
		Loader::appMainScript('fullcalendar/fullcalendar.min.js');
		Loader::appMainStyle('assets/fullcalendar/fullcalendar.css');
		//Loader::appMainStyle('assets/fullcalendar/fullcalendar.print.css');
	}

	public static function loadSelect2() {
		Loader::appScript('select2/select2.min.js');
		Loader::appStyle('assets/select2/select2.min.css');
		//Loader::appMainStyle('assets/fullcalendar/fullcalendar.print.css');
	}

	public static function loadMainSelect2() {
		Loader::appMainScript('select2/jquery.min.js');
		Loader::appMainScript('select2/select2.min.js');
		Loader::appMainStyle('assets/select2/select2.min.css');
		//Loader::appMainStyle('assets/fullcalendar/fullcalendar.print.css');
	}
		
}

?>