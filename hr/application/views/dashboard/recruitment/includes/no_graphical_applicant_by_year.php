<?php 
$year = ($_POST['year']) ? $_POST['year'] : date("Y");
$total = 0;
foreach($total_applicant as $key=>$val) {

	$total_application_submitted += $val['application_submitted'];
	$total_hired 				 = $val['hired'];
	$total_declined 			 = $val['declined'];
	if($val['year']==$year) {
		$month_string[]     = Date::getMonthName($val['month']);
		$no_declined[]      = $total_declined ;
		$no_hired[]         = $total_hired; //$val['total_hired'];
		$no_app_submitted[] = $val['application_submitted'];
		//echo $val['year'] . ' '. Date::getMonthName($val['month'])	.'<br> Total Hired: '. $val['total_hired']. '<br> Total Terminated: ' . $val['total_terminated']. '<br>';	
	}
}

$strXml .= "<chart caption='Year $year' xAxisName='Month' yAxisName='' showValues='1' numberPrefix=''>";

$strXml .= " <categories>";
foreach($month_string as $key=>$m) {
	$strXml .= "      <category label='".$m."' />";	
}
$strXml .= "   </categories>";

$strXml .= "   <dataset seriesName='Hired'>";
foreach($no_hired as $key=>$hired) {
	$hired = ($hired) ? $hired : 0 ;
	$strXml .= "     <set value='".$hired	."' />";	
}
$strXml .= "   </dataset>";

$strXml .= "   <dataset seriesName='Application Submitted'>";
foreach($no_app_submitted as $key=>$submitted) {
	$submitted = ($submitted) ? $submitted : 0 ;
	$strXml .= "     <set value='".$submitted	."' />";	
}
$strXml .= "      <set value='1'/>";
$strXml .= "   </dataset>";

$strXml .= "   <dataset seriesName='Declined'>";
foreach($no_declined as $key=>$declined) {
	$declined = ($declined) ? $declined : 0 ;
	$strXml .= "     <set value='".$declined	."' />";	
}
$strXml .= "      <set value='1'/>";
$strXml .= "   </dataset>";

$strXml .= "   <trendlines>";
$strXml .= "      <line startValue='26000' color='91C728' displayValue='Target' showOnTop='1'/>";
$strXml .= "   </trendlines>";

$strXml .= "</chart> ";


   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   //echo renderChartHTML(MAIN_FOLDER . "chart/Column2D.swf",MAIN_FOLDER."chart/data/Column2D.xml", "", "Monthly",450, 300, false);
    echo renderChartHTML(MAIN_FOLDER . "chart/MSColumn2D.swf","",$strXml, "Monthly",450, 300, false);
?>

