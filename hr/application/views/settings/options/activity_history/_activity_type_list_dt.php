<script type="text/javascript">
    var configLeave = {
            
             "aoColumns": [
                    { sTitle: "Name",mData: 'name' } ,
                    { sTitle: "Description",mData: 'location' } ,
                    { sTitle: "Start",mData: 'date_start' } ,
                    { sTitle: "End",mData: 'date_end' } ,
                    { sTitle: ""  ,mData: '' }
                 ],
            "aoColumnDefs": [{ "bSortable": false, "aTargets": [0,1,2,3]}],
            "bProcessing":true,
            "sAjaxSource":'_load_activity_leave_type_list_dt',
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
<h2>LIST OF ACTIVITIES</strong></h2>
<br>
<div class="table" >
    <table id="ProjectTable" class="display">
   
    </table>
</div>
