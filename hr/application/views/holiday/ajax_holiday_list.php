<br><h2><?php echo $year;?> Holidays</h2>
<div class="container_12" style="padding:0; margin-top:0; display:block;">
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
    <div class="col_1">
      <div class="inner">
        <h3 class="section_title blue no-margin"><i class="icon-calendar icon-fade vertical-middle"></i> <?php echo $months[$month - 1];?></h3>
        <table width="100%" class="formtable" summary="">
          <tbody>
            <thead>
            <tr>
              <th width="7%">Day</th>
              <th width="25%">Title</th>
              <th width="20%">Type</th>
              <!--<th width="38%">Branches</th>-->
              <th width="10%">&nbsp;</th>
            </tr>
            </thead>         
		<?php foreach ($month_holidays as $h):
        $selected_branches = G_Company_Branch_Finder::findByHoliday($h);
        ?>    
            <tr>
              <td><?php echo $h->getDay();?></td>
              <td><strong><?php echo $h->getTitle();?></strong></td>
              <td><?php echo $h->getTypeName();?></td>
              <!--<td><?php
                $branch = array();
                if (count($selected_branches) == $total_branches) {
                    echo 'All branches';
                } else {				
                    foreach ($selected_branches as $b) {
                        $branch[] = $b->getName();
                    }
                    echo implode(', ', $branch);
                }
              ?></td>-->
              <td>
                <a class="link_option btn-mini" href="javascript:void(0)" onclick="javascript:deleteHoliday(<?php echo $h->getId();?>)" style="float:right"><i class="icon-trash"><span title="Delete" class="tooltip"></span></i></a>&nbsp;
                <a class="link_option btn-mini" href="javascript:void(0)" onclick="javascript:editHolidayFromList(<?php echo $h->getId();?>)" style="float:right"><i class="icon-pencil"><span title="Edit" class="tooltip"></span></i></a>
              </td>
            </tr>  
        <?php endforeach;?>
          </tbody>
        </table>            
        </div>
    </div>
    <?php if (fmod($i,2)){ echo "<div class='clear'></div>";} ?>
    <?php $i++; ?>
<?php endforeach;?>
</div>
<script language="javascript">
var x = jQuery.noConflict();
x('.tooltip').tipsy({gravity: 's'});
</script>