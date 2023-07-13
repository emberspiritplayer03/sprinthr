<script>
$(function(){
    $(".hover-link").hover(
        function(){
            $(this).find(".overlay").show();
            $(this).find(".overlay").css('display','inline-block');
        },
        function(){
            $(this).find(".overlay").hide();
        }
    );
});
</script>
<?php if($schedule) { ?>
    <h2>Schedule</h2>
    <table style="margin:0px" summary="Schedule" class="formtable" id="box-table-a">
        <thead>
            <tr>
                <th width="30%" scope="col">Event</th>
                <th width="40%" scope="col">Description</th>
                <th width="10%" style="text-align:center" scope="col">Status</th>
                <th width="20%" style="text-align:center" scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if($schedule) { ?>
                <?php foreach($schedule as $key => $e) { ?>
                    <tr class="hover-link">
                      <td><?php echo $e['event_type'];?></td>
                      <td>
                          <?php echo "<span class='badge badge-info'> {$e["item"]}</span> {$e["description"]}";?>
                          
                      </td>
                      <td style="text-align:center">
                          <?php
                              if($e['status'] == G_Notifications::STATUS_NEW) {
                                  echo '<span class="badge badge-warning"><i class="icon icon-info-sign icon-white"> </i> New</span>';
                              }else{
                                  echo '<span class="badge badge-success"><i class="icon icon-ok icon-white"> </i> Seen</span>';
                              } 
                          ?>
                      </td>
                      <td style="text-align:center" >
                          <div class="overlay" style="height: 10px; display: none;">
                              <a target="_blank" href="<?php echo url('notifications/view_notification?eid='.Utilities::encrypt($e['id']).'&hash='.Utilities::createHash($e['id']) . '&from=' . $from . '&to='. $to); ?>" style="padding:0px 7px 0px 5px" class="btn btn-edit-type" ><i class="icon-search"> </i> View Details</a>
                          </div>
                      </td>
                    </tr>
                <?php } ?>           
            <?php }else{ ?>
                <tr>
                  <td colspan="4"><b>No record(s) found.</b></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>    
    <br/><br/>
<?php }else if($employee) { ?>
    <h2>Employee</h2>
    <table style="margin:0px" summary="Schedule" class="formtable" id="box-table-a">
        <thead>
            <tr>
                <th width="30%" scope="col">Event</th>
                <th width="40%" scope="col">Description</th>
                <th width="10%"style="text-align:center"  scope="col">Status</th>
                <th width="20%" style="text-align:center"  scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if($employee) { ?>
                <?php foreach($employee as $key => $e) { ?>
                    <tr class="hover-link">
                      <td><?php echo $e['event_type'];?></td>
                      <td>
                          <?php echo "<span class='badge badge-info'> {$e["item"]}</span> {$e["description"]}";?>
                          
                      </td>
                      <td style="text-align:center">
                          <?php
                              if($e['status'] == G_Notifications::STATUS_NEW) {
                                  echo '<span class="badge badge-warning"><i class="icon icon-info-sign icon-white"> </i> New</span>';
                              }else{
                                  echo '<span class="badge badge-success"><i class="icon icon-ok icon-white"> </i> Seen</span>';
                              } 
                          ?>
                      </td>
                      <td style="text-align:center" >
                          <div class="" style="">
                              <a target="_blank" href="<?php echo url('notifications/view_notification?eid='.Utilities::encrypt($e['id']).'&hash='.Utilities::createHash($e['id']));?>" style="padding:0px 7px 0px 5px" class="btn btn-edit-type" ><i class="icon-search"> </i> View Details</a>
                          </div>
                      </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                  <td colspan="4"><b>No record(s) found.</b></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br/><br/>  

<?php }else if($attendance) { ?>
    <h2>Attendance (<?php echo $cutoff; ?>) </h2>
    <table style="margin:0px" summary="Schedule" class="formtable" id="box-table-a">
        <thead>
            <tr>
                <th width="30%" scope="col">Event</th>
                <th width="40%" scope="col">Description</th>
                <th width="10%"style="text-align:center"  scope="col">Status</th>
                <th width="20%" style="text-align:center"  scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if($attendance) { ?>
                <?php foreach($attendance as $key => $a) { ?>
                    <tr class="hover-link">
                      <td><?php echo $a['event_type'];?></td>
                      <td>
                          <?php echo "<span class='badge badge-info'> {$a["item"]}</span> {$a["description"]}";?>
                          
                      </td>
                      <td style="text-align:center">
                          <?php
                              if($a['status'] == G_Notifications::STATUS_NEW) {
                                  echo '<span class="badge badge-warning"><i class="icon icon-info-sign icon-white"> </i> New</span>';
                              }else{
                                  echo '<span class="badge badge-success"><i class="icon icon-ok icon-white"> </i> Seen</span>';
                              } 
                          ?>
                      </td>
                      <td style="text-align:center" >
                          <div class="overlay" style="height: 10px; display: none;">
                          <!-- <div class="" style="height: 10px;"> -->
                              <a target="_blank" href="<?php echo url('notifications/view_notification?eid='.Utilities::encrypt($a['id']).'&hash='.Utilities::createHash($a['id']) . '&from=' . $from . '&to='. $to);?>" style="padding:0px 7px 0px 5px" class="btn btn-edit-type" ><i class="icon-search"> </i> View Details</a>
                          </div>
                      </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                  <td colspan="4"><b>No record(s) found.</b></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>  
<?php }else if($dtr) { ?>
    <h2>DTR</h2>
    <table style="margin:0px" summary="Schedule" class="formtable" id="box-table-a">
        <thead>
            <tr>
                <th width="30%" scope="col">Event</th>
                <th width="40%" scope="col">Description</th>
                <th width="10%"style="text-align:center"  scope="col">Status</th>
                <th width="20%" style="text-align:center"  scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if($dtr) { ?>
                <?php foreach($dtr as $key => $a) { ?>
                    <tr class="hover-link">
                      <td><?php echo $a['event_type'];?></td>
                      <td>
                          <?php echo "<span class='badge badge-info'> {$a["item"]}</span> {$a["description"]}";?>
                          
                      </td>
                      <td style="text-align:center">
                          <?php
                              if($a['status'] == G_Notifications::STATUS_NEW) {
                                  echo '<span class="badge badge-warning"><i class="icon icon-info-sign icon-white"> </i> New</span>';
                              }else{
                                  echo '<span class="badge badge-success"><i class="icon icon-ok icon-white"> </i> Seen</span>';
                              } 
                          ?>
                      </td>
                      <td style="text-align:center" >
                          <div class="overlay" style="height: 10px; display: none;">
                              <a target="_blank" href="<?php echo url('notifications/view_notification?eid='.Utilities::encrypt($a['id']).'&hash='.Utilities::createHash($a['id']));?>" style="padding:0px 7px 0px 5px" class="btn btn-edit-type" ><i class="icon-search"> </i> View Details</a>
                          </div>
                      </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                  <td colspan="4"><b>No record(s) found.</b></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>      
<?php }else{ ?>
    <div class="alert alert-warning">No new notification.</div>
<?php } ?>