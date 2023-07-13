 <?php  

$strXML2 = "<chart palette='2' caption='Headcount' xAxisName='Department' yAxisName='Total' showValues='1' decimals='0'  formatNumberScale='0' chartRightMargin='30' yAxisValuesStep='1'>
<set label='Above 60000' value='".$total_salary_by_range[0]['above_60k']."'/>
<set label='50000 - 60000' value='".$total_salary_by_range[0]['between_50k_60k']."'/>
<set label='40000 - 50000' value='".$total_salary_by_range[0]['between_40k_50k']."'/>
<set label='30000 - 40000' value='".$total_salary_by_range[0]['between_30k_40k']."'/>
<set label='20000 - 30000' value='".$total_salary_by_range[0]['between_20k_30k']."'/>
<set label='15000 - 20000' value='".$total_salary_by_range[0]['between_15k_20k']."'/>
<set label='8000 - 15000' value='".$total_salary_by_range[0]['between_8k_15k']."'/>
<set label='3000 - 8000' value='".$total_salary_by_range[0]['between_3k_8k']."'/>
<set label='below 3000' value='".$total_salary_by_range[0]['below_3k']."'/>

</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(MAIN_FOLDER . "chart/Bar2D.swf", "", $strXML2, "Monthly",450, 300, false);
   
   ?>