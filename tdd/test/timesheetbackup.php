<?php
error_reporting(0);

define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

class TestDTRErrors extends UnitTestCase {

    function testDTR() {
        //$e = G_Employee_Finder::findByEmployeeCode('aaa');
        //$e->punchIn('2014-10-13', '08:39:00');
        //$e->punchIn('2014-10-13', '08:45:00');
        //$e->punchIn('2014-10-13', '08:49:00');

        //$e->punchOut('2014-10-13', '17:00:00');
        //$e->punchOut('2014-10-13', '17:39:00');
    }
        
    function testNoInNightShiftMultiSwipe() {
        $raw_timesheet[1]['in']['2012-10-18']['20:03:00'] = '2012-10-18';
        $raw_timesheet[1]['out']['2012-10-19']['07:15:00'] = '2012-10-19';
        $raw_timesheet[1]['in']['2012-10-20']['20:04:00'] = '2012-10-20';       
        $raw_timesheet[1]['out']['2012-10-21']['07:16:00'] = '2012-10-21';
        $raw_timesheet[1]['out']['2012-10-22']['05:35:00'] = '2012-10-22';
        $raw_timesheet[1]['out']['2012-10-22']['06:35:00'] = '2012-10-22';
        $raw_timesheet[1]['out']['2012-10-22']['07:35:00'] = '2012-10-22';
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $x = $tr->filter();
        $no_in = $tr->getErrorsNoIn();
        $correct_no_in[1]['2012-10-22'] = '07:35:00';

        $this->assertIdentical($no_in, $correct_no_in);
    }
        
    function testNoInNightShift() {
        $raw_timesheet[1]['in']['2012-10-18']['20:03:00'] = '2012-10-18';
        $raw_timesheet[1]['out']['2012-10-19']['07:15:00'] = '2012-10-19';
        //$raw_timesheet[1]['in']['2012-10-19']['20:11:00'] = '2012-10-19';
        $raw_timesheet[1]['out']['2012-10-20']['05:35:00'] = '2012-10-20';
        $raw_timesheet[1]['in']['2012-10-20']['20:04:00'] = '2012-10-20';       
        $raw_timesheet[1]['out']['2012-10-21']['07:16:00'] = '2012-10-21';  
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $x = $tr->filter();
        $no_in = $tr->getErrorsNoIn();
        
        $correct_no_in[1]['2012-10-20'] = '05:35:00';
        
        echo '<pre>';
        //print_r($x);
        //print_r($no_in);
        //print_r($no_out);     

        $this->assertIdentical($no_in, $correct_no_in); 
        //exit;
    }
    
    function testNoIn() {
        $raw_timesheet[1]['in']['2012-10-18']['07:15:00'] = '2012-10-18';
        $raw_timesheet[1]['out']['2012-10-18']['20:03:00'] = '2012-10-18';
        $raw_timesheet[1]['out']['2012-10-19']['05:11:00'] = '2012-10-19';
        $raw_timesheet[1]['out']['2012-10-19']['05:35:00'] = '2012-10-19';
        $raw_timesheet[1]['out']['2012-10-19']['06:35:00'] = '2012-10-19';      
        $raw_timesheet[1]['in']['2012-10-20']['07:16:00'] = '2012-10-20';   
        $raw_timesheet[1]['out']['2012-10-20']['20:04:00'] = '2012-10-20';
        $raw_timesheet[1]['out']['2012-10-21']['20:04:00'] = '2012-10-21';      
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        $no_in = $tr->getErrorsNoIn();
        
        $correct_no_in[1]['2012-10-21'] = '20:04:00';
        $correct_no_in[1]['2012-10-19'] = '06:35:00';
        
        echo '<pre>';
        //print_r($answer);
        //print_r($x);
        //print_r($no_in);
        //print_r($no_out);     

        $this->assertIdentical($no_in, $correct_no_in); 
        //exit;
    }
            
    function testNoInAndOut() {
        $raw_timesheet[1]['out']['2012-09-16']['18:29:00'] = '2012-09-16';
        $raw_timesheet[1]['out']['2012-09-17']['18:51:00'] = '2012-09-17';
        $raw_timesheet[1]['in']['2012-09-17']['07:53:00'] = '2012-09-17';       
        $raw_timesheet[1]['out']['2012-09-18']['19:03:00'] = '2012-09-18';  
        $raw_timesheet[1]['in']['2012-09-19']['07:53:00'] = '2012-09-19';
        $raw_timesheet[1]['out']['2012-09-20']['19:03:00'] = '2012-09-20';
        $raw_timesheet[1]['in']['2012-09-21']['08:03:00'] = '2012-09-20';
        $raw_timesheet[1]['out']['2012-09-21']['19:03:00'] = '2012-09-21';
        $raw_timesheet[1]['out']['2012-09-22']['19:03:00'] = '2012-09-22';      
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $tr->filter();
        $no_in = $tr->getErrorsNoIn();
        $no_out = $tr->getErrorsNoOut();
        
        $correct_no_in[1]['2012-09-16'] = '18:29:00';
        $correct_no_in[1]['2012-09-18'] = '19:03:00';
        $correct_no_in[1]['2012-09-20'] = '19:03:00';
        $correct_no_in[1]['2012-09-22'] = '19:03:00';
        
        $correct_no_out[1]['2012-09-19'] = '07:53:00';
        
        echo '<pre>';
        //print_r($no_in);
        //print_r($no_out);     

        $this->assertIdentical($no_in, $correct_no_in); 
        $this->assertIdentical($no_out, $correct_no_out);   
        //exit;
    }
}    

class TestDTRRawFileConverter extends UnitTestCase {        
    function testDTRInstance8_with_conflicting_out() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance8.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['in']['2012-10-18']['07:15:00'] = '2012-10-18';
        $raw_timesheet[1]['out']['2012-10-18']['20:03:00'] = '2012-10-18';
        $raw_timesheet[1]['out']['2012-10-19']['05:11:00'] = '2012-10-19';      
        $raw_timesheet[1]['in']['2012-10-19']['07:16:00'] = '2012-10-19';   
        $raw_timesheet[1]['out']['2012-10-19']['20:04:00'] = '2012-10-19';      
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-18'] = array('in' => '07:15:00 2012-10-18', 'out' => '20:03:00 2012-10-18');
        $correct_answer[1]['2012-10-19'] = array('in' => '07:16:00 2012-10-19', 'out' => '20:04:00 2012-10-19');
        
        echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
        //exit;
    }   
    
    function testDTRInstance7_sudden_change_of_schedule() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance7.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['in']['2012-10-19']['19:27:00'] = '2012-10-19';
        $raw_timesheet[1]['out']['2012-10-20']['08:00:00'] = '2012-10-20';      
        $raw_timesheet[1]['in']['2012-10-20']['08:00:00'] = '2012-10-20';   
        $raw_timesheet[1]['out']['2012-10-20']['17:16:00'] = '2012-10-20';      
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-19'] = array('in' => '19:27:00 2012-10-19', 'out' => '08:00:00 2012-10-20');
        $correct_answer[1]['2012-10-20'] = array('in' => '08:00:00 2012-10-20', 'out' => '17:16:00 2012-10-20');
        
        echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
        //exit;
    }       
    
    function testDTRInstance6() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance6.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['in']['2012-10-21']['06:47:00'] = '2012-10-21';
        $raw_timesheet[1]['in']['2012-10-21']['11:18:00'] = '2012-10-21';       
        $raw_timesheet[1]['out']['2012-10-21']['11:18:00'] = '2012-10-21';      
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-21'] = array('in' => '06:47:00 2012-10-21', 'out' => '11:18:00 2012-10-21');
        
        echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
        //exit;
    }   
            
    function testDTRInstance5() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance5.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['in']['2012-10-18']['08:04:00'] = '2012-10-18';
        $raw_timesheet[1]['in']['2012-10-18']['19:42:00'] = '2012-10-18';       
        $raw_timesheet[1]['out']['2012-10-19']['08:11:00'] = '2012-10-19';      
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-18'] = array('in' => '19:42:00 2012-10-18', 'out' => '08:11:00 2012-10-19');
        
        echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
        //exit;
    }
    
    function testDTRInstance4() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance4.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['in']['2012-10-20']['18:47:00'] = '2012-10-20';
        $raw_timesheet[1]['out']['2012-10-21']['06:15:00'] = '2012-10-21';      
        $raw_timesheet[1]['in']['2012-10-21']['18:49:00'] = '2012-10-21';
        $raw_timesheet[1]['out']['2012-10-22']['05:34:00'] = '2012-10-22';
        $raw_timesheet[1]['in']['2012-10-22']['18:48:00'] = '2012-10-22';
        $raw_timesheet[1]['out']['2012-10-22']['19:55:00'] = '2012-10-22';          
        
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-20'] = array('in' => '18:47:00 2012-10-20', 'out' => '06:15:00 2012-10-21');
        $correct_answer[1]['2012-10-21'] = array('in' => '18:49:00 2012-10-21', 'out' => '05:34:00 2012-10-22');
        $correct_answer[1]['2012-10-22'] = array('in' => '18:48:00 2012-10-22', 'out' => '19:55:00 2012-10-22');
        
        echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
    }       
        
    function testDTRInstance3() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance3.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['out']['2012-10-16']['07:39:00'] = '2012-10-16';
        $raw_timesheet[1]['in']['2012-10-16']['19:23:00'] = '2012-10-16';       
        $raw_timesheet[1]['out']['2012-10-17']['07:46:00'] = '2012-10-17';
        $raw_timesheet[1]['in']['2012-10-17']['19:14:00'] = '2012-10-17';
        $raw_timesheet[1]['out']['2012-10-17']['20:17:00'] = '2012-10-17';          
        
        //echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-16'] = array('in' => '19:23:00 2012-10-16', 'out' => '07:46:00 2012-10-17');
        $correct_answer[1]['2012-10-17'] = array('in' => '19:14:00 2012-10-17', 'out' => '20:17:00 2012-10-17');
        
        //echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
    }
        
    function testDTRInstance2() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance2.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['in']['2012-10-19']['18:38:00'] = '2012-10-19';
        $raw_timesheet[1]['out']['2012-10-20']['07:03:00'] = '2012-10-20';      
        $raw_timesheet[1]['in']['2012-10-20']['18:42:00'] = '2012-10-20';
        $raw_timesheet[1]['out']['2012-10-21']['07:13:00'] = '2012-10-21';
        $raw_timesheet[1]['in']['2012-10-21']['18:37:00'] = '2012-10-21';
        $raw_timesheet[1]['out']['2012-10-21']['19:24:00'] = '2012-10-21';              
        
        //echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-19'] = array('in' => '18:38:00 2012-10-19', 'out' => '07:03:00 2012-10-20');
        $correct_answer[1]['2012-10-20'] = array('in' => '18:42:00 2012-10-20', 'out' => '07:13:00 2012-10-21');
        $correct_answer[1]['2012-10-21'] = array('in' => '18:37:00 2012-10-21', 'out' => '19:24:00 2012-10-21');
        
        //echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
        //exit;
    }
        
    function testDTRInstance1() {
        
        $file = BASE_PATH . 'timesheet/dtr_instance1.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        //$raw_timesheet = $time->convert();
        
        $raw_timesheet[1]['in']['2012-10-19']['19:14:00'] = '2012-10-19';
        $raw_timesheet[1]['out']['2012-10-20']['08:00:00'] = '2012-10-20';      
        $raw_timesheet[1]['in']['2012-10-20']['08:00:00'] = '2012-10-20';
        $raw_timesheet[1]['out']['2012-10-20']['17:17:00'] = '2012-10-20';              
        
        //echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-10-19'] = array('in' => '19:14:00 2012-10-19', 'out' => '08:00:00 2012-10-20');
        $correct_answer[1]['2012-10-20'] = array('in' => '08:00:00 2012-10-20', 'out' => '17:17:00 2012-10-20');
        
        //echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
        //exit;
    }
        
    function testConverter() {
        
        $file = BASE_PATH . 'timesheet/dtr.xlsx';
        $time = new Timesheet_Raw_Converter_IM($file);
        $raw_timesheet = $time->convert();      
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[469]['2012-09-16'] = array('in' => '17:01:00 2012-09-16', 'out' => '23:31:00 2012-09-16');
        $correct_answer[469]['2012-09-17'] = array('in' => '07:53:00 2012-09-17', 'out' => '18:29:00 2012-09-17');
        $correct_answer[469]['2012-09-18'] = array('in' => '07:46:00 2012-09-18', 'out' => '19:03:00 2012-09-18');              
        $correct_answer[469]['2012-09-19'] = array('in' => '07:49:00 2012-09-19', 'out' => '19:12:00 2012-09-19');
        
        //echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         
        
        //$this->assertIdentical($answer, $correct_answer); 
    }
}            

class TestTimesheet extends UnitTestCase {
    
    function testTimesheetSuperHabaNgPasok() {      
        $raw_timesheet[1]['in']['2012-11-16']['07:43:00'] = '2012-11-16';
        $raw_timesheet[1]['out']['2012-11-17']['00:01:00'] = '2012-11-17';
        $raw_timesheet[1]['in']['2012-11-17']['07:37:00'] = '2012-11-17';       
        $raw_timesheet[1]['out']['2012-11-17']['20:07:00'] = '2012-11-17';      
        
        //echo Tools::computeHoursWorked('07:43:00', '00:01:00');
                            
        echo '<pre>';
        //print_r($raw_timesheet);
        
        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $answer = $tr->filter();
        
        $correct_answer[1]['2012-11-16'] = array('in' => '07:43:00 2012-11-16', 'out' => '00:01:00 2012-11-17');
        $correct_answer[1]['2012-11-17'] = array('in' => '07:37:00 2012-11-17', 'out' => '20:07:00 2012-11-17');
        
        echo '<pre>';
        //print_r($answer);
        //print_r($correct_answer);         

        $this->assertIdentical($answer, $correct_answer);   
        //exit;
    }   
    
    function testTimesheetWithMultipleUserShuffled() {
        $timesheet[1]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['13:10:42'] = '2012-07-27';
        $timesheet[2]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['13:10:42'] = '2012-07-27';
        $timesheet[2]['out']['2012-07-27']['20:15:42'] = '2012-07-27';
        $timesheet[1]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[1]['out']['2012-07-27']['20:15:42'] = '2012-07-27';  
        $timesheet[2]['in']['2012-07-28']['06:10:42'] = '2012-07-28';       
        $timesheet[2]['out']['2012-07-28']['19:10:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[1]['out']['2012-07-28']['19:10:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-08-05']['06:05:42'] = '2012-08-05';                               
        $timesheet[1]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[1]['out']['2012-08-05']['18:05:42'] = '2012-08-05';  
        $timesheet[2]['in']['2012-08-05']['06:05:42'] = '2012-08-05';                               
        $timesheet[2]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[2]['out']['2012-08-05']['18:05:42'] = '2012-08-05';                                                          
        
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[1]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[1]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[1]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        $array1[2]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[2]['2012-07-28'] = array('in' => '06:10:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[2]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        
        echo '<pre>';
        //print_r($arr);
        //print_r($timesheet);
        //print_r($array1);
        //print_r($array2); 
        
        $this->assertIdentical($array1, $array2);   
    }   
    
    function testTimesheetWithMultipleUsersGroupByTypeAndShuffled() {
        $timesheet[1]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['13:10:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['13:10:42'] = '2012-07-27';       
        $timesheet[1]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[2]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[2]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-08-05']['06:05:42'] = '2012-08-05';
        $timesheet[2]['in']['2012-08-05']['06:05:42'] = '2012-08-05';
                        
        $timesheet[1]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[2]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[1]['out']['2012-07-27']['20:15:42'] = '2012-07-27';
        $timesheet[2]['out']['2012-07-27']['20:15:42'] = '2012-07-27';      
        $timesheet[1]['out']['2012-07-28']['19:10:42'] = '2012-07-28';  
        $timesheet[2]['out']['2012-07-28']['19:10:42'] = '2012-07-28';  
        $timesheet[1]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[2]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[1]['out']['2012-08-05']['18:05:42'] = '2012-08-05';              
        $timesheet[2]['out']['2012-08-05']['18:05:42'] = '2012-08-05';
    
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[1]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[1]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[1]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        $array1[2]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[2]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[2]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        
        echo '<pre>';
        //print_r($arr);
        //print_r($timesheet);
        //print_r($array1);
        //print_r($array2); 
        
        $this->assertIdentical($array1, $array2);   
    }   
    
    function testTimesheetWithMultipleUserGroupByType() {
        $timesheet[1]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['13:10:42'] = '2012-07-27';       
        $timesheet[1]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-08-05']['06:05:42'] = '2012-08-05';
        $timesheet[2]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['13:10:42'] = '2012-07-27';       
        $timesheet[2]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[2]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[2]['in']['2012-08-05']['06:05:42'] = '2012-08-05';
                        
        $timesheet[1]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[1]['out']['2012-07-27']['20:15:42'] = '2012-07-27';          
        $timesheet[1]['out']['2012-07-28']['19:10:42'] = '2012-07-28';      
        $timesheet[1]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[1]['out']['2012-08-05']['18:05:42'] = '2012-08-05';              
        $timesheet[2]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[2]['out']['2012-07-27']['20:15:42'] = '2012-07-27';          
        $timesheet[2]['out']['2012-07-28']['19:10:42'] = '2012-07-28';      
        $timesheet[2]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[2]['out']['2012-08-05']['18:05:42'] = '2012-08-05';                                                          
        
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[1]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[1]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[1]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        $array1[2]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[2]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[2]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        
        echo '<pre>';
        //print_r($arr);
        //print_r($timesheet);
        //print_r($array1);
        //print_r($array2); 
        
        $this->assertIdentical($array1, $array2);   
    }       
    
    function testTimesheetWithMultipleUser() {
        $timesheet[1]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['13:10:42'] = '2012-07-27';       
        $timesheet[1]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-08-05']['06:05:42'] = '2012-08-05';       
        $timesheet[1]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[1]['out']['2012-07-27']['20:15:42'] = '2012-07-27';          
        $timesheet[1]['out']['2012-07-28']['19:10:42'] = '2012-07-28';      
        $timesheet[1]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[1]['out']['2012-08-05']['18:05:42'] = '2012-08-05';      
        $timesheet[2]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[2]['in']['2012-07-27']['13:10:42'] = '2012-07-27';       
        $timesheet[2]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[2]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[2]['in']['2012-08-05']['06:05:42'] = '2012-08-05';       
        $timesheet[2]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[2]['out']['2012-07-27']['20:15:42'] = '2012-07-27';          
        $timesheet[2]['out']['2012-07-28']['19:10:42'] = '2012-07-28';      
        $timesheet[2]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[2]['out']['2012-08-05']['18:05:42'] = '2012-08-05';                                                          
        
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[1]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[1]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[1]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        $array1[2]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[2]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[2]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');        
        
        echo '<pre>';
        //print_r($arr);
        //print_r($timesheet);
        //print_r($array1);
        //print_r($array2); 
        
        $this->assertIdentical($array1, $array2);   
    }   
    
    function testTimesheetWithGroupByTypeAndJumpDate() {
        $timesheet[1]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['13:10:42'] = '2012-07-27';       
        $timesheet[1]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
        $timesheet[1]['in']['2012-08-05']['06:05:42'] = '2012-08-05';
        
        $timesheet[1]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[1]['out']['2012-07-27']['20:15:42'] = '2012-07-27';          
        $timesheet[1]['out']['2012-07-28']['19:10:42'] = '2012-07-28';      
        $timesheet[1]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[1]['out']['2012-08-05']['18:05:42'] = '2012-08-05';                                              
        
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[1]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[1]['2012-07-28'] = array('in' => '06:05:42 2012-07-28', 'out' => '19:10:42 2012-07-28');
        $array1[1]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');
        
        echo '<pre>';
        //print_r($arr);
        //print_r($timesheet);
        //print_r($array1);
        //print_r($array2); 
        
        $this->assertIdentical($array1, $array2);   
    }
        
    function testTimesheetWithNoOutValues() {
        $timesheet[1]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['13:10:42'] = '2012-07-27';
        $timesheet[1]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
        $timesheet[1]['out']['2012-07-27']['20:15:42'] = '2012-07-27';  
        
        $timesheet[1]['in']['2012-08-02']['06:05:42'] = '2012-08-02';
        $timesheet[1]['in']['2012-08-03']['06:05:42'] = '2012-08-03';
        
        $timesheet[1]['in']['2012-08-05']['06:05:42'] = '2012-08-05';
        $timesheet[1]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
        $timesheet[1]['out']['2012-08-05']['18:05:42'] = '2012-08-05';                                              
        
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[1]['2012-07-27'] = array('in' => '06:05:42 2012-07-27', 'out' => '20:15:42 2012-07-27');
        $array1[1]['2012-08-05'] = array('in' => '06:05:42 2012-08-05', 'out' => '18:05:42 2012-08-05');
        
        echo '<pre>';
        //print_r($arr);
        //print_r($timesheet);
        //print_r($array1);
        //print_r($array2); 
        
        $this->assertIdentical($array1, $array2);   
    }
    
    function testTimesheetWithGroupValues() {
        
        $timesheet[1]['in']['2012-07-27']['20:05:42'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-27']['22:10:01'] = '2012-07-27';
        $timesheet[1]['in']['2012-07-28']['01:15:02'] = '2012-07-28';
        $timesheet[1]['in']['2012-07-28']['03:30:03'] = '2012-07-28';
        $timesheet[1]['in']['2012-07-29']['20:30:03'] = '2012-07-29';
        $timesheet[1]['in']['2012-07-29']['22:34:03'] = '2012-07-29';   
                
        $timesheet[1]['out']['2012-07-28']['06:37:03'] = '2012-07-28';  
        $timesheet[1]['out']['2012-07-28']['06:40:03'] = '2012-07-28';  
        $timesheet[1]['out']['2012-07-30']['05:40:03'] = '2012-07-30';
        $timesheet[1]['out']['2012-07-30']['05:55:03'] = '2012-07-30';                                                      
        
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[1]['2012-07-27'] = array('in' => '20:05:42 2012-07-27', 'out' => '06:40:03 2012-07-28');
        $array1[1]['2012-07-29'] = array('in' => '20:30:03 2012-07-29', 'out' => '05:55:03 2012-07-30');
        
        echo '<pre>';
        //print_r($arr);
        //print_r($timesheet);
        //print_r($array1);     
        
        $this->assertIdentical($array1, $array2);   
    }
    
    function testTimesheetWithNightshift() {
        
        $timesheet[0017]['in']['2012-09-17']['19:53:00'] = '2012-09-17';
        $timesheet[0017]['out']['2012-09-18']['08:24:00'] = '2012-09-18';       
        $timesheet[0017]['in']['2012-09-18']['19:51:00'] = '2012-09-18';
        $timesheet[0017]['out']['2012-09-19']['08:28:00'] = '2012-09-19';       
        $timesheet[0017]['in']['2012-09-19']['19:55:00'] = '2012-09-19';
        $timesheet[0017]['out']['2012-09-20']['07:53:00'] = '2012-09-20';               
        $timesheet[0017]['in']['2012-09-20']['19:49:00'] = '2012-09-20';
        $timesheet[0017]['out']['2012-09-21']['06:08:00'] = '2012-09-21';       
        $timesheet[0017]['in']['2012-09-21']['21:18:00'] = '2012-09-21';
        $timesheet[0017]['in']['2012-09-21']['22:18:00'] = '2012-09-21';
        $timesheet[0017]['out']['2012-09-22']['05:10:00'] = '2012-09-22';
        $timesheet[0017]['out']['2012-09-22']['07:10:00'] = '2012-09-22';
        $timesheet[0017]['in']['2012-09-24']['19:52:00'] = '2012-09-24';            
                                                                    
        
        $tr = new G_Timesheet_Raw_Filter($timesheet);
        $array2 = $tr->filter();
        
        $array1[0017]['2012-09-17'] = array('in' => '19:53:00 2012-09-17', 'out' => '08:24:00 2012-09-18');
        $array1[0017]['2012-09-18'] = array('in' => '19:51:00 2012-09-18', 'out' => '08:28:00 2012-09-19');
        $array1[0017]['2012-09-19'] = array('in' => '19:55:00 2012-09-19', 'out' => '07:53:00 2012-09-20');             
        $array1[0017]['2012-09-20'] = array('in' => '19:49:00 2012-09-20', 'out' => '06:08:00 2012-09-21');     
        $array1[0017]['2012-09-21'] = array('in' => '21:18:00 2012-09-21', 'out' => '07:10:00 2012-09-22');     
        
        echo '<pre>';
        //print_r($arr);        
        //print_r($array1);
        //print_r($array2);
        //print_r($timesheet);  
        $this->assertIdentical($array1, $array2);   
    }       
}
