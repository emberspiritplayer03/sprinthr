
<script> 
  
  $(function() {    


      var oTables = $('#audit_trail_timekeeping_list').dataTable( {
         "aoColumns": [              

              {sName:'col-no', sWidth: '2%',sClass:'dt_small_font'}, // no                        
              {sName:'col-username',sWidth: '5%',sClass:'dt_small_font dt_center'}, //username
              {sName:'col-role',sWidth: '8%',sClass:'dt_small_font dt_center'}, // role
              {sName:'col-audited_action',sWidth: '45%',sClass:'dt_small_font dt_center'}, // audited action
              {sName:'col-from',sWidth: '5%',sClass:'dt_small_font dt_center'}, // from
              {sName:'col-to',sWidth: '5%',sClass:'dt_small_font dt_center'}, // to
              {sName:'col-event_status',sWidth: '5%',sClass:'dt_small_font dt_center'}, // event status
              {sName:'col-position',sWidth: '5%',sClass:'dt_small_font dt_center'}, // position
              {sName:'col-department',sWidth: '5%',sClass:'dt_small_font dt_center'}, // department
              {sName:'col-date_time',sWidth: '40%',sClass:'dt_small_font dt_center'}  // date & time                                                               

          ],
          "bProcessing":true,
          "bServerSide":false,
          "bAutoWidth": true,
          "bInfo":false,
          "bJQueryUI": true,
          "sPaginationType": "full_numbers",
          "bPaginate": true,
          "bFilter": false,
          "oLanguage": {
           "sSearch": "Search By: "
         }

      });
  });

</script>

  <table id="audit_trail_timekeeping_list" style="width:100%;">

        <thead>
          
          <tr>

            <th class="tr" data-name="col-no" valign="top" ></th>
            <th class="tr" data-name="col-username" valign="top" >Username</th>
            <th class="tr" data-name="col-role" valign="top" >Role</th>
            <!--<th valign="top" >Module</th>
            <th valign="top" >Action</th>
            <th valign="top" >Activity Type</th>-->
            <th class="tr" data-name="col-audited_action" valign="top" >Audited Action</th>
            <th class="tr" data-name="col-from" valign="top" >From</th>
            <th class="tr" data-name="col-to" valign="top" >To</th>
            <th class="tr" data-name="col-event_status" valign="top" >Event Status</th>  
            <th class="tr" data-name="col-position" valign="top" >Position</th>
            <th class="tr" data-name="col-department" valign="top" >Department</th>  
            <th class="tr" data-name="col-date_time" valign="top" >Audit Date & Time</th>  
            <!--<th valign="top" >Audit Time</th>-->
            
          </tr>
          
        </thead>
        
              <?php 
              $i = 1;
              foreach($data as $value):?>
                  <tr> 

                      <td valign="center" align="left" style="color:#333"><?php echo $i++;?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['username'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['role'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['activity_action'].' '.$value['activity_type'].' '.$value['audited_action'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['action_from'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['action_to'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['event_status'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['position'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['department'];?></td>
                      <td valign="center" align="left" style="color:#333"><?php echo $value['audit_date'].' '.$value['audit_time'];?></td>
                   
                      
                  </tr>
            <?php  endforeach; ?>   

    </table>
