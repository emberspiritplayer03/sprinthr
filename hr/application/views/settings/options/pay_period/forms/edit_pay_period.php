<script>
$(function(){
  $("#editPayPeriod").validationEngine({scroll:false});

  $('#editPayPeriod').ajaxForm({
      success:function(o) {
          if (o.is_success) {        
              dialogOkBox(o.message,{});                                  
              load_pay_period_dt();

              var $dialog = $('#action_form');                    
              $dialog.dialog("destroy");                    

          } else {                            
              dialogOkBox(o.message,{});          
          }                   
      },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });

  $('#first_cutoff_a').change(function(){
    var start_day = $('#first_cutoff_a').val();
    //need code improvements
    if(start_day == "Sunday"){
      $("#first_cutoff_b").val('Saturday');
    }else if(start_day == "Monday"){
      $("#first_cutoff_b").val('Sunday');
    }
    else if(start_day == "Tuesday"){
      $("#first_cutoff_b").val('Monday');  
    }
    else if(start_day == "Wednesday"){
      $("#first_cutoff_b").val('Tuesday');
    }
    else if(start_day == "Thursday"){
      $("#first_cutoff_b").val('Wednesday');
    }
    else if(start_day == "Friday"){
      $("#first_cutoff_b").val('Thursday');
    }
    else if(start_day == "Saturday"){
      $("#first_cutoff_b").val('Friday');
    }


  });


});
</script>
<div id="form_main" class="inner_form popup_form wider">
    <form name="editPayPeriod" id="editPayPeriod" method="post" action="<?php echo $action_pay_period; ?>">  
    <input type="hidden" value="<?php echo $pp->getId() ?>" id="pay_period_id" name="pay_period_id" />    
    <div id="form_default"> 
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Pay Period Code:</td>
            <td>
                <input type="text" readonly="readonly" value="<?php echo $pp->getPayPeriodCode(); ?>" name="pay_period_code" class="validate[required] text" id="pay_period_code" />    
            </td>
        </tr>  
        <tr>
            <td class="field_label">Pay Period Name:</td>
            <td>
                <input type="text" value="<?php echo $pp->getPayPeriodName(); ?>" name="pay_period_name" class="validate[required] text" id="pay_period_name" />    
            </td>
        </tr>
       
          <?php if($pp->getPayPeriodCode()=="BMO"){ ?>
          <tr>
          <td class="field_label">1st Cut Off:</td>
          <td><input style="width:10%;" type="text" value="<?php echo $first_cutoff[0]; ?>" name="first_cutoff_a" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_a" /> to <input style="width:10%;" type="text" value="<?php echo $first_cutoff[1]; ?>" name="first_cutoff_b" class="alidate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_b" />
            <input style="width:51%;" type="text" placeholder="Pay Day: Day number" value="<?php echo $payoutday[0]; ?>" name="first_cutoff_payday" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_payday" />

          </td>
        </tr>        
        <tr>
          <td class="field_label">2nd Cut Off:</td>
          <td><input style="width:10%;" type="text" value="<?php echo $second_cutoff[0]; ?>" name="second_cutoff_a" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_a" /> to <input style="width:10%;" type="text" value="<?php echo $second_cutoff[1]; ?>" name="second_cutoff_b" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_b" />
            <input style="width:51%;" type="text" placeholder="Pay Day: Day number" value="<?php echo $payoutday[1]; ?>" name="second_cutoff_payday" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_payday" />
            <input type="hidden" name="frequency" value="1">
          </td>
        </tr>  



          <!--monthly-->

           <?php } else if($pp->getPayPeriodCode()=="MONTHLY"){ ?>
          <tr>
          <td class="field_label"> Cut Off:</td>
          <td><input style="width:10%;" type="text" value="<?php echo $first_cutoff[0]; ?>" name="first_cutoff_a" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_a" /> to <input style="width:10%;" type="text" value="<?php echo $first_cutoff[1]; ?>" name="first_cutoff_b" class="alidate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_b" />
            <input style="width:51%;" type="text" placeholder="Pay Day: Day number" value="<?php echo $payoutday[0]; ?>" name="first_cutoff_payday" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_payday" />


             <input type="hidden" name="frequency" value="3">
          </td>
        </tr>        
        <tr>
         
        
          <?php
          } else{ ?>

          <tr>
            <td >
               
          
            </td>
          </tr>
            <tr>
          <td class="field_label">Cutoff Period</td>
          <td>
             <select style="width:45%;" id="first_cutoff_a" name="first_cutoff_a" onchange="">
              <?php
                foreach ($days_a_week as  $value) {
                  ?>
                  <option <?php if(strtolower(trim($value," "))  == strtolower(trim($first_cutoff[0]," "))) { echo 'selected = "selected'; }else { echo "waahh"; } ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                 <?php
                }
               ?>
               </select> 
            <!-- <input style="width:25%;" type="text" value="<?php echo $first_cutoff[0]; ?>" name="first_cutoff_a" class="validate[required] text" id="first_cutoff_a" />  -->

            to

            
            <input style="width:30%;" type="text" value="<?php echo $first_cutoff[1]; ?>" name="first_cutoff_b" class="validate[required] text" id="first_cutoff_b" readonly />
            

          </td>
          
        </tr> 
        <tr>
          <td class="field_label"> </td>
          <td>
            <select style="width:45%;"  id="first_cutoff_payday" name="first_cutoff_payday">
              <?php
                foreach ($days_a_week as $value) {
                  ?>
                  <option <?php if(strtolower(trim($value," "))  == strtolower(trim($payoutday[0]," "))) { echo 'selected = "selected'; }else { echo "waahh"; } ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                 <?php
                }
               ?>
               </select>


           <!--  <input style="width:62%;" type="text" placeholder="Pay Day" value="<?php echo $payoutday[0]; ?>" name="first_cutoff_payday" class="validate[required] text" id="first_cutoff_payday" /> -->
          </td>
          <input type="hidden" name="frequency" value="2">
        </tr>

        
          <?php } ?>
        
       
             
        <!-- <tr>
          <td class="field_label">Is Default:</td>
          <td>
            <select id="is_default" name="is_default" style="width:39%;">
                <option <?php echo( $pp->getIsDefault() == 0 ? 'selected = "selected"' : "" ); ?> value="0">No</option>
                <option <?php echo( $pp->getIsDefault() == G_Settings_Pay_Period::IS_DEFAULT ? 'selected = "selected"' : "" ); ?> value="<?php echo G_Settings_Pay_Period::IS_DEFAULT; ?>">Yes</option>
            </select>
          </td>
        </tr> -->    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" class="blue_button" value="Save" /></td>
        </tr>          
    </table>
    </div>
    </form>
</div>