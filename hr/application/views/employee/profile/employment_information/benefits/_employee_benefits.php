<script>
$(function(){
  $(".btn-remove-benefit").click(function(){
    var eid = $(this).attr("id");
    //deleteEmployeeBenefit(eid);
  });
});
</script>
<table width="858" id="hor-minimalist-b"  border="0">
  <thead>
    <tr>          
      <th width="117" scope="col">Benefit Code</th>
      <th width="150" scope="col">Benefit Name</th>
      <th width="109" scope="col">Amount</th>
      <!-- <th width="50">&nbsp;</th> -->
    </tr>
  </thead>
  <tbody>
  <?php foreach($benefits as $b) { ?>
    <tr>          
      <td><?php echo $b['code']; ?></td>
      <td><?php echo $b['name']; ?></td>
      <td><?php echo number_format($b['amount'],2,".",","); ?></td>
      <!-- <td> -->
      <?php //if( $b['applied_to'] != $all_employees ){ ?>
        <!-- <a class="btn btn-mini btn-remove-benefit" id="<?php echo $b['id']; ?>" href="javascript:void(0);">Remove Benefit</a> -->
      <?php //} ?>
      <!-- </td> -->
    </tr>
   <?php } ?>
  </tbody>
</table>