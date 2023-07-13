<div class="container_12">
<?php
$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
?>
<?php foreach ($holidays as $h):?>
<?php
$record_months[$h->getMonth()][$h->getDay()] = $h;
?>
<?php endforeach;?>
<?php $i=0; ?>
<?php foreach ($record_months as $month => $month_holidays):?>
    <?php
	ksort($month_holidays);
	?>
    <div class="col_1_2">
      <div class="inner">
        <h3 class="section_title blue"><?php echo $months[$month - 1];?></h3>
        <table width="100%" class="formtable" summary="">
          <tbody>
            <thead>
            <tr>
              <th width="8%">Day</th>
              <th width="40%">Title</th>
              <th width="20%">Name</th>
              <th width="19%">Branches</th>
              <th width="13%">&nbsp;</th>
            </tr>
            </thead>         
		<?php foreach ($month_holidays as $h):
        $selected_branches = G_Company_Branch_Finder::findByHoliday($h);
        ?>    
            <tr>
              <td width="8%"><?php echo $h->getDay();?></td>
              <td width="40%"><strong><?php echo $h->getTitle();?></strong></td>
              <td width="20%"><?php echo $h->getTypeName();?></td>
              <td width="19%"><?php
                $branch = array();
                if (count($selected_branches) == $total_branches) {
                    echo 'All branches';
                } else {				
                    foreach ($selected_branches as $b) {
                        $branch[] = $b->getName();
                    }
                    echo implode(', ', $branch);
                }
              ?></td>
              <td width="13%">
                <a class="ui-icon ui-icon-close tooltip" href="javascript:void(0)" onclick="javascript:deleteHoliday(<?php echo $h->getId();?>)" style="float:right" title="Delete"></a>
                <a class="ui-icon ui-icon-pencil tooltip" href="javascript:void(0)" onclick="javascript:editHolidayFromList(<?php echo $h->getId();?>)" style="float:right" title="Edit"></a>
              </td>
            </tr>  
        <?php endforeach;?>
          </tbody>
        </table>            
        </div>
    </div>
    <?php if (fmod($i,2)){ echo "<div class='clear'></div><br>";} ?>
    <?php $i++; ?>
<?php endforeach;?>
</div>
<script language="javascript">
$('.tooltip').tipsy({gravity: 's'});
</script>