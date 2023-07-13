<h3 class="field_title"><?php echo $depedents_title; ?></h3>
<div id="dependent_table_wrapper">
<table>
      <thead>
        <tr>
          <th  valign="middle" scope="col">Name</th>
          <th  valign="middle" scope="col">Relationship</th>
          <th  align="left" valign="middle" scope="col">Birthdate</th>
        </tr>
      </thead>
      <tbody>
      <?php 
      $ctr = 0;
       foreach($dependents as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top"><?php echo $e->name; ?></a></td>
          <td align="left" valign="top"><?php echo $e->relationship; ?></td>
          <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->birthdate); ?></td>
        </tr>
       <?php 
       $ctr++;
       }

      if($ctr==0) { ?>
          <tr>
          <td colspan="3" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
        </tr> 
        <?php }  ?>
      </tbody>
    </table>
</div>