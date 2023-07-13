<table width="100%" border="0">
 
  <tr>
    <td width="60%"><strong>Column3D(Column3D.swf)</strong> <br />
    <?php 
   
      $strXML  = "";
   $strXML .= "<chart caption='Applicant Summary Graph' xAxisName='' yAxisName='' showValues='1' formatNumberScale='0' showBorder='1'>";
   $strXML .= "<set label='Total Applicants' value='3'  />";
   $strXML .= "<set label='No of Pending Applicants' value='5'  />";
   $strXML .= "<set label='No of Applicants Passed' value='56' />";
   $strXML .= "<set label='No of Applicants Failed' value='7' />";

   $strXML .= "</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/Column3D.swf", "", $strXML, "Monthly",500, 300, false);

?></td>
    <td width="40%"><img src="<?php echo BASE_FOLDER;  ?>themes/krikel/images/BtnViewXML.gif" /></td>
  </tr>
  <tr>
    <td><strong>Column2D (Column2D.swf)</strong> <br />
    <?php 
   
      $strXML  = "";
   $strXML .= "<chart caption='Applicant Summary Graph' xAxisName='' yAxisName='' showValues='1' formatNumberScale='0' showBorder='1'>";
   $strXML .= "<set label='Total Applicants' value='3'  />";
   $strXML .= "<set label='No of Pending Applicants' value='5'  />";
   $strXML .= "<set label='No of Applicants Passed' value='56' />";
   $strXML .= "<set label='No of Applicants Failed' value='7' />";

   $strXML .= "</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/Column2D.swf", BASE_FOLDER."chart/data/Column2D.xml", "", "Monthly",500, 300, false);

?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Pie 3d (Pie3D.swf)</strong> <br />
       <?php 
   
      $strXML  = "";
   $strXML .= "<chart caption='Weight (Lbs) Graph' xAxisName='' yAxisName='' showValues='1' formatNumberScale='0' showBorder='1'>";
   $strXML .= "<set label='Jayson' value='130'  />";
   $strXML .= "<set label='Bryann' value='116'  />";
   $strXML .= "<set label='Bryan' value='145' />";
   $strXML .= "<set label='Harney' value='160' />";
      $strXML .= "<set label='Tristan' value='130'  />";
   $strXML .= "<set label='Ton Ton' value='145'  />";
   $strXML .= "<set label='Aljon' value='120' />";
   $strXML .= "<set label='Monte' value='100' />";
   $strXML .= "<set label='Chev' value='116' />";

   $strXML .= "</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/Pie3D.swf", "", $strXML, "Monthly",500, 300, false);

?>
    
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <strong>Pie 2d (Pie2D.swf)</strong> <br />
      <?php 
   
      $strXML  = "";
   $strXML .= "<chart caption='Weight (Lbs) Graph' xAxisName='' yAxisName='' showValues='1' formatNumberScale='0' showBorder='1'>";
   $strXML .= "<set label='Jayson' value='130'  />";
   $strXML .= "<set label='Bryann' value='116'  />";
   $strXML .= "<set label='Bryan' value='145' />";
   $strXML .= "<set label='Harney' value='160' />";
      $strXML .= "<set label='Tristan' value='130'  />";
   $strXML .= "<set label='Ton Ton' value='145'  />";
   $strXML .= "<set label='Aljon' value='120' />";
   $strXML .= "<set label='Monte' value='100' />";
   $strXML .= "<set label='Chev' value='116' />";

   $strXML .= "</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/Pie2D.swf", "", $strXML, "Monthly",500, 300, false);

?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Line 2d (line.swf)</strong> <br />
    <?php 
   
      $strXML  = "";
   $strXML .= "<chart caption='Weight (Lbs) Graph' xAxisName='' yAxisName='' showValues='1' formatNumberScale='0' showBorder='1'>";
   $strXML .= "<set label='Jayson' value='130'  />";
   $strXML .= "<set label='Bryann' value='116'  />";
   $strXML .= "<set label='Bryan' value='145' />";
   $strXML .= "<set label='Harney' value='160' />";
      $strXML .= "<set label='Tristan' value='130'  />";
   $strXML .= "<set label='Ton Ton' value='145'  />";
   $strXML .= "<set label='Aljon' value='120' />";
   $strXML .= "<set label='Monte' value='100' />";
   $strXML .= "<set label='Chev' value='116' />";

   $strXML .= "</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/line.swf", "", $strXML, "Monthly",600, 300, false);
?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Bar 2d (bar2d.swf)</strong> <br />
    <?php 
   
      $strXML  = "";
   $strXML .= "<chart caption='Weight (Lbs) Graph' xAxisName='' yAxisName='' showValues='1' formatNumberScale='0' showBorder='1'>";
   $strXML .= "<set label='Jayson' value='130'  />";
   $strXML .= "<set label='Bryann' value='116'  />";
   $strXML .= "<set label='Bryan' value='145' />";
   $strXML .= "<set label='Harney' value='160' />";
      $strXML .= "<set label='Tristan' value='130'  />";
   $strXML .= "<set label='Ton Ton' value='145'  />";
   $strXML .= "<set label='Aljon' value='120' />";
   $strXML .= "<set label='Monte' value='100' />";
   $strXML .= "<set label='Chev' value='116' />";

   $strXML .= "</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/bar2d.swf", "", $strXML, "Monthly",600, 300, false);
?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <strong>Area 2d (area2d.swf)</strong> <br />
    <?php 
   
      $strXML  = "";
  	$strXML .= "<chart palette='2' caption='Monthly Sales Summary' subcaption='For the year 2006' xAxisName='Month' yAxisMinValue='15000' yAxisName='Sales' numberPrefix='$' showValues='0'>
			<set label='Jan' value='17400'/>
			<set label='Feb' value='18100'/>
			<set label='Mar' value='21800'/>
			<set label='Apr' value='23800'/>
			<set label='May' value='29600'/>
			<set label='Jun' value='27600'/>
			<set label='Jul' value='31800'/>
			<set label='Aug' value='39700'/>
			<set label='Sep' value='37800'/>
			<set label='Oct' value='21900'/>
			<set label='Nov' value='32900'/>
			<set label='Dec' value='39800'/>
	<styles>
   <definition>
   <style name='Anim1' type='animation' param='_xscale' start='0' duration='1'/>
		<style name='Anim2' type='animation' param='_alpha' start='0' duration='1'/>
		<style name='DataShadow' type='Shadow' alpha='20'/>
	</definition> 
	<application>
		<apply toObject='DIVLINES' styles='Anim1'/><apply toObject='HGRID' styles='Anim2'/>
		<apply toObject='DATALABELS' styles='DataShadow,Anim2'/>
	</application>
	</styles> ";

   $strXML .= "</chart>";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/area2d.swf", "", $strXML, "Monthly",600, 300, false);
?>
    
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <strong>Doughnut 2D (Doughnut2D.swf)</strong> <br />
    <?php 
	 $strXML = "";
	 $strXML .= "<chart palette='2'>
	 				<set label='France' value='17'/>
					<set label='India' value='12'/>
					<set label='Brazil' value='18'/>
					<set label='USA' value='8'/>
					<set label='Australia' value='10'/>
					<set label='Japan' value='7' isSliced='1'/>
					<set label='England' value='5' isSliced='1'/>
					<set label='Nigeria' value='12' isSliced='1'/>
					<set label='Italy' value='8'/>
					<set label='China' value='10'/>
					<set label='Canada' value='19'/>
					<set label='Germany' value='15'/>
					</chart>";
					
		  echo renderChartHTML(BASE_FOLDER . "chart/Doughnut2D.swf", "", $strXML, "Monthly",600, 300, false);
	?>
    
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
<p>