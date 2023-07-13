<script>
    $(function(){
      $('.mainmenu').find('li:first-child').addClass('first');
      $('.mainmenu').find('li:last-child').addClass('last'); 

      $('.mainmenu').find('li:first-child').prepend('<span class="lshad">');
      $('.mainmenu').find('li:last-child').prepend('<span class="rshad">');

      <?php if($payroll_report_default_module != '') { ?>
      		$('#mnu_payroll_report').attr("href",'<?php echo $payroll_report_default_module;?>');
      <?php } ?>
    });
</script>
<div id="menu"><?php echo $hdr_sprint_menu; ?></div>
