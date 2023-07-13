<select id="page_number" name="page_number" style="width:150px;">
<?php if($max_page != 0) { ?>
    <?php for($i=1; $i<=$max_page; $i++): ?>
        <option value="<?php echo $i; ?>">Page <?php echo $i; ?></option>
    <?php endfor; ?>
    <option value="all"> All Page</option>
 <?php } else { ?>
 <option value=""> No Record(s) to print</option>
 <?php }?>
</select>
