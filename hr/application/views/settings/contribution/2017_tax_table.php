<style>
#dtTaxTable_filter{display:none;}
ul.inline{list-style:none outside none;margin-left:0;}
ul.inline li{display:inline-block;padding-left:5px;padding-right:5px;}
.tax-table-legend-center{width:100%;margin-top:10px;text-align:center;}
</style>
<script>
$(document).ready(function(){ 
  var oTable = $('#dtTaxTable').dataTable( {
	   "aoColumns": [   		   	
			{sWidth: '10%',sClass:'dt_small_font'},							
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '10%',sClass:'dt_small_font dt_center'}																
	 	],
		"bProcessing":true,
		"bServerSide":false,
		"bAutoWidth": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": false
	});
});
</script>
<br />
<div class="table-container">
<table id="dtTaxTable" class="display">
<thead>
  <tr> 	
    <th valign="top">SEMI-MONTHLY</th>   
    <th valign="top"></th>    
    <th valign="top">1</th>
    <th valign="top">2</th>
    <th valign="top">3</th>   
    <th valign="top">4</th>   
    <th valign="top">5</th>   
    <th valign="top">6</th>   
    <th valign="top">7</th>   
    <th valign="top">8</th>   
  </tr>
</thead>
	<tr>
    	<td valign="center" align="left" style="color:#333">Exemption</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">0.00</td>
        <td valign="center" align="left" style="color:#333">0.00</td>
        <td valign="center" align="left" style="color:#333">20.83</td>
        <td valign="center" align="left" style="color:#333">104.17</td>
        <td valign="center" align="left" style="color:#333">354.17</td>
        <td valign="center" align="left" style="color:#333">937.50</td>
        <td valign="center" align="left" style="color:#333">2,083.33</td>
        <td valign="center" align="left" style="color:#333">5,208.33</td>
    </tr>
    <tr>
    	<td valign="center" align="left" style="color:#333">Status</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">+0% over</td>
        <td valign="center" align="left" style="color:#333">+5% over</td>
        <td valign="center" align="left" style="color:#333">+10% over</td>
        <td valign="center" align="left" style="color:#333">+15% over</td>
        <td valign="center" align="left" style="color:#333">+20% over</td>
        <td valign="center" align="left" style="color:#333">+25% over</td>
        <td valign="center" align="left" style="color:#333">+30% over</td>
        <td valign="center" align="left" style="color:#333">+32% over</td>
    </tr>
    <?php foreach ($monthly as $m){?>
      <tr>      
        <td valign="center" align="left" style="color:#333">
        	<?php echo $m->getStatus(); ?>
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD0(),2,".",","); ?>
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD1(),2,".",","); ?>        
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD2(),2,".",","); ?>	
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD3(),2,".",","); ?>
        </td> 
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD4(),2,".",","); ?>
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD5(),2,".",","); ?>
        </td>    
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD6(),2,".",","); ?>
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD7(),2,".",","); ?>
        </td>    
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($m->getD8(),2,".",","); ?>
        </td>             
      </tr>
    <?php } ?> 
    <tr> 
    	<td class="ui-state-default" valign="left" align="left" style="color:#333">MOTHLY</td>    
        <th class="ui-state-default" valign="left" align="left" style="color:#333"></th>    
        <th class="ui-state-default" valign="left" align="left" style="color:#333">1</th>
        <th class="ui-state-default" valign="left" align="left" style="color:#333">2</th>
        <th class="ui-state-default" valign="left" align="left" style="color:#333">3</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">4</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">5</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">6</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">7</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">8</th>   
    </tr>
    <tr>
    	<td valign="center" align="left" style="color:#333">Exemption</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">0.00</td>
        <td valign="center" align="left" style="color:#333">0.00</td>
        <td valign="center" align="left" style="color:#333">41.67</td>
        <td valign="center" align="left" style="color:#333">208.33</td>
        <td valign="center" align="left" style="color:#333">708.33</td>
        <td valign="center" align="left" style="color:#333">1,875.00</td>
        <td valign="center" align="left" style="color:#333">4,166.67</td>        
        <td valign="center" align="left" style="color:#333">10,416.67</td>
    </tr>
    <tr>
    	<td valign="center" align="left" style="color:#333">Status</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">+0% over</td>
        <td valign="center" align="left" style="color:#333">+5% over</td>
        <td valign="center" align="left" style="color:#333">+10% over</td>
        <td valign="center" align="left" style="color:#333">+15% over</td>
        <td valign="center" align="left" style="color:#333">+20% over</td>
        <td valign="center" align="left" style="color:#333">+25% over</td>
        <td valign="center" align="left" style="color:#333">+30% over</td>
        <td valign="center" align="left" style="color:#333">+32% over</td>
    </tr> 
    <?php foreach ($semi_monthly as $sm){?>
      <tr>      
        <td valign="center" align="left" style="color:#333">
        	<?php echo $sm->getStatus(); ?>
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD0(),2,".",","); ?>
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD1(),2,".",","); ?>        
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD2(),2,".",","); ?>	
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD3(),2,".",","); ?>
        </td> 
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD4(),2,".",","); ?>
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD5(),2,".",","); ?>
        </td>    
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD6(),2,".",","); ?>
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD7(),2,".",","); ?>
        </td>    
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($sm->getD8(),2,".",","); ?>
        </td>             
      </tr>
    <?php } ?> 
</table>
</div>
<br />
<div class="tax-table-legend">
	<b>LEGEND:</b>
    <ul class="inline">
    	<li><b>Z - Zero exemption</b></li>
        <li><b>S - Single</b></li>
        <li><b>ME - Married Employee</b></li>
        <li><b>1;2;3;4 - Number of qualified dependent children</b></li>
    </ul>
</div>
<div class="tax-table-legend-center">
	<ul class="inline">
    	<li style="font-size:11px;"><b>S/ME = P50,000 EACH WORKING EMPLOYEE</b></li>
        <li style="font-size:11px;"><b>Qualified Dependent Child = P25,000 each but not exceeding four (4) children</b></li>        
    </ul>
</div>
<div class="tax-table-a">
	<p style="margin:0;">USE <b>TABLE A</b> FOR SINGLE/MARRIED EMPLOYEES WITH NO QUALIFIED DEPENDENT</b></p>
    <ol>
    	<li>Married Employee (Husband or Wife) whose spouse is unemployed.</li>
        <li>Married Employee (Husband or Wife) whose spouse is a non-resident citizen receiving income from foreign sources.</li>
        <li>Married Employee (Husband or Wife) whose spouse is engaged in business.</li>
        <li>Single with dependent father/mother/brother/sister/senior citizen.</li>
        <li>Single</li>
        <li>Zero Exemption for Employee with multiple employers for their 2nd,3rd...employers(main employer claims personal and additional exemption)</li>
        <li>Zero Exemption for those who failed to file Application for Registration.</li>        
    </ol>
    
    <p style="margin:0;">USE <b>TABLE B</b> FOR THE FOLLOWING SINGLE/MARRIED EMPLOYEES WITH QUALIFIED DEPENDENT CHILDREN:</p>
    <ol>
    	<li>Employed husband and husband claims exemption of children.</li>
        <li>Employed wife whose husband is also employed or engaged in business; husband waived claim for dependent children in favor of the employed wife.</li>
        <li>Single with qualified dependent children.</li>        
    </ol>
</div>
