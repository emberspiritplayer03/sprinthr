 <?php  
	$strXML2 = "<chart palette='2' caption='Headcount' xAxisName='Department' yAxisName='Total' showValues='1' decimals='0' formatNumberScale='0' chartRightMargin='30'>";
foreach($headcount_by_department as $key=>$value)  {
	$strXML2.="<set label='".$value['department']."' value='".$value['total_employee']."' color='ccdf4r'/>";
}
	$strXML2.="</chart>";

//Create the chart - Column 3D Chart with data from strXML variable using dataXML method
echo renderChartHTML(MAIN_FOLDER . "chart/Bar2D.swf", "", $strXML2, "Monthly",450, 300, false);
?>