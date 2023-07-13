

<form method="post" name="edit_evaluation_form" id="edit_evaluation_form" action="<?php echo url('evaluation/add_employee_evaluation_notif');?>" enctype="multipart/form-data">	
<input type="hidden" id="eid" name="eid" value="<?php echo $eid; ?>" />
<div id="form_main" class="inner_form popup_form">
	<div id="form_default">
          <table class="no_border" width="100%">
        <tr>
         <td><label>Employee Name:</label></td>
         <td> <input type="text"  readonly="readonly" value="<?php echo $emp; ?>" /> 
              <input type="hidden" name="employee_id" id="employee_id" value="<?php echo Utilities::encrypt($eval->getEmployeeId()); ?>">
              <input type="hidden" name="eval_id" id="eval_id" value="<?php echo $eval->getId(); ?>">
        </td>
        </tr>
         <tr>
         <td><label>Evaluation Date:</label></td>
         <td> <input class="validate[required]" type="text" name="evaldate" id="evaldate" value="<?php echo date('Y-m-d', strtotime('now')); ?>" readonly="readonly" /> </td>
        </tr>
         <tr>
         <td><label>Score:</label></td>
         <td> <input class="validate[required]" type="number" step=".01" name="score" id="score" value="" /> </td>
        </tr>
         <tr>
         <td><label>Next Evaluation Date:</label></td>
         <td> <input class="validate[required]" type="text" name="nextevaldate" id="nextevaldate" value="" /> </td>
        </tr>  


        <tr>
         <td><label>Attachments:</label></td>
    	 <td><input  type="file" name="attachments" id="attachments"  />
            
         </td>
        </tr>

        </table>     
    </div>
    
    <div id="form_default" class="form_action_section" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td style="text-align: right">
            	<input type="submit" value="Submit" class="curve blue_button" />            	
            	<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_evaluation_form');">Cancel</a>
            </td>
          </tr>
        </table>		
    </div>
</div>
</form>

<script type="text/javascript">

$('#edit_evaluation_form').validationEngine({scroll:false}); 


/*
 var t = new $.TextboxList('#employee_id', {max:1,plugins: {
      autocomplete: {
        minLength: 2,
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + 'evaluation/ajax_get_employees_autocomplete'}
      
      }
  }});*/


  


//$("#edit_evaluation_form #evaldate ").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#edit_evaluation_form #nextevaldate ").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

</script>