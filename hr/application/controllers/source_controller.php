<?php
class Source_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		Loader::appMainStyle('style.css');
		$menu = get_param(1);
		if($menu=='plugins' || $menu=='index.php') {
			$this->var['plugins'] = 'selected';	
		}elseif($menu=='jquery') {
			$this->var['jquery'] = 'selected';	
		}elseif($menu== 'libraries') {
			$this->var['libraries'] = 'selected';	
		}elseif($menu== 'themes') {
			$this->var['themes'] = 'selected';	
		}elseif($menu== 'reports') {
			$this->var['reports'] = 'selected';	
		}	
	}

	function index()
	{
		self::plugins();
	}
	
	function plugins() {
		if($_GET['sidebar']=='autocomplete' || $_GET['sidebar']=='') {
			$views = self::autocomplete();
		}elseif($_GET['sidebar']=='textboxlist') {
			$views = self::textboxlist();
		}elseif($_GET['sidebar']=='chart') {
			$views = self::chart();			
		}elseif($_GET['sidebar']=='datatable') {
			$views = self::datatable();			
		}
		$this->view->setTemplate('template_plugins.php');
		$this->view->render($views,$this->var);
	}
	
	
	function jquery() 
	{
		if($_GET['sidebar']=='jquery' || $_GET['sidebar']=='') {
			$this->var['page_title'] = "Jquery";
			$views = 'krikel/jquery/jquery.php';
		}elseif($_GET['sidebar']=='ui') {
			$views = self::ui();
		}elseif($_GET['sidebar']=='validator') {
			$views = self::validator();			
		}elseif($_GET['sidebar']=='inline_validation') {
			$views = self::inline_validation();			
		}elseif($_GET['sidebar']=='jquery_upload') {	
			$views = self::file_upload();	
		}elseif($_GET['sidebar']=='image_lightbox') {	
			$views = self::image_light_box();	
		}elseif($_GET['sidebar']=='block_ui') {	
			$views = self::block_ui();	
		}elseif($_GET['sidebar']=='pretty_photo') {	
			$views = self::pretty_photo();	
		}elseif($_GET['sidebar']=='dialog') {	
			$views = self::dialog();	
		}elseif($_GET['sidebar']=='date_rangepicker') {	
			$views = self::date_range_picker();	
		}elseif($_GET['sidebar']=='form_submit') {	
			$views = self::form_submit();	
		}
		
		$this->view->setTemplate('template_jquery.php');
		$this->view->render($views,$this->var);
	}
	
	function libraries(){
		
		if($_GET['sidebar']=='tools' || $_GET['sidebar']=='') {
			$this->var['page_title'] = "Tools";
			$views = 'krikel/libraries/tools.php';
		}elseif($_GET['sidebar']=='date') {	
			$views = self::class_date();	
		}elseif($_GET['sidebar']=='server_validation') {	
			$views = self::class_validate();	
		}
		
		$this->view->setTemplate('template_libraries.php');
		$this->view->render($views,$this->var);
	}
	
	function themes() 
	{
		if($_GET['sidebar']=='themes' || $_GET['sidebar']=='') {
			$views = self::table_theme();
			
		}elseif($_GET['sidebar']=='text_curve') {	
			$views = self::curve();	
		}elseif($_GET['sidebar']=='periodic_table') {	
			$views = self::periodic_table();	
		}elseif($_GET['sidebar']=='periodic_table_form') {	
			$views = self::periodic_table_form();	
		}elseif($_GET['sidebar']=='forms') {	
			$views = self::form_design();	
		}
		
		
			
		$this->view->setTemplate('template_themes.php');
		$this->view->render($views,$this->var);
	}
	
	function reports() 
	{
		if($_GET['sidebar']=='pdf_writer' || $_GET['sidebar']=='') {
			$views = self::pdf_writer();
			
		}elseif($_GET['sidebar']=='excel') {	
			$views = self::excel();	
		}elseif($_GET['sidebar']=='doc') {	
			$views = self::doc();	
		}
		
		$this->view->setTemplate('template_reports.php');
		$this->view->render($views,$this->var);
	}
	
	function pdf_writer()
	{
	
		$this->var['page_title'] = "PDF Writer";
		$views = 'krikel/reports/pdf_writer.php';
		return $views;
	}
	
	function _load_pdf_output()
	{
		Loader::appMainLibrary('class_pdf_writer');		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetFont('dejavusans', '', 10);
		$pdf->AddPage();
		$html = 'This is a test';
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		$pdf->Output('payslip.pdf', 'D');
	}
	
	function excel()
	{
		$this->var['page_title'] = "Excel";
		$views = 'krikel/reports/excel.php';
		return $views;
	}
	
	function doc()
	{
		$this->var['page_title'] = "Doc";
		$views = 'krikel/reports/doc.php';
		return $views;
	}
	
	function _load_excel() 
	{
		$this->view->noTemplate();
		$this->view->render('krikel/reports/print_excel.php',$this->var);	
	}
	
	function _load_doc() 
	{
		$this->view->noTemplate();
		$this->view->render('krikel/reports/print_doc.php',$this->var);	
	}
	
	
	function curve()
	{
		
		$this->var['page_title'] = "Curve";
		$views = 'krikel/themes/curve.php';
		return $views;
	}
	
	function periodic_table()
	{
		Style::loadMainPeriodicTable();
		$this->var['page_title'] = "Periodic Table";
		$views = 'krikel/themes/periodic_table.php';
		return $views;
	}
	
	function periodic_table_form()
	{
		Style::loadMainPeriodicTableForm();
		$this->var['page_title'] = "Periodic Table with Form";
		$views = 'krikel/themes/periodic_table_form.php';
		return $views;
	}
	
	function form_design()
	{
		Style::loadMainFormDesign();
		$this->var['page_title'] = "Form Design";
		$views = 'krikel/themes/form_design.php';
		return $views;
	}
	
	
	function table_theme()
	{
		Style::loadMainTableThemes();
		$this->var['page_title'] = "Themes";
		$views = 'krikel/themes/themes.php';
		return $views;
	}
	
	function class_validate()
	{
		$this->var['page_title'] = "Class Validate";
		$views = 'krikel/libraries/validate.php';
		return $views;
	}
	
	function class_date() 
	{
		$this->var['page_title'] = "Class Date";
		$views = 'krikel/libraries/date.php';
		return $views;
	}
	
	
	function autocomplete() 
	{
		$this->var['page_title'] = "Jquery Autocomplete";
		$views = 'krikel/plugins/autocomplete.php';
		return $views;
	}
	
	function textboxlist() 
	{	
		Jquery::loadMainTextBoxList();
		$this->var['page_title'] = "Textbox List";
		$views = 'krikel/plugins/textboxlist.php';
		return $views;
	}
	
	function _autocomplete() {
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
		$sql = "
				SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name
				FROM g_user u
				WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%')
				";
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$response[] = array('id'=>$record['id'],'label'=>$record['name']);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}
		
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function _get_names_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {
			$sql = "
				SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name
				FROM g_user u
				WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%')
				";
			
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$response[] = array($record['id'], $record['name'], null);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	
	function datatable() {
		
		Yui::loadMainDatatable();
		$this->var['page_title'] = "YUI Datatable";
		$views = 'krikel/plugins/datatable.php';
		return $views;
	}
	
	function _update_container() 
	{
		print_r($_POST);
		$_SESSION['list_box']='';
		if($_POST['list_box']!=''){
			$_SESSION['list_box'] = $_POST['list_box'];	
		}else {
			$_SESSION['list_box'] = '';	
		}
		print_r($_SESSION['list_box']);	
	}
	
	function _json_encode_user_datatable() {
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? $_GET['sort'] . ' ' . $_GET['dir']  :  'id asc' ;
	
		$sql = "SELECT * FROM g_user ORDER BY ".$order_by. " " . $limit ;

		$data = Model::runSql($sql,true);
		
		$sql2 = "SELECT COUNT(*) as total FROM g_user";

		$count_total =  Model::runSql($sql2,true);
		$total = count($data);
		$total_records =$count_total[0]['total'];
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function chart() {
		$this->var['page_title'] = "Chart";
		
		Loader::appMainLibrary('FusionCharts');
		if($_GET['sub_sidebar']==1) {
			$this->var['one'] ='selected';
			$views = 'krikel/plugins/chart.php';
		}elseif($_GET['sub_sidebar']==2) {
			$this->var['two'] = 'selected';
			$views = 'krikel/plugins/chart2.php';
		}elseif($_GET['sub_sidebar']==3) {
			$this->var['three'] = 'selected';
			$views = 'krikel/plugins/chart3.php';
		}elseif($_GET['sub_sidebar']==4) {
			$this->var['four'] = 'selected';
			$views = 'krikel/plugins/chart4.php';
		}elseif($_GET['sub_sidebar']==5) {
			$this->var['five'] = 'selected';
			$views = 'krikel/plugins/chart5.php';
		}elseif($_GET['sub_sidebar']==6) {
			$this->var['six'] = 'selected';
			$views = 'krikel/plugins/chart6.php';
		}elseif($_GET['sub_sidebar']==7 || $_GET['sub_sidebar']=='') {
			$this->var['usage'] = 'selected';
			$views = 'krikel/plugins/chart_usage.php';
		}
		return $views;
	}
	
	
	
	function date_range_picker()
	{
		Jquery::loadMainDateRangePicker();
		$this->var['page_title'] = "Date Range Picker";
		$views = 'krikel/jquery/date_range_picker.php';
		return $views;
	}
	
	function dialog() 
	{
		Jquery::loadMainValidator();
		$this->var['page_title'] = "Dialog";
		$views = 'krikel/jquery/dialog.php';
		return $views;
	}
	
	function pretty_photo()
	{
		Jquery::loadMainPrettyPhoto();
		$this->var['page_title'] = "Jquery Pretty Photo";
		$views = 'krikel/jquery/pretty_photo.php';
		return $views;
	}
	
	function block_ui() 
	{
		$this->var['page_title'] = "Block UI";
		$views = 'krikel/jquery/block_ui.php';
		return $views;
	}
	
	function image_light_box() 
	{
		Jquery::loadMainVisualLightBox();
		
		$this->var['page_title'] = "Image Lightbox";
		$views = 'krikel/jquery/image_lightbox.php';
		return $views;
	}
	
	function inline_validation() 
	{
		Jquery::loadMainInlineValidation();
		Loader::appMainStyle('assets/inlinevalidation/template.css');
		$this->var['page_title'] = "Inline Validation";
		$views = 'krikel/jquery/inlinevalidation.php';
		return $views;
		
	}
	
	function file_upload() 
	{
		//Jquery::loadValidator();
		Jquery::loadMainFileUpload();
		$this->var['page_title'] = "Jquery File Upload";
		$views = 'krikel/jquery/fileupload.php';
		return $views;
	
	}
	
	function _upload() 
	{
		//print_r($_POST);
		Loader::appMainLibrary('class_main_jquery_file_upload');	
	}
	
	function form_submit()
	{
		Jquery::loadMainJqueryFormSubmit();
		
		$this->var['page_title'] = "Form Submit";
		$views = 'krikel/jquery/form_submit.php';
		return $views;	
	}
	
	function _ajax_form() 
	{
		print_r($_POST);	
	}
	
	
	function ui() 
	{
		$this->var['page_title'] = "UI";
		$views = 'krikel/jquery/ui/index.php';
		return $views;
	}
	
	function validator() 
	{
		Jquery::loadMainValidator();
		$this->var['page_title'] = "Validator";
		$views = 'krikel/jquery/validator.php';
		return $views;
	}
	
	function json_username_check() {
		$new_username = $_POST['username'];
		
		$isExist = Model::runSql("SELECT * FROM g_user WHERE  username='".$new_username."' ",true);
		if(count($isExist)>0) {
			
			echo "false";
		}else {
			echo "true";	
		}
	}

	
}
?>