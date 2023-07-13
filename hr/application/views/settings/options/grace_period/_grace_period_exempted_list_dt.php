<br>

<table class="formtable">
<thead>
    <tr>
        <th width="45%">Employee Code</th>
        <th width="30%">Employee Name</th>
        <!--<th width="25%">Is Default</th>-->
    </tr>
</thead>
<?php

if(!empty($record)){
 foreach ($record as $d => $value){
?>
<tr>
    <td><?php echo $value['employee_code']; ?></td>
    <td>
		<?php echo $value['lastname'].', '.$value['firstname'].' '.$value['middlename'];?>        
              
         <a class="link_option"  href="javascript:archiveGracePeriodExempted('<?php echo Utilities::encrypt($value['id']); ?>');"><i class="icon-edit"></i> Delete</a>
    </td>
</tr>
<?php } 

}else{
?>
<tr>
    <td colspan="2">- No Records Found. -</td>
</tr>
<?php
} ?> 
</table>