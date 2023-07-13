<style>
.qry-options{width:20% !important;margin-left:8px;}
.qry-inputs{width:30%;height:21px;margin-left:8px;}
.btn-remove-other-detail{margin-left:10px;}
.qry-title{background-color: #e3e3e3; padding-left: 11px;margin:22px 4px 17px; width: 100%;font-size: 15px;}
.btn-add-qry{margin-top: 7px;margin-right:7px;}
.rep-checkbox-container{margin-left:8px;}
.rep-checkbox-container .checkbox{margin-right:3px;}
</style>
<h2><?php echo $title;?></h2>
<form id="yearly_bonus_form" name="yearly_bonus_form" method="post" action="<?php echo $action; ?>">
<div id="form_main" class="employee_form">
  <div id="form_default">
      <table width="100%">      
        <tr>
            <td>Select Year</td>
            <td class="form-inline">: 
                <select id="year_payroll" name="year_payroll">
                    <?php for($x = $start_year; $x<=date("Y"); $x++){ ?>
                      <option value="<?php echo $x ?>"><?php echo $x; ?></option>
                    <?php } ?>
                </select><br />                
            </td>
        </tr>
        <?php if($is_with_confi_nonconfi_option){ ?>
          <tr>
              <td>Employee Type</td>
              <td>: 
                  <select name="yearly_bonus_q">
                      <option selected="selected" value="both">Both</option>
                      <option value="confidential">Confidential</option>
                      <option value="non-confidential">Non-Confidential</option>
                  </select>                
              </td>
          </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td class="form-inline">                
                <div class="rep-checkbox-container">
                  <label class="checkbox"><input type="checkbox" name="yearly_bonus_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                  <label class="checkbox"><input type="checkbox" name="yearly_bonus_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                  <label class="checkbox"><input type="checkbox" name="yearly_bonus_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                  <label class="checkbox"><input type="checkbox" name="yearly_bonus_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                </div>
            </td>
        </tr>
      </table>
  </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
      <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
