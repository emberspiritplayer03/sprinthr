<table width="100%" border="0" cellpadding="4" cellspacing="2">
  <tr>
    <td width="60%"><strong>2 Single Y Combination (MSCombi2D.swf)<br />
    </strong>
    <?php 
		echo renderChartHTML(MAIN_FOLDER . "chart/MSCombi2D.swf", MAIN_FOLDER. "/chart/data/Combi2D.xml", "", "Monthly",500,300, false);
	?>
    
    </td>
    <td width="40%">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Column 3D + Line Single Y (MSColumnLine3D.swf)<br />
      <?php 
		echo renderChartHTML(MAIN_FOLDER . "chart/MSColumnLine3D.swf", MAIN_FOLDER. "/chart/data/Col3DLine.xml", "", "Monthly",500,300, false);
	?>
    </strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>2D Dual Y Combination (MSCombiDY2D.swf)</strong>
      <?php 
		echo renderChartHTML(MAIN_FOLDER . "chart/MSCombiDY2D.swf", MAIN_FOLDER. "/chart/data/Combi2DDY.xml", "", "Monthly",500,300, false);
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Column 3D + Line Dual Y (MSColumn3DLineDY.swf)<br />
    </strong>
  <?php 
  	echo renderChartHTML(MAIN_FOLDER . "chart/MSColumn3DLineDY.swf", MAIN_FOLDER. "/chart/data/Col3DLineDY.xml", "", "Monthly",500,300, false);
  ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Stacked Column 3D + Line Dual Y (StackedColumn3DLineDY.swf)<br />
    </strong>
  <?php 
  	echo renderChartHTML(MAIN_FOLDER . "chart/StackedColumn3DLineDY.swf", MAIN_FOLDER. "/chart/data/StCol3DLineDY.xml", "", "Monthly",500,300, false);
  ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Multi-Series Stacked Column 2D + Line Dual Y (MSStackedColumn2DLineDY.swf)<br />
      <?php 
  	echo renderChartHTML(MAIN_FOLDER . "chart/MSStackedColumn2DLineDY.swf", MAIN_FOLDER. "/chart/data/StCol2DLineDY.xml", "", "Monthly",500,300, false);
  ?>
    </strong></td>
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
