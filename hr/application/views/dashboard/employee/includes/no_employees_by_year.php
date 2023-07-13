<?php 

$year = ($_POST['year']) ? $_POST['year'] : date("Y");
$total = 0;
foreach($total_employee as $key=>$val) {

	$total_hired += $val['total_hired'];
	$total_hired -= $val['total_terminated'];
	if($val['year']==$year) {
		$month_string[] = Date::getMonthName($val['month']);
		$no_terminated[] = $val['total_terminated'];
		$no_hired[] = $total_hired; //$val['total_hired'];
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

$strXml .= "   <dataset seriesName='Terminated'>";
foreach($no_terminated as $key=>$terminated) {
	$terminated = ($terminated) ? $terminated : 0 ;
	$strXml .= "     <set value='".$terminated	."' />";	
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
echo "<div align='left' style='font-size:16px;'><b>" . "Total: " . '<span class="label label-info" style="font-size:16px;">&nbsp;&nbsp;' . $total_hired . '&nbsp;&nbsp;</span>' . "</b></div>";
?>

