<h3 class="section_title"><span>Application Information</span></h3>
  <div id="form_default">            
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="field_label">Applying for Position: </td>
      <td>
      <em class="note label"><?php echo $j->getJobTitle(); ?></em></td>
    </tr>
    <tr>
      <td class="field_label">Date Applied: </td>
      <td><input type="text" disabled="disabled" class="validate[required] text-input text" value="<?php echo date('Y-m-d'); ?>" name="date_applied" id="date_applied" /></td>
    </tr>
    <tr>
      <td valign="top" class="field_label">Email Address:</td>
      <td><input type="text" disabled="disabled" class="validate[required,custom[email]]" name="email_address" id="email_address" value="<?php echo $a->getEmail(); ?>"></td>
    </tr>
    <!-- <tr>
      <td valign="top" class="field_label">Resume:</td>
      <td>
      	<input type="file" name="filename" id="filename" />
          <div id="uploaded_file_wrapper" style="display:none;">
          <br />
          	<strong><i><span id="filename_wrapper"></span></i></strong> <a href="javascript:void(0);" onclick="javascript:remove_file();">Remove</a>
              <input type="hidden" id="directory_name" name="directory_name" value="" />
              <input type="hidden" id="upload_filename" name="upload_filename" value="" />
          </div>
      </td>
    </tr> -->
    <tr>
      <td colspan="2" valign="top" class="field_label">&nbsp;</td>
    </tr>
    </table>
  </div>
  <div class="form_separator"></div>