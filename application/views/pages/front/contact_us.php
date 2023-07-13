<div id="sixth" class="section_spacing last">
<div id="footer_container">
	<div class="section_shadow"></div>
	<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Cabuyao,+CALABARZON,+Philippines+Gleent&amp;aq=t&amp;sll=37.0625,-95.677068&amp;sspn=34.808514,86.572266&amp;ie=UTF8&amp;hq=Gleent&amp;hnear=Cabuyao+City,+Laguna,+Calabarzon,+Philippines&amp;t=m&amp;cid=15085482466797353038&amp;ll=14.286343,121.116199&amp;spn=0.039925,0.054932&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br />
	<!--<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Gleent+Incorporated,+Cabuyao,+Philippines&amp;aq=1&amp;oq=gleent&amp;sll=37.0625,-95.677068&amp;sspn=38.281301,86.572266&amp;ie=UTF8&amp;hq=Gleent+Incorporated,&amp;hnear=Cabuyao+City,+Laguna,+CALABARZON,+Philippines&amp;filter=0&amp;t=m&amp;fll=14.272259,121.125292&amp;fspn=0.002865,0.005284&amp;st=104379868401627743538&amp;rq=1&amp;ev=zi&amp;split=1&amp;ll=14.274407,121.125298&amp;spn=0.002865,0.005284&amp;output=embed&iwloc=near"></iframe>-->
	<div id="wrap">
        <footer id="wrap" class="bs-docs-grid footer">        	
        	<div class="row-fluid">
                <div class="footer_content">
                    <div class="contact_form footer_section">
                    	<h3>We’d love to hear from you</h3>
                    	<div class="form_submit_message_container" style="display:none;"></div>
                    	<form id="sprint_contact_form" class="sprint_contact_form" method="POST" action="<?php echo url('inquiry/send'); ?>">
                    		<input type="hidden" name="g_token" id="g_token" value="<?php echo $g_token; ?>" />
                        	<p><input onFocus="if(this.value == '*Your Name:')this.value=''" onblur="if(this.value=='' || this.value == '*Your Name:')this.value ='*Your Name:'" id="your_name" name="sprnt['Name:']" value="*Your Name:" type="text" class="validate[required,length[1,50]]" /></p>
                            <p><input onFocus="if(this.value == '*Email Address:')this.value=''" onblur="if(this.value=='' || this.value == '*Email Address:')this.value ='*Email Address:'" id="email_address" name="sprnt['Email Address']" value="*Email Address:" type="text" class="validate[required,custom[email],length[1,50]]" /></p>
                            <p><input onFocus="if(this.value == '*Company Name:')this.value=''" onblur="if(this.value=='' || this.value == '*Company Name:')this.value ='*Company Name:'" id="company_name" name="sprnt['Company Name:']" value="*Company Name:" type="text" class="validate[required,length[1,50]]" /></p>
                            <p><textarea onFocus="if(this.value == '*Message:')this.value=''" onblur="if(this.value=='' || this.value == '*Message:')this.value ='*Message:'" name="sprnt['Message:']" id="your_message"></textarea></p>
                            <p><select name="sprnt['Where did you find us?']" id="g_inquiry_option">
                            	<option selected="selected">Where did you find us?</option>
                                <option>Flyers</option>
                                <option>Radio Ad</option>
                                <option>Banner / Tarpuline</option>
                                <option>Newspaper / Magazine</option>
                                <option>Friend / Relative</option>
                                <option>Others</option>
                            </select></p>
                            <p><button type="submit" id="g_submit_btn">Submit <span></span></button></p>
                        </form>
                    </div>
                    <div class="social_holder footer_section">
                        <h3>Let’s get connected!</h3>
                        <ul class="social_tab">
                            <li><a href="http://facebook.com/SprintHR" target="_blank"><span class="social_button facebook_social"></span></a></li>
                            <li><a href="http://twitter.com/SprintHR" target="_blank"><span class="social_button twitter_social"></span></a></li>
                            <!--<li><span class="social_button youtube_social"></span></li>-->
                            <div class="clear"></div>
                        </ul>
                    </div>
                </div>
            </div>        	
        </footer>        
	</div><!-- #wrap -->    
</div><!-- #footer-container -->
</div>