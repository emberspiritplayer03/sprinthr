<script>
	$(function(){
		$("#section_id").change(function(){
		    if($(this).val() == "add") {
		      	checkForAddSection();
		    }
		});
	});
</script>
<select class="select_option" name="section_id" id="section_id" >
    <option value="" selected="selected">-- Select Section --</option>
		<?php foreach($sections as $value) { ?>
            <option selected="selected" value="<?php echo $value->getId(); ?>"><?php echo $value->getTitle(); ?></option>
        <?php } ?>
<option value="add">Add Section...</option>
</select>