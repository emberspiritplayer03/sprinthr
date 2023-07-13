<h2 class="field_title"><?php echo $title_contribution; ?></h2>

<?php include 'form/contribution_edit.php'; ?>

<div id="contribution_table_wrapper">
<table width="515" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="30%" scope="col">Contribution/Benefits</th>
          <th width="35%" scope="col">EE</th>
          <th width="35%" scope="col">ER</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="30%" align="center">SSS</td>
          <td width="35%"><?php echo $c->sss_ee; ?></td>
          <td width="35%"><?php echo $c->sss_er; ?></td>
        </tr>
        <tr>
          <td width="30%" align="center">PHIC</td>
          <td width="35%"><?php echo $c->philhealth_ee; ?></td>
          <td width="35%"><?php echo $c->philhealth_ee; ?></td>
        </tr>
        <tr>
          <td width="30%" align="center">HDMF</td>
          <td width="35%"><?php echo $c->pagibig_ee; ?></td>
          <td width="35%"><?php echo $c->pagibig_er; ?></td>
        </tr>
        <tr class="form_action_section">
          <td width="30%">&nbsp;</td>
          <td class="action_section" colspan="2"><a class="edit_button" id="contribution_button_wrapper" href="javascript:loadContributionForm();"><strong></strong>Edit Details</a></td>
        </tr>
      </tbody>
    </table>
</div>