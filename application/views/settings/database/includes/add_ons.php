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
    $("#addons-list").change(function(){       
        var addon = $(this).val();              
        loadAddOnInfo(addon);
    });
});
</script>
<div id="error_container"></div>
<div class="formWrapper" id="addon-wrapper">			
    <legend>Current SprintHR Version : <?php echo $app_version; ?></legend>
    <div id="addon-err-msg"></div>
    <div class="input-prepend">
        <span class="add-on">Add Ons</span>
        <select id="addons-list" class="span4">
            <option>-- Select Add Ons --</option>
           <?php foreach($addons_data as $key => $value){ ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
           <?php } ?>           
        </select>
    </div>    
    <div id="addon-info-container"></div>
</div>