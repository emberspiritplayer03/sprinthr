<div id="job_vacancy_list">
<ul>
	<?php 
		foreach($data as $key => $value){
			foreach($value as $keysub => $subvalue){
				$jhid =  Utilities::createHash($subvalue['job_id']);
				$jeid =  Utilities::encrypt($subvalue['job_id']);
	?>
		<li>
			<h2 class="job_title">
				<?php echo $subvalue['job_title']; ?>
				<small class="job_meta"><i class="icon-list-alt icon-fade"></i> Open until : <b><?php echo $subvalue['advertisement_end']; ?></b></small>
			</h2>
			<div class="job-description-container">
				<p><?php echo $subvalue['job_description']; ?></p>
			</div>
            <div class="apply-btn-container">
            	<?php $count = G_Applicant_Helper::isEmailAndJobIdExists($hdr_email_address,$subvalue['job_id']);?>
				<?php if($count == 0){ ?>
					<?php if($total_pending_applications < MAX_JOB_APPLICATION){?>
						<a class="btn" href="<?php echo url("applicant/apply?jeid={$jeid}&jhid={$jhid}"); ?>">
							<i class="icon-hand-up"></i> Apply
						</a>	
					<?php }else{ ?>
						<span class="label label-important"><i class="icon-remove-sign icon-white"></i> Can no longer apply for this job.</span>
					<?php } ?>
				<?php }else{ ?>
					<span class="label label-warning"><i class="icon-star icon-white"></i> Already sent application</span>
				<?php } ?>	
            </div>
			<div class="clear"></div>			
		</li>
	<?php			
			}
		}
	?>
</ul>	
</div>

