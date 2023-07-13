<?php if($cutoff_periods) { ?>
  <select id="cutoff" name="cutoff" class="validate[required]" style="width:30%">
      <?php foreach ($cutoff_periods as $c):?>
      <option value="<?php echo $c->getStartDate();?>/<?php echo $c->getEndDate();?>"><?php echo $c->getYearTag();?> - <?php echo $c->getMonth();?> - <?php echo $c->getCutoffCharacter();?></option>
      <?php endforeach;?>
  </select>
<?php }else{ ?>
  <select id="cutoff" name="cutoff" class="validate[required]" style="width:30%">
      <option value="">- Select Cutoff Period -</option>
  </select>
<?php }?>