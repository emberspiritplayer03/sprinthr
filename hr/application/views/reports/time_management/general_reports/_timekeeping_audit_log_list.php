
<script> 
	
  $(function() {		
		  var oTable = $('#audit_trail_timekeeping_dt').dataTable( {
	       "aoColumns": [              

	            {sWidth: '2%',sClass:'dt_small_font'}, // no                        
              {sWidth: '5%',sClass:'dt_small_font dt_center'}, //username
              {sWidth: '10%',sClass:'dt_small_font dt_center'}, // role
              {sWidth: '45%',sClass:'dt_small_font dt_center'}, // audited action
              {sWidth: '8%',sClass:'dt_small_font dt_center'}, // from
              {sWidth: '8%',sClass:'dt_small_font dt_center'}, // to
              {sWidth: '5%',sClass:'dt_small_font dt_center'}, // event status
              {sWidth: '5%',sClass:'dt_small_font dt_center'}, // position
              {sWidth: '5%',sClass:'dt_small_font dt_center'}, // department
              {sWidth: '40%',sClass:'dt_small_font dt_center'}  // date & time                                                            

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
<style type="text/css">


  #btn-search{
    float:right;
  }
  #audit_trail_timekeeping_filter{
    height:24px;
    margin-right:10px;
  }
  #download_timekeepping{
    float:left;
  }
  #at_filter_dropdown{
    float: right;
    margin-bottom:20px;
  }
  
</style>

<div class="table-container" style="font-size:12px;">

    &nbsp;&nbsp;&nbsp;

    <div class="at_fiter">

        <div id="download_timekeeping_link" style="display:block;">
          <a id="download_timekeepping" class="gray_button vertical-middle" href="<?php echo $at_timekeeping_download_url.'?search_col=&search_field='; ?>"><i class="icon-excel icon-custom vertical-middle"></i> <b>Download Time Keeping List</b></a>
        </div>
        <div id="download_timekeeping_filter" style="display:block;"></div>

          <div id="at_filter_dropdown">

                Search By:
                <select id="audit_trail_timekeeping_filter">
                    <option value="all">All</option>
                    <option value="username">Username</option>
                    <option value="activity_action">Action</option>
                    <option value="position">Position</option>
                    <option value="department">Department</option>
                    <option value="audited_date">Audited Date</option>
                </select>

                <input type="text" id="tm_search_value" name="search_value">

               <input type="button" name="Search" id="tm_btn_search" class="gray_button vertical-middle" value="Search">
          </div>

    </div>

    
    <div id="tbl_audit_trail_timekeeping_dt" style="display:block;">

      <table id="audit_trail_timekeeping_dt" class="display" style="width:100%;">

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
              <?php endforeach; ?>  
          
      </table>
    </div>

    <div id="tbl_filter_audit_trail_timekeeping_dt" style="display:none;"></div>
  

</div>


<script>


 $(document).ready(function () {


      $("#tbl_audit_trail_timekeeping_dt").show();

      $("#tm_btn_search").click(function () {

          var SearchCol = $('#audit_trail_timekeeping_filter').val();
          var SearchField = $('#tm_search_value').val();

          //console.log(SearchCol+' = '+SearchField);

          $('#download_timekeeping_link').hide();
          
          var download_link = '<a id="download_hr" class="gray_button vertical-middle" href="<?php echo $at_timekeeping_download_url;?>?search_col='+SearchCol+'&search_field='+SearchField+'"><i class="icon-excel icon-custom vertical-middle"></i> <b>Download TimeKeeping List</b></a>';

          $('#download_timekeeping_filter').html(download_link);
          
           $.ajax({
              url: base_url + 'reports/filter_load_timekeeping_audit_log_list',
              type: 'GET',
              data: {search_col: SearchCol,
                     search_field: SearchField
                   },
              success:function(data){         

                $("#tbl_audit_trail_timekeeping_dt").hide();
                $("#tbl_filter_audit_trail_timekeeping_dt").show();
                $("#tbl_filter_audit_trail_timekeeping_dt").html(data);
                 //console.log(data);
              }
          });
        
      });


     /* $('.dataTables_filter input').keyup(function (){

         $('#search_value').val(this.value);

       });*/

    
      //------------------------------------------

     //var table = $("#audit_trail_timekeeping_dt_filter").append($("#audit_trail_timekeeping_filter"));
      //var table = $("#audit_trail_timekeeping_dt_filter").append($("#btn-search"));

     // table.destroy();
     //table.fnDestroy();

      //var table = $("#audit_trail_timekeeping_dt").dataTable({
      //  "searching": false
      //});


  });

 
</script>



