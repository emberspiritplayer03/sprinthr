<?php include('includes/employee_summary.php'); ?>
<h2 class="field_title">Contribution</h2>
<div id="contribution_table_wrapper">
<table width="515" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="40%" align="left" valign="middle" scope="col">Contribution/Benefits</th>
          <th width="30%" align="left" valign="middle" scope="col">EE</th>
          <th width="30%" align="left" valign="middle" scope="col">ER</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="40%" align="left" valign="top">SSS</td>
          <td width="30%" align="left" valign="top"><?php echo $c->sss_ee; ?></td>
          <td width="30%" align="left" valign="top"><?php echo $c->sss_er; ?></td>
        </tr>
        <tr>
          <td width="40%" align="left" valign="top">PHIC</td>
          <td width="30%" align="left" valign="top"><?php echo $c->philhealth_ee; ?></td>
          <td width="30%" align="left" valign="top"><?php echo $c->philhealth_ee; ?></td>
        </tr>
        <tr>
          <td width="40%" align="left" valign="top">HDMF</td>
          <td width="30%" align="left" valign="top"><?php echo $c->pagibig_ee; ?></td>
          <td width="30%" align="left" valign="top"><?php echo $c->pagibig_er; ?></td>
        </tr>
      </tbody>
    </table>
</div>