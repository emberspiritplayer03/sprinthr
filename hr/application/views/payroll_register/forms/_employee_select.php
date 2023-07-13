<style>
.select2-container--default{

}
</style>
<script>

$(function(){
    jq("#employee_id").select2({
        placeholder: "Select Employee",
    });

    $("#all_dept_employee").click(function(){
        if($("#all_dept_employee").is(':checked') ){
            $(".select2-container--default").css("max-height","150px");
            $(".select2-container--default").css("overflow-y","auto");
            jq("#employee_id > option").prop("selected","selected");
            jq("#employee_id").trigger("change");

        }else{
            $(".select2-container--default").css("max-height","");
            $(".select2-container--default").css("overflow-y","");
            jq("#employee_id > option").removeAttr("selected");
            jq("#employee_id").trigger("change");
         }
    });

    $("#btn-add-selected").click(function(){
        if($("#employee_id").val() != null) {
            var prev_value = $("#selected_employee_id").val();
            $("#selected_employee_id").val(prev_value + $("#employee_id").val() +",");
            var selected_employee_id = $("#selected_employee_id").val();
            $(".hide-show-tr").fadeIn(1000);

            //$("#employee_wrapper").html(loading_image);
            $("#loading-msg").html("<div id='msg-cont'>"+loading_image + " Adding Employee..</div>");
            loadSelectedEmployees(selected_employee_id);
            
        }
    });
});



</script>
<?php if(!empty($employees)) { ?>
    <select class="filter-select" id="employee_id" name="employee_id" multiple="multiple" >
        <?php foreach($employees as $key => $value) { ?>
            <option value="<?php echo Utilities::encrypt($value['id']);?>"><?php echo $value['fullname'];?></option>
        <?php } ?>
    </select> 
    <a href="javascript:void(0);" id="btn-add-selected" style="vertical-align:top;padding:5px;" class="btn"><i class="icon-plus"></i> Add Selected</a>
    <br/>
    <input type="checkbox" style="vertical-align:text-bottom;" value="1" name="all_dept_employee" id="all_dept_employee"> 
    <label style="display:inline-flex;padding:5px;" for="all_dept_employee">  All Department Employees </label>
<?php }else{ ?>
    <select class=" filter-select" name="employee_id" disabled="disabled">
        <option value="all">All</option>
    </select>
<?php } ?>
