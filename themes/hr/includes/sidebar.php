<div id="sidebar">
	 <?php include_once("job_search.php"); ?>
    <h1 class="sidebar_title">LOCATE US</h1>
    <iframe width="95%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=philippines&amp;aq=&amp;sll=37.0625,-95.677068&amp;sspn=37.871902,86.572266&amp;ie=UTF8&amp;hq=&amp;hnear=Philippines&amp;t=m&amp;z=5&amp;ll=12.879721,121.774017&amp;output=embed"></iframe>
    <br>
    <br />
    <?php if($hdr_email_address){ ?>
    	<a class="btn btn-large btn-primary" href="<?php echo recruitment_url("applicant_login"); ?>"><i class="icon-home icon-white"></i> My Cpanel</a>
    <?php }else{ ?>
    	<a class="btn btn-large btn-primary" href="<?php echo url("register"); ?>"><i class="icon-hand-up icon-white"></i> Register</a>
    	<a class="btn btn-large btn-primary" href="<?php echo recruitment_url("applicant_login"); ?>"><i class="icon-user icon-white"></i> Applicant Login</a>
    <?php } ?>
</div><!-- #sidebar -->