<table width="100%" border="0" cellpadding="3" cellspacing="2">
  <tr>
    <td width="60%"><strong>Multi Series Column 2d (MSColumn2D.swf)</strong> <br />
    <?php 
   
      $strXML  = "";
	 $strXML  .= "<chart palette='2' caption='Country Comparison' shownames='1' showvalues='0' decimals='0' numberPrefix='$' useRoundEdges='1' legendBorderAlpha='0'>
	 		<categories>
				<category label='Austria'/>
				<category label='Brazil'/>
				<category label='France'/>
				<category label='Germany'/>
				<category label='USA'/>
			</categories>
			<dataset seriesName='1996' color='AFD8F8' showValues='0'>
				<set value='25601.34'/>
				<set value='20148.82'/>
				<set value='17372.76'/>
				<set value='35407.15'/>
				<set value='38105.68'/>
				</dataset>
			<dataset seriesName='1997' color='F6BD0F' showValues='0'>
				<set value='57401.85'/><set value='41941.19'/>
				<set value='45263.37'/><set value='117320.16'/><set value='114845.27'/>
			</dataset>
			<dataset seriesName='1998' color='8BBA00' showValues='0'>
				<set value='45000.65'/>
				<set value='44835.76'/>
				<set value='18722.18'/>
				<set value='77557.31'/>
				<set value='92633.68'/>
				</dataset>
			</chart> ";

   //Create the chart - Column 3D Chart with data from strXML variable using dataXML method
   echo renderChartHTML(BASE_FOLDER . "chart/MSColumn2D.swf", "", $strXML, "Monthly",500, 300, false);

?></td>
    <td width="40%">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Multi Series Column 3d (MSColumn3D.swf)</strong> <br />
    <?php 
	 $strXML  = "";
	 $strXML .= "<chart caption='Country Comparison' shownames='1' showvalues='0' decimals='0' numberPrefix='$'>
	<categories><category label='Austria'/>
	<category label='Brazil'/>
	<category label='France'/><category label='Germany'/>
	<category label='USA'/>
	</categories><dataset seriesName='1996' color='AFD8F8' showValues='0'>
	<set value='25601.34'/><set value='20148.82'/>
	<set value='17372.76'/>
	<set value='35407.15'/><set value='38105.68'/>
	</dataset><dataset seriesName='1997' color='F6BD0F' showValues='0'>
	<set value='57401.85'/><set value='41941.19'/><set value='45263.37'/>
	<set value='117320.16'/><set value='114845.27'/>
	</dataset>
	<dataset seriesName='1998' color='8BBA00' showValues='0'>
	<set value='45000.65'/><set value='44835.76'/>
	<set value='18722.18'/><set value='77557.31'/>
	<set value='92633.68'/></dataset>
	</chart>";
	 echo renderChartHTML(BASE_FOLDER . "chart/MSColumn3D.swf", "", $strXML, "Monthly",500, 300, false);
	?>
    
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Multi Series Line 2d (MSLine.swf)<br />
    </strong>
    <?php 
	$strXML  = "";
	$strXML .= "<chart caption='Daily Visits' subcaption='(from 8/6/2006 to 8/12/2006)' lineThickness='1' showValues='0' formatNumberScale='0' 
	anchorRadius='2' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' 
	alternateHGridColor='CC3300' shadowAlpha='40' labelStep='2' numvdivlines='5' chartRightMargin='35' bgColor='FFFFFF,CC3300' bgAngle='270' 
	bgAlpha='10,10'>
	<categories>
		<category label='8/6/2006'/><category label='8/7/2006'/>
		<category label='8/8/2006'/><category label='8/9/2006'/>
		<category label='8/10/2006'/><category label='8/11/2006'/>
		<category label='8/12/2006'/></categories>
		<dataset seriesName='Offline Marketing' color='1D8BD1' anchorBorderColor='1D8BD1' anchorBgColor='1D8BD1'>
			<set value='1327'/><set value='1826'/>
			<set value='1699'/><set value='1511'/>
			<set value='1904'/><set value='1957'/>
			<set value='1296'/>
		</dataset>
		<dataset seriesName='Search' color='F1683C' anchorBorderColor='F1683C' anchorBgColor='F1683C'>
			<set value='2042'/><set value='3210'/><set value='2994'/>
			<set value='3115'/><set value='2844'/><set value='3576'/>
			<set value='1862'/></dataset>
		<dataset seriesName='Paid Search' color='2AD62A' anchorBorderColor='2AD62A' anchorBgColor='2AD62A'>
			<set value='850'/><set value='1010'/>
			<set value='1116'/><set value='1234'/>
			<set value='1210'/><set value='1054'/>
			<set value='802'/>
		</dataset>
		<dataset seriesName='From Mail' color='DBDC25' anchorBorderColor='DBDC25' anchorBgColor='DBDC25'>
			<set value='541'/><set value='781'/>
			<set value='920'/><set value='754'/>
			<set value='840'/><set value='893'/>
			<set value='451'/>
		</dataset>
	<styles>
		<definition>
		<style name='CaptionFont' type='font' size='12'/></definition>
	<application>
		<apply toObject='CAPTION' styles='CaptionFont'/>
		<apply toObject='SUBCAPTION' styles='CaptionFont'/>
		</application>
	</styles>
	</chart>";
	 echo renderChartHTML(BASE_FOLDER . "chart/MSLine.swf", "", $strXML, "Monthly",500, 300, false);
	?>
    
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Multi Series Area 2d (MSArea.swf)<br />
    </strong>
    <?php 
	$strXML  = "";
	$strXML .= "<chart bgColor='E9E9E9' outCnvBaseFontColor='666666' caption='Monthly Sales Summary' xAxisName='Month' yAxisName='Sales'
	 numberPrefix='$' showNames='1' showValues='0' plotFillAlpha='50' numVDivLines='10' showAlternateVGridColor='1' AlternateVGridColor='e1f5ff' 
	 divLineColor='e1f5ff' vdivLineColor='e1f5ff' baseFontColor='666666' canvasBorderThickness='1' showPlotBorder='1' plotBorderThickness='0'>
	 <categories>
	 	<category label='Jan'/>
		<category label='Feb'/>
		<category label='Mar'/>
		<category label='Apr'/>
		<category label='May'/>
		<category label='Jun'/>
		<category label='Jul'/>
		<category label='Aug'/>
		<category label='Sep'/>
		<category label='Oct'/>
		<category label='Nov'/>
		<category label='Dec'/>
	</categories>
	<dataset seriesName='2005' color='B1D1DC' plotBorderColor='B1D1DC'>
		<set value='27400'/>
		<set value='29800'/>
		<set value='25800'/>
		<set value='26800'/>
		<set value='29600'/>
		<set value='32600'/>
		<set value='31800'/>
		<set value='36700'/>
		<set value='29700'/>
		<set value='31900'/>
		<set value='32900'/>
		<set value='34800'/>
	</dataset>
	<dataset seriesName='2006' color='C8A1D1' plotBorderColor='C8A1D1'>
		<set/><set/>
		<set value='4500'/>
		<set value='6500'/>
		<set value='7600'/>
		<set value='6800'/>
		<set value='11800'/>
		<set value='19700'/>
		<set value='21700'/>
		<set value='21900'/>
		<set value='22900'/>
		<set value='29800'/>
	</dataset>
	</chart>"; 
	echo renderChartHTML(BASE_FOLDER . "chart/MSArea.swf", "", $strXML, "Monthly",500, 300, false);
	
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Multi Series Bar 2d (MSBar2D.swf)<br />
    </strong>
    <?php 
	$strXML = "";
	$strXML .= "<chart palette='2' caption='Business Results: 2005' yaxisname='Revenue (Millions)' hovercapbg='FFFFFF' toolTipBorder='889E6D'
	 divLineColor='999999' divLineAlpha='80' showShadow='0' canvasBgColor='FEFEFE' canvasBaseColor='FEFEFE' canvasBaseAlpha='50' divLineIsDashed='1'
	  divLineDashLen='1' divLineDashGap='2' numberPrefix='$' numberSuffix='M' chartRightMargin='30' useRoundEdges='1' legendBorderAlpha='0'>
	<categories>
	  	<category label='Hardware'/>
		<category label='Software'/>
		<category label='Service'/>
	</categories>
	<dataset seriesname='Domestic' color='8EAC41'>
		<set value='84'/>
		<set value='207'/>
		<set value='116'/>
	</dataset>
	<dataset seriesname='International' color='607142'>
		<set value='116'/>
		<set value='237'/>
		<set value='83'/>
	</dataset>
	</chart>";
	echo renderChartHTML(BASE_FOLDER . "chart/MSBar2D.swf", "", $strXML, "Monthly",500, 300, false);
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Multi Series Bar 3d (MSBar3D.swf)<br />
    </strong>
   <?php 
   $strXML = "";
   $strXML .= "<chart palette='2' caption='Country Comparison' shownames='1' showvalues='0' decimals='0' numberPrefix='$'>
   <categories>
   	<category label='Austria'/>
	<category label='Brazil'/>
	<category label='France'/>
	<category label='Germany'/>
	<category label='USA'/>
   </categories>
    <dataset seriesName='1996' color='AFD8F8' showValues='0'>
		<set value='25601.34'/>
		<set value='20148.82'/>
		<set value='17372.76'/>
		<set value='35407.15'/>
		<set value='38105.68'/>
	</dataset>
	<dataset seriesName='1997' color='F6BD0F' showValues='0'><br />
		<set value='57401.85'/>
		<set value='41941.19'/>
		<set value='45263.37'/>
		<set value='117320.16'/><set value='114845.27'/>
	</dataset><dataset seriesName='1998' color='8BBA00' showValues='0'>
		<set value='45000.65'/>
		<set value='44835.76'/>
		<set value='18722.18'/>
		<set value='77557.31'/>
		<set value='92633.68'/>
		</dataset>
	</chart>";
   echo renderChartHTML(BASE_FOLDER . "chart/MSBar3D.swf", "", $strXML, "Monthly",500, 500, false);
   ?> 
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
<p>