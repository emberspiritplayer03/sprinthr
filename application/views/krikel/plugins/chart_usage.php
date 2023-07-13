<table width="100%" border="0" cellpadding="3" cellspacing="4">
  <tr>
    <td width="60%"><strong>Usage:</strong><br />
Controller: Loader::appLibrary('FusionCharts');<br />
<br />
Views: <br />
<strong>Using strURL:</strong><br />
echo renderChartHTML(BASE_FOLDER . &quot;chart/StackedBar3D.swf&quot;, BASE_FOLDER. &quot;/chart/data/StBar3D.xml&quot;, '', &quot;Monthly&quot;,500,300, false);<br />
<br />
<strong>Using strXML:</strong><br />
<br />
<p> &lt;?php <br />
  <br />
  $strXML  = &quot;&quot;;<br />
  $strXML .= &quot;&lt;chart caption='Applicant Summary Graph' xAxisName='' yAxisName='' showValues='1' formatNumberScale='0' showBorder='1'&gt;&quot;;<br />
  $strXML .= &quot;&lt;set label='Total Applicants' value='3'  /&gt;&quot;;<br />
  $strXML .= &quot;&lt;set label='No of Pending Applicants' value='5'  /&gt;&quot;;<br />
  $strXML .= &quot;&lt;set label='No of Applicants Passed' value='56' /&gt;&quot;;<br />
  $strXML .= &quot;&lt;set label='No of Applicants Failed' value='7' /&gt;&quot;;</p>
<p> $strXML .= &quot;&lt;/chart&gt;&quot;;</p>
<p> //Create the chart - Column 3D Chart with data from strXML variable using dataXML method<br />
  <strong>echo renderChartHTML(BASE_FOLDER . &quot;chart/Column3D.swf&quot;, &quot;&quot;, $strXML, &quot;Monthly&quot;,500, 300, false);</strong></p>
<p>?&gt;</p>
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" width="19%">Parameter</td>
    <td valign="top" width="81%">Description</td>
  </tr>
  <tr>
    <td valign="top">chartSWF</td>
    <td valign="top">SWF File Name (and Path) of the   chart which you intend to plot. Here, we are plotting a Column 3D chart.   So, we've specified it as ../../FusionCharts/Column3D.swf</td>
  </tr>
  <tr>
    <td valign="top">strURL</td>
    <td valign="top">If you intend to use dataURL method for the chart, pass the URL as this parameter. Else, set it to &quot;&quot; (in case of dataXML method). In this case, we're using Data.xml file, so we specify Data/Data.xml</td>
  </tr>
  <tr>
    <td valign="top">strXML</td>
    <td valign="top">If you intend to use dataXML method for this chart, pass the XML data as this parameter. Else, set it to &quot;&quot; (in case of dataURL method). Since we're using dataURL method, we specify this parameter as &quot;&quot;.</td>
  </tr>
  <tr>
    <td valign="top">chartId</td>
    <td valign="top"> Id for the chart, using which it will be recognized in the HTML page. <strong>Each chart on the page needs to have a unique Id.</strong></td>
  </tr>
  <tr>
    <td valign="top">chartWidth</td>
    <td valign="top">Intended width for the chart (in pixels)</td>
  </tr>
  <tr>
    <td valign="top">chartHeight</td>
    <td valign="top">Intended height for the chart (in pixels)</td>
  </tr>
  <tr>
    <td valign="top">debugMode</td>
    <td valign="top">Whether to start the chart in debug mode. Please see Debugging your Charts section for more details on Debug Mode. </td>
  </tr>
</table>
<p>&nbsp;</p></td>
  </tr>
  </table>
<p>
<p>