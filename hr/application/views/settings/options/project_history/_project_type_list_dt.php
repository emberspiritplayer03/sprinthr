<script type="text/javascript">
    var configLeave = {
            
             "aoColumns": [
                    { sTitle: "Project Site Name"  ,mData: 'name' } ,
                    { sTitle: "Project Site Address",mData: 'location' } ,
                    { sTitle: "Project Site Description"  ,mData: 'description' } ,
                    { sTitle: "Device Id"  ,mData: 'device_id' }
                   
                 ],
            "aoColumnDefs": [{ "bSortable": false, "aTargets": [0,1,2,3,4]}],
            "bProcessing":true,
            "sAjaxSource":'_load_project_site_leave_type_list_dt',
            "bAutoWidth": false,
            "bInfo":true,
            "bFilter": true,
            "bJQueryUI": true,
            "aaSorting": [[ 1, "asc" ]],    
            "sPaginationType": "full_numbers",
            "bPaginate": true

    }
    
    var ProjectTable = $('#ProjectTable').dataTable(configLeave);

    

</script>
<br />
<div id="terms"></div>
<h2>LIST OF PROJECT SITE</strong></h2>
<br>
<div class="table" >
    <table id="ProjectTable" class="display">
        <thead>
            <tr>
                <th valign="top">Project Site Name</th>
                <th valign="top">Project Site Location</th>
                <th valign="top">Project Site Description</th>
                <th valign="top">Device Id</th>
                <th valign="top"></th>
            </tr>
        </thead>
             <tbody><tbody>
    </table>
</div>
