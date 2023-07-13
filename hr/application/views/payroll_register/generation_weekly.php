<style>
.blue_button{vertical-align: middle;}    
</style>
<script>
$(function(){
    var jq = jQuery.noConflict();
    jq('.dropdown-toggle').dropdown();
})
</script>
<form method="get">
Go to
<select name="frequency">
    <!-- <?php foreach ($frequencies as $key => $f):?>
         <option value="<?php echo ($f->id);?>" <?php echo (($f->id) == $frequency) ? 'selected="selected"' : '';?>><?php echo $f->pay_period_name;?></option>
    <?php endforeach;?> -->
    <?php
    foreach(G_Settings_Pay_Period_Finder::findAll() as $period)
    {
    ?>

        <option value = "<?php echo $period->id; ?>" <?php echo $frequency == $period->id ? 'selected="selected"' : ''?>> <?php echo $period->pay_period_name; ?> </option>    

    <?php
    }
    ?>
    <!-- <option value = "1">Bi-Monthly</option>
    <option value = "2">Weekly</option>   -->  
</select>

<select name="month">
    <?php foreach ($months as $key => $m):?>
        <option value="<?php echo ($key + 1);?>" <?php echo (($key + 1) == $month) ? 'selected="selected"' : '';?>><?php echo $m;?></option>
    <?php endforeach;?>
</select>
<select name="year">
    <?php foreach($payroll_years as $y) { ?>
            <option value="<?php echo $y;?>" <?php echo ($y == $year) ? 'selected="selected"' : '';?>><?php echo $y; ?></option>
    <?php } ?>

</select> <button class="blue_button" type="submit">Go</button>
</form>
<br><br>
<?php foreach ($periods as $p):?>
   
    <?php  
        $start_date = $p->getStartDate();
        $end_date = $p->getEndDate();
        $cutoff_character = $p->getCutoffCharacter();
    ?>
    <div style="border:1px solid #CCCCCC; margin-bottom:10px; padding: 10px">
        <div style="font-size: 20px; font-weight:bold"><?php echo Tools::getMonthString($month);?> - <?php echo $cutoff_character;?> <span style="font-size:13px">(<?php echo Tools::convertDateFormat($start_date);?> - <?php echo Tools::convertDateFormat($end_date);?>)</span></div><br>
            <?php 
                echo $btns_lock_unlock[$p->getId()]; 
                echo $btns_dl_payslip[$p->getId()];
                echo $btns_dl_payroll[$p->getId()];
                echo $btns_generate_payroll[$p->getId()];
                echo $btns_view_payslip[$p->getId()];
                echo $btn_processed_payroll[$p->getId()];
            ?>
    </div>
<?php endforeach;?>
<div id="important_payroll_notifications"></div>

<script>
    
$(function() {
    function load_important_payroll_notifications(cutoff_01, cutoff_02) {
        $("#important_payroll_notifications").html(loading_image);
        $.post(base_url + 'notifications/_load_important_payroll_notification_list',{cutoff_01:cutoff_01,cutoff_02:cutoff_02},function(o) {
            $("#important_payroll_notifications").html(o);                           
        })
        
        var $dialog = $('#important_payroll_notifications');
         $dialog.dialog("destroy");
         
        var $dialog = $('#important_payroll_notifications');
        $dialog.dialog({
            title: 'IMPORTANT NOTIFICATIONS',
            resizable: true,
            position: [270,50],
            width: 800,
            modal: false,
            close: function() {
                       $dialog.dialog("destroy");
                       $dialog.hide($.validationEngine.closePrompt('.formError',true)); 
                    }   
                        
            }).dialogExtend({
            "maximize" : false,
            "minimize"  : true,
            "dblclick" : "maximize",
            "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
          }).show();            
    }    

    <?php if($is_enable_popup_notification && $count_payroll_new_notifications > 0) { ?>
    <?php 
        $month = isset($_GET['month']) ? $_GET['month'] : '';
        $year  = isset($_GET['year']) ? $_GET['year'] : '';
        if(!empty($month) && !empty($year)) {

            $sv = new G_Weekly_Cutoff_Period();
            $cutoffs = $sv->expectedWeeklyCutOffPeriodsByMonthAndYear($month, $year); 

            if($cutoffs) {
                $first_cutoff  = $cutoffs[0]['start_date'] . '/' . $cutoffs[0]['end_date'];
                $second_cutoff = $cutoffs[1]['start_date'] . '/' . $cutoffs[1]['end_date'];
            }

        } else {

            $month = date('m');
            $year  = date('Y');
            $sv = new G_Weekly_Cutoff_Period();
            $cutoffs = $sv->expectedWeeklyCutOffPeriodsByMonthAndYear($month, $year);  

            if($cutoffs) {
                $first_cutoff  = $cutoffs[0]['start_date'] . '/' . $cutoffs[0]['end_date'];
                $second_cutoff = $cutoffs[1]['start_date'] . '/' . $cutoffs[1]['end_date'];
            }

        }

    ?>

        <?php if($PAYROLL_NOTIF_ENABLE == 'Yes') { ?>   
            load_important_payroll_notifications('<?php echo $first_cutoff; ?>', '<?php echo $second_cutoff; ?>');
        <?php } ?>

    <?php } ?>
});

</script>