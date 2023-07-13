<div class="dataTables_wrapper">
    <div class="ui-toolbar">
    <strong>Request:</strong>&nbsp;&nbsp;
    <select size="" id="approved_request" name="approved_request" onchange="javascript:load_approved_request();">
        <option value=""> -- Select -- </option>
        <option value="1"><?php echo Settings_Request::OT ?></option>
        <option value="2"><?php echo Settings_Request::LEAVE ?></option>
    </select>
    </div>
    <div id="recent_approved_request_list_wrapper">
    <table width="100%" class="formtable">
    <thead>
        <tr>
            <th class="bold" width="60%">Employee Name</th>
            <th class="bold" width="30%">Date</th>
            <th class="bold" width="100">Type</th>
        </tr>
    </thead>
    <tbody>
        <tr class="odd">
            <td colspan="3" class="reminder_title">No result(s) found</td>
        </tr>
    </tbody>
    </table>
    </div>
</div>
