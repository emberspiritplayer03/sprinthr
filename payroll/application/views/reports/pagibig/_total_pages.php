<?php if($pages){ ?>	
	<select class="text-input" name="limit_start" id="limit_start" style="width:170px;">
        <option selected="selected" value="all">Print All</option>
        <?php 
            for($i=1;$i<=$pages;$i++){ 
            $limit_start += 40; 
        ?>
            <option value="<?php echo $limit_start; ?>">Page <?php echo $i; ?></option>
        <?php } ?>        
    </select>
	<br />
	<span style="font-size:11px;font-style:italic;">(Total of <?php echo $pages ?> pages)</span>
<?php }else{ ?>
<select class="text-input" name="limit_start" id="limit_start" style="width:170px;">
	<option value="0">No record to print</option>	
</select>
<?php } ?>
