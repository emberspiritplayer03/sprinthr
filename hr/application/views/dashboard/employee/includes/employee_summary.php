<?php 

$year = ($_POST['year']) ? $_POST['year'] : date("Y");
$total = 0;
foreach($status as $key=>$val) {	
		$month_string[] = Date::getMonthName($val['month']);
		foreach($val as $key_sub=>$sub_val){			
			if($key_sub != 'year' && $key_sub != 'month'){				
				$total_header[$key_sub] += $sub_val; 				
			}
		}
		
}

//echo '<pre>';
//print_r($total_header);

$strXml .= "<chart caption='Year $year' xAxisName='Month' yAxisName='' showValues='1' numberPrefix=''>";

$strXml .= " <categories>";
foreach($month_string as $key=>$m) {
	$strXml .= "      <category label='".$m."' />";	
}
$strXml .= "   </categories>";

//Dataset

/*$strXml .= "   <dataset seriesName='test1'>";
$strXml .= "      <set value='15'/>";
$strXml .= "      <set value='1'/>";
$strXml .= "   </dataset>";
$strXml .= "   <dataset seriesName='test3'>";
$strXml .= "      <set value='25'/>";
$strXml .= "      <set value='33'/>";
$strXml .= "   </dataset>";*/

foreach($total_header as $key=>$value){
	$title = str_replace("_"," ",strtoupper($key));	
	$title = str_replace("TAG","",$title);
	$strXml .= "   <dataset seriesName='" . $title . "'>";
		foreach($status as $key_status=>$val_status){
			foreach($val as $key_status_sub=>$sub_val){	
				if($key == $key_status_sub){
					$chartArr['year']  = $val_status['year'];
					$chartArr['month'] = $val_status['month'];
					$chartArr['total'] = $val_status[$key];
					$strXml .= "     <set value='". $val_status[$key] ."'></set>";	
				}
			}
		}	
	$strXml .= "   </dataset>";
}

$strXml .= "   <trendlines>";
$strXml .= "      <line startValue='26000' color='91C728' displayValue='Target' showOnTop='1'/>";
$strXml .= "   </trendlines>";

$strXml .= "</chart> ";
//


   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   //echo renderChartHTML(MAIN_FOLDER . "chart/Column2D.swf",MAIN_FOLDER."chart/data/Column2D.xml", "", "Monthly",450, 300, false);
 
 
    echo renderChartHTML(MAIN_FOLDER . "chart/MSColumn2D.swf","",$strXml, "Monthly",450, 300, false);

?>

