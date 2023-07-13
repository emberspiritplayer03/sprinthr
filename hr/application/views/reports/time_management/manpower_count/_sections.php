<script>
$(function(){
	$('#section-all').click(function(event) {
        if(this.checked) {
            $('.chk-sections').each(function() { 
                this.checked = true; 
            });
        }else{
            $('.chk-sections').each(function() {
                this.checked = false;
            });        
        }
    });
});
</script>
<div class="option-container">
<p>Groups / Sections</p>
<ul class="options-list">
	<?php if( !empty($groups) ){ ?>
		<li><label class="checkbox"><input type="checkbox" id="section-all" />All</label></li>
	<?php } ?>
    <?php foreach($groups as $g){ ?>
    	<li><label class="checkbox"><input type="checkbox" class="chk-sections" name="manpower[groups][<?php echo $g['id']; ?>]" value="<?php echo $g['title']; ?>" /><?php echo $g['title']; ?></label></li>
    <?php } ?>
</ul>
</div>