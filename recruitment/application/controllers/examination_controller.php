<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Examination_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		Loader::appStyle('style.css');
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];	
		$this->eid                  = $_SESSION['sprint_hr']['employee_id'];
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		
		$this->applicant_id 			 = $_SESSION['sprint_applicant']['applicant_id'];
		$this->company_structure_id = $_SESSION['sprint_applicant']['company_structure_id'];
		$this->username				 = $_SESSION['sprint_applicant']['username'];		
		if($this->applicant_id){
			$count = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));
												
			$this->a_has_applicant_info = $count;			
			$this->is_profile_exist 	 = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));			 
			$this->ahid 				    =  Utilities::createHash($this->applicant_id);			
			$this->aeid 				    =  Utilities::encrypt($this->applicant_id);			
		}
	}

	function index()
	{
		$this->applicant_login();
		Loader::appMainScript("generic/main.js");
		unset($_SESSION['sprinthr']['tmp_exam_timer']);
		$this->var['token'] = Utilities::createFormToken();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		$this->var['ecode'] = $_GET['code'];
		$this->var['title'] = "Take an Exam";
		$this->var['module_title'] = "Examination";
		$this->view->setTemplate('template.php');
		$this->view->render('examination/index.php',$this->var);	
	}
	
	function _verify_exam_code()
	{
		//	Utilities::verifyFormToken($_POST['token']);
		$examination = G_Applicant_Examination_Finder::findByExamCode($_POST['exam_code']);
		if($examination){
			$examination = G_Applicant_Examination_Finder::findByExamCode($_POST['exam_code']);
			if($examination->status!='Pending') {
				echo -1;
			}else{
				echo Utilities::encrypt($examination->id);	
			}
		}else{
			echo 0;	
		}
	}
	
	function _get_examination_summary()
	{
		$examination = G_Applicant_Examination_Finder::findById(Utilities::decrypt($_POST['applicant_examination_id']));
		$applicant = G_Applicant_Finder::findById($examination->getApplicantId());
		$this->var['exam_id'] = $examination->getId();
		$this->var['applicant_name'] = $applicant->lastname . ', ' . $applicant->firstname;
		$this->var['exam_title'] = $examination->title;
		$this->var['passing_percentage'] = $examination->passing_percentage;
		$this->view->noTemplate();
		$this->view->render('examination/examination_summary.php',$this->var);
		
	}
	
	function start_exam(){
		$this->applicant_login();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainSimpleCountdownTimer();
	
		$applicant_examination_id = Utilities::decrypt($_GET['examination']);
		
		$examination = G_Applicant_Examination_Finder::findById($applicant_examination_id);
		
		//Tools::showArray($examination);
		if($examination) {			
			//echo $examination->applicant_id;
			$ex = G_Exam_Finder::findById($examination->getExamId());			
			$e  = G_Applicant_Finder::findById($examination->applicant_id);
			if($e){				
				if($e->getPhoto() != ""){					
					$file = HR_BASE_FOLDER . 'files/photo/'  . $e->getPhoto();					
				}else{
					$app = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($this->applicant_id));
					$file = HR_BASE_FOLDER . 'files/photo/'  . $app->getPhoto();
				}		
			}else{
			
			}
			
			//                                                                                                        unset($_SESSION['sprinthr']['tmp_exam_timer']);
			$question = G_Exam_Question_Finder::findByExamId($examination->exam_id);
			$this->var['applicant_examination_id'] = $applicant_examination_id;
			$this->var['exam_id'] 		= $examination->exam_id;
			$this->var['q'] 			= $question;
			$this->var['valid'] 		= true;
			
			$this->var['examination']	= $examination;
			$this->var['applicant'] 	= $e;

			if(Tools::isFileExist($file)==1) {
				$this->var['filemtime'] = md5($e->getPhoto()).date("His");
				$this->var['filename'] = $file;
				
			}else {				
				$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
				
			}
			
			//limit duration
			if(!$_SESSION['sprinthr']['tmp_exam_timer']) {				
				if($ex->getTimeDuration() != "") {
					$time 	= explode(':',$ex->getTimeDuration());									
					$hour 	= ($time[0] ? $time[0] : '00');
					$minute = ($time[1] ? $time[1] : '00');
					$second = ($time[2] ? $time[2] : '00');					
				} else { 				
					$hour 	= "01";
					$minute = "00";
					$secomd = "00";
				}
				
				$arr_time	= array("hour"=>$hour,"minute"=>$minute,"second"=>$second);				
				//$arr_time	= array("hour"=>"00","minute"=>"00","second"=>"3");
				$_SESSION['sprinthr']['tmp_exam_timer'] = $arr_time;
				
				/*
				$date_now	= date("Y-").(date("m")-1).date("-d");
				$plus_date	= strtotime('+ ' .$time[0].' day',strtotime($date_now));
				
				$year	= date('Y',$plus_date);
				$month	= date('m',$plus_date);
				$day	= date('d',$plus_date);
			
				$arr_date 	= array("year"=>$year,"month"=>$month,"day"=>$day,"hour"=>$time[1],"minute"=>$time[2]);
				
				*/
				//$now = new DateTime(); 
				//echo $now->format("M j, Y H:i:s O")."\n"; 
							
				//Tools::showArray($arr_date);
				
			} else {
				//Tools::showArray($_SESSION['sprinthr']['tmp_exam_timer']);
				$arr_time = $_SESSION['sprinthr']['tmp_exam_timer'];
			}

			$this->var['time_duration'] = date("M j, Y H:i:s O",$plus_date);
			$this->var['t'] = $arr_time;
			
			if($examination->status!='Pending') {
				Utilities::error500();		
			}
		}else {
			//echo "Invalid Examination";
			$this->var['valid'] = false;
			Utilities::error500();	
		}
		
		$this->var['page_title'] 	= "Examination";
		
		$view = 'examination/form/examination_form.php';
		$this->view->setTemplate('template_exam.php');
		$this->view->render($view,$this->var);	
	}
	
	function _record_time() {
		if(!empty($_POST)) {
			$arr_time	= array("hour"=>$_POST['hour'],"minute"=>$_POST['minute'],"second"=>$_POST['second']);	
			$_SESSION['sprinthr']['tmp_exam_timer'] = $arr_time;
		}
	}
	
	function _finish_answering_examination()
	{
		//echo "<pre>";
		//print_r($_POST);
		unset($_SESSION['sprinthr']['tmp_exam_timer']);
		
		$exam = G_Exam_Finder::findById($_POST['exam_id']);
		$applicant = G_Applicant_Examination_Finder::findById($_POST['applicant_examination_id']);
		$question = G_Exam_Question_Finder::findByExamId($exam->getId());
		//echo "<pre>";
		//print_r($exam);
		//print_r($applicant);
			header("Content-Type:text/xml");
			$xml = new Xml;
			$ob->examination->applicant_examination_id = $_POST['applicant_examination_id'];
			$ob->examination->exam_id = $_POST['exam_id'];
			$ob->examination->title = $exam->title;;
			$ob->examination->description = $exam->description;
			$ob->examination->passing_percentage = $exam->passing_percentage;
			$ob->examination->exam_code = $applicant->exam_code;
			$ob->examination->schedule_date = $applicant->schedule_date;
			$ob->examination->status= 'Need to be checked';
			$ob->examination->result = '';
			$e = G_Employee_Finder::findById($applicant->scheduled_by);
			$ob->examination->scheduled_by = $e->lastname. ', ' . $e->firstname ;
			$ctr=1;
			$correct=0;
			$for_checking=0;
			$incorrect=0;
			$total=0;
			foreach($question as $key=>$val) {
				//echo $val;
				//echo "<br>";
				
				$user_answer = (stripslashes($_POST['answer_'.$val->id]) ? stripslashes($_POST['answer_'.$val->id]) : 'No Given Answer / Blank');
				
				$var = 'question_'.$ctr;
				$ob->$var->id=$val->id;
				$ob->$var->question =$val->question;
				$ob->$var->answer=$val->answer;
				$ob->$var->user_answer=$user_answer; 
				$ob->$var->type=$val->type; 
				echo "your answer " . stripslashes($_POST['answer_'.$val->id]);
				echo "<pre>";
				echo "correct answer " . $val->answer;
				if($val->type=='choices') {
					if(strtolower($val->answer)==strtolower(stripslashes($_POST['answer_'.$val->id]))) {
						echo "correct";
						$ob->$var->result="correct"; 
						$correct++;	
					}else {
						echo "incorrect";
						$ob->$var->result="incorrect"; 
						$incorrect++;	
					}
				}else if($val->type=='essay') {
					$ob->$var->result="need to be checked";
					$for_checking++;
				}else if($val->type=='blank') {
					if(strtolower($val->answer)==strtolower(stripslashes($_POST['answer_'.$val->id]))) {
						$ob->$var->result="correct"; 
						$correct++;	
					}else {
						$ob->$var->result="incorrect";
						$incorrect++; 		
					}
				}

				$ctr++;
				$total++;

			}
			//print_r($obj);
			$xml->setNode('questions');
			//----test object----
			$xmlObj =  $xml->toXml($ob);
			//$xmlStr = simplexml_load_string($xmlObj);
			
			//$xml2 = new Xml;
			//$arrXml = $xml2->objectsIntoArray($xmlStr);
			//echo "<pre>";
			//print_r($arrXml);	
			$ini = $correct/$total;
			$grade = $ini * 100;
			$result = $correct .'/'.$total .'('.number_format($grade,2).'%)';
			$applicant->setQuestions($xmlObj);
			$applicant->setStatus('For Checking');
			$applicant->setDateTaken($this->c_date);
			$applicant->setResult($result);
			$applicant->save();
			echo 1;

	}
	
	function _test_xml()
	{
		/*$xmlUrl = "catalog.xml"; // XML feed file/URL
		//$xmlStr = file_get_contents($xmlUrl);
		$xmlObj = simplexml_load_string($xmlStr);
		$xml = new Xml;
		$arrXml = $xml->objectsIntoArray($xmlObj);
		echo "<pre>";
		print_r($arrXml);	*/
		
		
		//$exam = G_Exam_Finder::findById(1);
		//echo "<pre>";
		//print_r($exam);
			header("Content-Type:text/xml");
			$xml = new Xml;
			$ob->examination->applicant_examination_id = 1;
			$ob->examination->exam_id = 2;
			$ob->examination->title = 'Designing Terminology';
			$ob->examination->description = 'test';
			$ob->examination->passing_percentage = 45;
			$ob->examination->exam_code = '02ff02';
			$ob->examination->schedule_date = '2012-03-03';
			$ob->examination->status= 'pending';
			$ob->examination->result = 'passed';
			$ob->examination->scheduled_by = 2;
			
			$ob->question_1->id=10;
			$ob->question_1->question ="Ilan ang kuto ni darna?";
			$ob->question_1->answer="3";
			$ob->question_1->user_answer="3"; 
			$ob->question_1->type="blank"; 
			$ob->question_1->result="correct"; 
			
			
			$ob->question_2->id=12;
			$ob->question_2->question ="ilan ang kuto ni joy?";
			$ob->question_2->answer="300"; 
			$ob->question_2->user_answer="3";
			$ob->question_2->type="essay"; 
			$ob->question_2->result="wrong"; 
			$xml->setNode('questions');
			//----test object----
			echo $xmlObj =  $xml->toXml($ob);
			//$xmlStr = simplexml_load_string($xmlObj);
			
			//$xml2 = new Xml;
			//$arrXml = $xml2->objectsIntoArray($xmlStr);
			//echo "<pre>";
			//print_r($arrXml);
	
	}
	
	function choose_examination() {
		if(!empty($_GET)) {
			$h_app_id 	= $_GET['app_id'];
			$hash 		= $_GET['hash'];
			Utilities::verifyHash(Utilities::decrypt($h_app_id),$hash);
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
			
			/*$app_id = Utilities::decrypt($h_app_id);
			$examination = G_Applicant_Examination_Finder::findByApplicantId2($app_id);
			if($examination) {
				echo "Already";
				
			} else {
					
			}*/
			
			$this->var['page_title'] 	= "Choose Examination";
			$this->var['examination'] 	= $examination = G_Exam_Finder::findAll();
			$this->var['token'] 		= Utilities::createFormToken();
			$this->var['h_app_id']		= $h_app_id;
			
			$this->view->setTemplate('template.php');
			$this->view->render('examination/choose_exam.php',$this->var);	
		}
		
	}
	
	function create_exam_set() {
		if(!empty($_POST)) {
			unset($_SESSION['sprinthr']['tmp_exam_timer']);
			//Utilities::verifyFormToken($_POST['token']);
			$exam = G_Exam_Finder::findById(Utilities::decrypt($_POST['h_exam_id']));
			if($exam) {
				$gcb = new G_Applicant_Examination();
				$gcb->setCompanyStructureId($this->company_structure_id);
				$gcb->setApplicantId(Utilities::decrypt($_POST['h_app_id']));
				$gcb->setExamId(Utilities::decrypt($_POST['h_exam_id']));
				$gcb->setTitle($exam->getTitle());
				$gcb->setDescription($exam->getDescription());
				$gcb->setPassingPercentage($exam->getPassingPercentage());
				$gcb->setScheduleDate(date("Y-m-d"));
				$gcb->setStatus(G_Applicant_Examination::PENDING);
				$gcb->setResult();
				$gcb->setQuestions($row['questions']);
				$gcb->setTimeDuration($exam->getTimeDuration());
				$gcb->setScheduledBy(Utilities::decrypt($this->eid));
				$exam_id = $gcb->save();
				
				$json['h_exam_id'] 	= Utilities::encrypt($exam_id);
				$json['is_saved']	= true;
				
				//redirect('examination/start_exam?examination='.Utilities::encrypt($exam_id));
			} else {
				$json['is_saved']	= false;	
				$json['message']	= "Error : Cannot generate exam question.";
			}
		} else {
			$json['is_saved']	= false;	
			$json['message']	= "Error : Invalid Argument!";
		}
		echo json_encode($json);
	}
	
}
?>