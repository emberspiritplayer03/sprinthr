<script>
$(function(){
    $('.btn-to-enable').click(function(){
        var id = $(this).attr('id');

        if($(this).hasClass('btn-primary')) {
            $(this).html('No');
            $(this).removeClass('btn-primary');
            $(this).addClass('btn-danger');
        }else{
            $(this).html('Yes');
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-primary');
        }

        $.post(base_url+'settings/update_notif_setting',{id:id},
        function(o){
        },"json");
    });
});
</script>

  <table id="dataTableLicenseList" class="display">
    <thead>
      <tr>
        <th valign="top" class="table_header">Title</th>
        <th valign="top" class="table_header" style="text-align:center;">Is enable</th>
      </tr>
    </thead>
      <?php foreach($setting_notif as $notif) { ?>
              <tr>
                <td width="38%" valign="top" bgcolor="#FFFFFF"><?php echo $notif->getTitle(); ?></td>
                <td width="28%" valign="top" style="text-align:center;" bgcolor="#FFFFFF">
                  <a id="<?php echo $notif->getId(); ?>" class="btn-to-enable btn <?php echo ($notif->getIsEnable() == 'Yes' ? 'btn-primary' : 'btn-danger' ); ?>"><?php echo $notif->getIsEnable() ?></a>    
                </td>
                <!--
                  <td width="8%" valign="top" bgcolor="#FFFFFF">
                    <a id="" class="btn btn-small edit-notification-settings" data-key="<?php echo $notif->getId(); ?>" href="javascript:void(0);">Edit</a>
                  </td> 
                -->
              </tr>
    <?php } ?>
  </table>