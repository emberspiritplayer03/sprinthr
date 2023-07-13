<table width="52%" bgcolor="#ffffff" border="0" align="center" cellpadding="6" cellspacing="1">
  <thead>
    <tr>
      <td width="40%" bgcolor="#CCCCCC">Employee Name</td>
      <td width="20%" bgcolor="#CCCCCC" style="text-align:center;">Type (In or Out)</td>
      <td width="25%" bgcolor="#CCCCCC">Time and Date</td>
      <td width="25%" bgcolor="#CCCCCC">Project Site</td>
      <td width="30%" bgcolor="#CCCCCC">Activity Name</td>
    </tr>
  </thead>
  <tbody>
    <?php
    $counter = 1;
    foreach ($records as $r) : 
      $exit_for_dtr = 0;
      $date = $r->getDate();
      if ($date == date('Y-m-d', strtotime('today'))) {
        $date_string = 'Today';
      } else {
        $date_string = Tools::convertDateFormat($date);
      }
      ?>
      <?php if ($counter == 1) : ?>
        <tr>
          <td><?php echo $r->getEmployeeName(); ?></td>
          <td style="text-align:center;"><?php echo strtoupper($r->getType()); ?></td>
          <td><?php echo date('g:i a', strtotime($r->getTime())); ?> - <?php echo $date_string; ?></td>
          <td>
            <?php 
              foreach($records_dtr as $dtr){
                if ($dtr->getTime() == $r->getTime() && $dtr->getDate() == $date && $dtr->getType() == $r->getType() && $dtr->getEmployeeId() == $r->getEmployeeId() && $exit_for_dtr == 0) {
                  $project_name = G_Project_Site_Finder::findById($dtr->getProjectSiteId());
                  echo $project_name->projectname;
                  $exit_for_dtr = 1;
               
            ?>
          </td>
          <td>
            <?php 
                  $activity_name = G_Activity_Skills_Finder::findById($dtr->getActivityName());
                  echo $activity_name->activity_skills_name;
                }
              }
            ?>
          </td>
        </tr>
      <?php else : ?>
        <tr>
          <td><?php echo $r->getEmployeeName(); ?></td>
          <td style="text-align:center;"><?php echo strtoupper($r->getType()); ?></td>
          <td><?php echo date('g:i a', strtotime($r->getTime())); ?> - <?php echo $date_string; ?></td>
          <td>
            <?php 
              foreach($records_dtr as $dtr){
                if ($dtr->getTime() == $r->getTime() && $dtr->getDate() == $date && $dtr->getType() == $r->getType() && $dtr->getEmployeeId() == $r->getEmployeeId() && $exit_for_dtr == 0) {
                  $project_name = G_Project_Site_Finder::findById($dtr->getProjectSiteId());
                  echo $project_name->projectname;
                  $exit_for_dtr = 1;
            ?>
          </td>
          <td>
            <?php 
                  $activity_name = G_Activity_Skills_Finder::findById($dtr->getActivityName());
                  echo $activity_name->activity_skills_name;
                }
              }
            ?>
          </td>
        </tr>
      <?php endif; ?>
      <?php $counter++; ?>
      <?php
      $date_string = '';
      $date = '';
      ?>
    <?php endforeach; ?>
  </tbody>
</table>