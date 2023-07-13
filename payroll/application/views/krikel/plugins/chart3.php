<table width="100%" border="0">
  <tr>
    <td width="60%"><strong>Stacked Column 2D (StackedColumn2D.swf)</strong><br />
    <?php 
	$strXML = "";
	$strXML .= "<chart palette='2' caption='Product Comparison' shownames='1' showvalues='0' numberPrefix='$' showSum='1' decimals='0'
	 useRoundEdges='1'>
	 <categories>
		 <category label='Product A'/>
		 <category label='Product B'/>
		 <category label='Product C'/>
		 <category label='Product D'/>
		 <category label='Product E'/>
	 </categories>
	 <dataset seriesName='2004' color='AFD8F8' showValues='0'>
		 <set value='25601.34'/>
		 <set value='20148.82'/>
		 <set value='17372.76'/>
		 <set value='35407.15'/>
		 <set value='38105.68'/>
	 </dataset>
	 <dataset seriesName='2005' color='F6BD0F' showValues='0'>
	 	<set value='57401.85'/>
		<set value='41941.19'/>
		<set value='45263.37'/>
		<set value='117320.16'/>
		<set value='114845.27'/>
	</dataset>
	<dataset seriesName='2006' color='8BBA00' showValues='0'>
		<set value='45000.65'/>
		<set value='44835.76'/>
		<set value='18722.18'/>
		<set value='77557.31'/>
		<set value='92633.68'/>
	</dataset>
	</chart>";
	echo renderChartHTML(MAIN_FOLDER . "chart/StackedColumn2D.swf", "", $strXML, "Monthly",500,300, false);
	?>
    </td>
    <td width="40%">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Stack Column 3D (StackedColumn3D.swf)<br />
    </strong>
    <?php 
	$strXML = "";
	$strXML .= "<chart palette='1' caption='Product Comparison' shownames='1' showvalues='0' numberPrefix='$' showSum='1' decimals='0'
	 overlapColumns='0'>
	 <categories>
	 	<category label='Product A'/>
		<category label='Product B'/>
		<category label='Product C'/>
		<category label='Product D'/>
		<category label='Product E'/>
	</categories>
	<dataset seriesName='2004' showValues='0'>
		<set value='25601.34'/>
		<set value='20148.82'/>
		<set value='17372.76'/>
		<set value='35407.15'/>
		<set value='38105.68'/>
	</dataset>
	<dataset seriesName='2005' showValues='0'>
		<set value='57401.85'/>
		<set value='41941.19'/>
		<set value='45263.37'/>
		<set value='117320.16'/>
		<set value='114845.27'/>
	</dataset>
	<dataset seriesName='2006' showValues='0'>
		<set value='45000.65'/>
		<set value='44835.76'/>
		<set value='18722.18'/>
		<set value='77557.31'/>
		<set value='92633.68'/>
	</dataset>
	</chart>";
	echo renderChartHTML(MAIN_FOLDER . "chart/StackedColumn3D.swf", "", $strXML, "Monthly",500,300, false);
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Stack Area 2D (StackedArea2D.swf)<br />
    </strong>
    <?php 
	$strXML = "";
	$strXML .= "<chart bgColor='E9E9E9' outCnvBaseFontColor='666666' caption='Monthly Sales Summary Comparison' xAxisName='Month' 
	yAxisName='Sales' numberPrefix='$' showValues='0' numVDivLines='10' showAlternateVGridColor='1' AlternateVGridColor='e1f5ff' 
	divLineColor='e1f5ff' vdivLineColor='e1f5ff' baseFontColor='666666' toolTipBgColor='F3F3F3' toolTipBorderColor='666666' canvasBorderColor='666666'
	 canvasBorderThickness='1' showPlotBorder='1' plotFillAlpha='80'>
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
	<dataset seriesName='2004' color='B1D1DC' plotBorderColor='B1D1DC'>
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
	<dataset seriesName='2003' color='C8A1D1' plotBorderColor='C8A1D1'>
		<set/>
		<set/>
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
	<trendlines>
		<line startValue='22000' endValue='58000' color='999999' displayValue='Target' dashed='1' thickness='2' dashGap='6' alpha='100' showOnTop='1'/>
	</trendlines>
	<styles>
		<definition>
		<style type='animation' name='TrendAnim' param='_alpha' duration='1' start='0'/>
		</definition>
		<application>
			<apply toObject='TRENDLINES' styles='TrendAnim'/>
			</application>
	</styles>
	</chart>";
	echo renderChartHTML(MAIN_FOLDER . "chart/StackedArea2D.swf", "", $strXML, "Monthly",500,300, false);
	
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Stacked Bar 2D (StackedBar3D.swf)<br />
    </strong>
    <?php 
	$strXML = "";
	$strXML .= "
	<chart palette='2' caption='Product Comparison' shownames='1' showvalues='0'  numberPrefix='$' showSum='1' decimals='0' useRoundEdges='1'>
<categories>
<category label='Product A' />
<category label='Product B' />
<category label='Product C' />
<category label='Product D' />
<category label='Product E' />
</categories>
<dataset seriesName='2004' color='AFD8F8' showValues='0'>
<set value='25601.34' />
<set value='20148.82' />
<set value='17372.76' />
<set value='35407.15' />
<set value='38105.68' />
</dataset>
<dataset seriesName='2005' color='F6BD0F' showValues='0'>
<set value='57401.85' />
<set value='41941.19' />
<set value='45263.37' />
<set value='117320.16' />
<set value='114845.27' />
</dataset>
<dataset seriesName='2006' color='8BBA00' showValues='0'>
<set value='45000.65' />
<set value='44835.76' />
<set value='18722.18' />
<set value='77557.31' />
<set value='92633.68' />
</dataset>
</chart>";
echo renderChartHTML(MAIN_FOLDER . "chart/StackedBar2D.swf", "", $strXML, "Monthly",500,300, false);
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Stacked Bar 3D (StackedBar3D.swf)<br />
<?php 
    echo renderChartHTML(MAIN_FOLDER . "chart/StackedBar3D.swf", MAIN_FOLDER. "/chart/data/StBar3D.xml", '', "Monthly",500,300, false);
	?>
    </strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Multi-Series Stacked (MSStackedColumn2D.swf)<br />
    </strong>
    <?php 
    echo renderChartHTML(MAIN_FOLDER . "chart/MSStackedColumn2D.swf", MAIN_FOLDER. "/chart/data/StMSCol2D.xml", '', "Monthly",500,300, false);
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