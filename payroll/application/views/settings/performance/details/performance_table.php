<h2>Performance Template</h2>
<div class="sectionarea">
<div id="form_main" class="employee_form">
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Title</td>
                <td><?php echo $details->title; ?></td>
            </tr>
            <tr>
                <td class="field_label">Job:</td>
                <td><?php echo $details->job_name; ?></td>
            </tr>
            <tr>
                <td class="field_label">Description</td>
                <td><?php echo $details->description; ?></td>
            </tr>
            <tr>
                <td class="field_label">Created by:</td>
                <td><?php echo $details->created_by; ?></td>
            </tr>
            <tr>
                <td class="field_label">Date Created:</td>
                <td><?php echo Date::convertDateIntIntoDateString($details->date_created); ?></td>
            </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><a onclick="javascript:loadPerformanceDetailsForm();" class="edit_button" href="#performance_details_table_wrapper"><strong></strong>Edit Details</a></td>
            </tr>
        </table>
    </div>
</div>
</div><!-- .sectionarea -->
