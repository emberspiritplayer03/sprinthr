<?php if ($employees):?>
    <table id="box-table-a" summary="Most Favorite Movies" style="margin:0px">
      <thead>
      	<tr>
        	<th width="150"><strong>Employee Code</strong></th>
            <th><strong>Employee Name</strong></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($employees as $e):?>
        <tr>
          <td width="150"><?php echo $e->getEmployeeCode();?></td>
          <td><a href="<?php echo url('project_site/show_attendance?employee_id='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash());?>"><?php echo $e->getLastname();?>, <?php echo $e->getFirstname();?></a></td>
        </tr>
      <?php endforeach;?> 
      </tbody>
    </table>
<?php else:?>
No Records Found
<?php endif;?>