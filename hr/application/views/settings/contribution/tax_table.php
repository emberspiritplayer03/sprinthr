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
  </tr>
</thead>
    <tr>
        <td valign="center" align="left" style="color:#333">Compensation Level (CL)</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">10,417 and below</td>
        <td valign="center" align="left" style="color:#333">10,417</td>
        <td valign="center" align="left" style="color:#333">16,667</td>
        <td valign="center" align="left" style="color:#333">33,333</td>
        <td valign="center" align="left" style="color:#333">83,333</td>
        <td valign="center" align="left" style="color:#333">333,333</td>
    </tr>
    <tr>
        <td valign="center" align="left" style="color:#333">Prescribed Min. Withholding Tax</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">0.00</td>
        <td valign="center" align="left" style="color:#333">0.00 +15% over CL</td>
        <td valign="center" align="left" style="color:#333">937.50 +20% over CL</td>
        <td valign="center" align="left" style="color:#333">4,270.70 +25% over CL</td>
        <td valign="center" align="left" style="color:#333">16,770.70 +30% over CL</td>
        <td valign="center" align="left" style="color:#333">91,770.70 +35% over CL</td>
    </tr> 
    <tr> 
        <td class="ui-state-default" valign="left" align="left" style="color:#333">MOTHLY</td>    
        <th class="ui-state-default" valign="left" align="left" style="color:#333"></th>    
        <th class="ui-state-default" valign="left" align="left" style="color:#333">1</th>
        <th class="ui-state-default" valign="left" align="left" style="color:#333">2</th>
        <th class="ui-state-default" valign="left" align="left" style="color:#333">3</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">4</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">5</th>   
        <th class="ui-state-default" valign="left" align="left" style="color:#333">6</th>   
    </tr>
    <tr>
        <td valign="center" align="left" style="color:#333">Compensation Level (CL)</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">20,833 and below</td>
        <td valign="center" align="left" style="color:#333">20,833</td>
        <td valign="center" align="left" style="color:#333">33,333</td>
        <td valign="center" align="left" style="color:#333">66,667</td>
        <td valign="center" align="left" style="color:#333">166,667</td>
        <td valign="center" align="left" style="color:#333">666,667</td>
    </tr>
    <tr>
        <td valign="center" align="left" style="color:#333">Prescribed Min. Withholding Tax</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" style="color:#333">0.00</td>
        <td valign="center" align="left" style="color:#333">0.00 +15% over CL</td>
        <td valign="center" align="left" style="color:#333">1,875 +20% over CL</td>
        <td valign="center" align="left" style="color:#333">8,541.80 +25% over CL</td>
        <td valign="center" align="left" style="color:#333">33,541.80 +30% over CL</td>
        <td valign="center" align="left" style="color:#333">183,541.80 +35% over CL</td>
    </tr> 

    <tr> 
        <td class="ui-state-default" valign="left" align="left" colspan="2" style="color:#333">ANNUAL</td>    
        <th class="ui-state-default" valign="left" align="left" style="color:#333"></th>     
        <th class="ui-state-default" valign="left" align="left" colspan="5" style="color:#333">TAX RATE</th>
    </tr>    
    <tr> 
        <td valign="center" align="left" colspan="2" style="color:#333">P250,000 and below</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" colspan="5" style="color:#333">None (0%)</td>
    </tr>  
    <tr> 
        <td valign="center" align="left" colspan="2" style="color:#333">P250,000 to P400,000</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" colspan="5" style="color:#333">15% of excess over P250,000</td>
    </tr> 
    <tr> 
        <td valign="center" align="left" colspan="2" style="color:#333">P400,000 to P800,000</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" colspan="5" style="color:#333">P22,500 + 20% of excess over P400,000</td>
    </tr> 
    <tr> 
        <td valign="center" align="left" colspan="2" style="color:#333">P800,000 to P2,000,000</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" colspan="5" style="color:#333">P102,500 + 25% of excess over P800,000</td>
    </tr> 
    <tr> 
        <td valign="center" align="left" colspan="2" style="color:#333">P2,000,000 to P8,000,000</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" colspan="5" style="color:#333">P402,500 + 30% of excess over P2,000,000</td>
    </tr> 
    <tr> 
        <td valign="center" align="left" colspan="2" style="color:#333">Above 8,000,000</td>
        <td valign="center" align="left" style="color:#333"></td>
        <td valign="center" align="left" colspan="5" style="color:#333">P2.2025 million + 35% of excess over P8,000,000</td>
    </tr> 
</table>
</div>
