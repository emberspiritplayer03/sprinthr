<style>
.textboxlist{display:inline-block;width:76%;margin-right: 5px;}
.textboxlist-bits{height:28px;width: 100%;}
</style>
<script>
$(function(){  
  var t = new $.TextboxList('#employee_id', {
    unique: true,
    plugins: {
      autocomplete: {
        minLength: 2,       
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'autocomplete/ajax_get_active_employees'}
      }
  }});
    $(".btn-search-overtime").click(function(){
    var eids = $("#employee_id").val();   
    //window.location = location.href + "&eids=" + eids;  
        if( eids != "" ){  
            var current_url = location.href;
            var new_url     = removeParam("eids",current_url);
            window.location = new_url + "&eids=" + eids;
        }else{
            //dialogOkBox("Please enter employee name(s) to find",{});
            //Will display all records
            var current_url = location.href;
            var new_url     = removeParam("eids",current_url);
            window.location = new_url;
        }
    });

    function removeParam(key, sourceURL) {
        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            rtn = rtn + "?" + params_arr.join("&");
        }
        return rtn;
    }
});
</script>
<form id="withSelectedAction" name="withSelectedAction" method="post">
    <input type="hidden" name="action" id="action">   
    <div class="input-prepend">
      <span class="add-on">Search Employee :</span>
      <input class="span2" id="employee_id" type="text">
      <a class="btn btn-small btn-search-overtime" href="javascript:void(0);">Search</a>      
    </div>

    <table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
        <thead>
        <tr>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                <th width="1" scope="col"><input id="check_uncheck" onclick="chkUnchk();" type="checkbox"></th>
            <?php } ?>
            <th width="45" scope="col">Employee</th>
            <th width="30" scope="col">Date</th>
            <th width="50" scope="col">Overtime</th>
            <th width="50" scope="col">Reason</th>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                <th width="170" scope="col">Action</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php if ($has_overtime):?>
        <?php foreach ($overtime as $o):?>
            <input type="hidden" name="date[]" value="<?php echo $o->getDate();?>" >
            <input type="hidden" name="overtime_id[]" value="<?php echo Utilities::encrypt($o->getId());?>" >
            <tr>
                 <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                    <td><input class="validate[required]" id="<?php echo Utilities::encrypt($o->getId());?>" value="<?php echo Utilities::encrypt($o->getEmployeeId());?>" name="dtChk[]" onclick="enableDisableWithSelected()" type="checkbox"></td>
                <?php } ?>  
                <td><?php echo G_Employee_Helper::getEmployeeNameWithCodeById($o->getEmployeeId());?></td>
                <td><?php echo Tools::convertDateFormat($o->getDate());?></td>
                <td><?php echo Tools::timeFormat($o->getTimeIn());?> - <?php echo Tools::timeFormat($o->getTimeOut());?></td>
                <td> <?php echo $o->getReason(); ?> </td>
                <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                    <td><?php echo G_Overtime_Helper::getOvertimeActionString($o);?></td>
                <?php } ?>
            </tr>
        <?php endforeach;?>
        <?php else:?>
            <tr>
                <td colspan="5" style="text-align: center"><i>No records found.</i></td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</form>

<br>
<div style="text-align: center"><?php echo $pager_links;?></div>
