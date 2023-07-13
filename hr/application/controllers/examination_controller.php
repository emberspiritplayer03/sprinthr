<?php
class Examination_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		Loader::appStyle('style.css');
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];	
		
		Utilities::checkModulePackageAccess('hr','examination');
	}

	function index()
	{
		$this->var['token'] = Utilities::createFormToken();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		$this->var['title'] = "Take an Exam";
		$this->view->setTemplate('template.php');
		$this->view->render('examination/index.php',$this->var);	
	}
	
	function _verify_exam_code()
	{
		//print_r($_POST);
	//	Utilities::verifyFormToken($_POST['token']);
		$examination = G_Applicant_Examination_Finder::findByExamCode($_POST['exam_code']);
		if($examination){
			$examination = G_Applicant_Examination_Finder::findByExamCode($_POST['exam_code']);
			if($examination->status!='Pending') {
				echo -1;
			}else {
				echo Utilities::encrypt($examination->id);	
			}
		}else
		{
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
	
	function start_exam()
	{
		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		
		
		$applicant_examination_id = Utilities::decrypt($_GET['examination']);
		
		$examination = G_Applicant_Examination_Finder::findById($applicant_examination_id);
		if($examination) {
			$question = G_Exam_Question_Finder::findByExamId($examination->exam_id);
			$this->var['applicant_examination_id'] = $applicant_examination_id;
			$this->var['exam_id'] = $examination->exam_id;
			$this->var['q'] = $question;
			$this->var['valid'] = true;
			if($examination->status!='Pending') {
				Utilities::error500();		
			}
		}else {
			//echo "Invalid Examination";
			$this->var['valid'] = false;
			Utilities::error500();	
		}
	
		$view = 'examination/form/examination_form.php';
		$this->view->setTemplate('template.php');
		$this->view->render($view,$this->var);	
	}
	
	function _finish_answering_examination()
	{
	//	echo "<pre>";
		//print_r($_POST);
		
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
				
				$var = 'question_'.$ctr;
				$ob->$var->id=$val->id;
				$ob->$var->question =$val->question;
				$ob->$var->answer=$val->answer;
				$ob->$var->user_answer=stripslashes($_POST['answer_'.$val->id]); 
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
			//$applicant->setStatus('Pending');
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
	

}
?>