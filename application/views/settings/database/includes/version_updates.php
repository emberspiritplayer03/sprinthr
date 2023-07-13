<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
legend{
	-moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: -moz-use-text-color -moz-use-text-color #E5E5E5;
    border-image: none;
    border-style: none none solid;
    border-width: 0 0 1px;
    color: #333333;
    display: block;
    font-size: 21px;
    line-height: 40px;
    margin-bottom: 20px;
    padding: 0;
    width: 100%;
}
	
</style>
<script>
$(function(){
    loadVersionInfo($("#version-list").val());
    $("#version-list").change(function(){
        var version = $(this).val();       
        loadVersionInfo(version);
    });
});
</script>
<div id="error_container"></div>
<div class="formWrapper" id="version-wrapper">			
    <legend>Current SprintHR Version : <?php echo $app_version; ?></legend>
    <div id="err-msg"></div>
    <div class="input-prepend">
        <span class="add-on">Versions</span>
        <select id="version-list" class="span2">
            <?php foreach($versions as $version){ ?>
            <option value="<?php echo $version; ?>"><?php echo $version; ?></option>
            <?php } ?>
        </select>
    </div>    
    <div id="version-info-container"></div>
</div>