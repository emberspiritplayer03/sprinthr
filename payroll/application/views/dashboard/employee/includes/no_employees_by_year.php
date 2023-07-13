<?php
$strXML.="<chart palette='2' caption='Total Employees ' xAxisName='Month' yAxisName='Total Employees' showValues='1' decimals='0' formatNumberScale='0' useRoundEdges='1'>";
foreach($total_employee as $key=>$value) {
	$strXML.="<set label='".date("M", mktime(0, 0, 0, $value['month'], 10))."' value='".$value['total_employee']."' />";
} 
$strXML.="</chart>";
   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   //echo renderChartHTML(MAIN_FOLDER . "chart/Column2D.swf",MAIN_FOLDER."chart/data/Column2D.xml", "", "Monthly",450, 300, false);
    echo renderChartHTML(MAIN_FOLDER . "chart/Column2D.swf","",$strXML, "Monthly",450, 300, false);

?>