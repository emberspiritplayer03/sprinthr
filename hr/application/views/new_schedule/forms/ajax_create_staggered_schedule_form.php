<style>
    .date_input {
        width: 44% !important;
    }
</style>

<form id="add_schedule_form" method="post" action="<?php echo $action; ?>">
    <div id="form_main" class="inner_form popup_form wider">
        <div id="form_default">
            <table class="no_border" width="100%">
                <tr>
                    <td width="24%" class="field_label">*Schedule Name:</td>
                    <td width="76%"><input class="validate[required] text-input" type="text" name="name" id="name" value="" /></td>
                </tr>
                <tr>
                    <td class="field_label">*Required hours:</td>
                    <td><input class="validate[required]" type="number" name="hours" id="" value="" /></td>
                </tr> 
            </table>
        </div>

        <span id="schedule_message"></span>
        <div id="form_default" class="form_action_section">
            <table class="no_border" width="100%">
                <tr>
                    <td class="field_label">&nbsp;</td>
                    <td>
                        <input value="Save" id="add_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>