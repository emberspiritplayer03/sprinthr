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
<?php if($payroll) { ?>
    <h2>Payroll</h2>
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
            <?php if($payroll) { ?>
                <?php foreach($payroll as $key => $e) { ?>
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
                              <?php 
                                $cutoff_01 = $_POST['cutoff_01'];
                                $cutoff_02 = $_POST['cutoff_02'];
                              ?>
                              <a target="_blank" href="<?php echo url('payroll_register/view_payroll_notification?eid='.Utilities::encrypt($e['id']).'&hash='.Utilities::createHash($e['id']).'&cutoff_01=' . $cutoff_01 . '&cutoff_02=' . $cutoff_02 );?>" style="padding:0px 7px 0px 5px" class="btn btn-edit-type" ><i class="icon-search"> </i> View Details</a>
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
  
<?php }else{ ?>
    <div class="alert alert-warning">No new notification.</div>
<?php } ?>