<table width="1010" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="516" scope="col">Question</th>
          <th width="218" scope="col">Answer</th>
          <th width="77" scope="col">Type</th>
          <th width="181" scope="col">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
       
      <?php 
	  $ctr = 0;
	  $numbering=0;
	   foreach($questions as $key=>$e) { 
	   $numbering++;
	   ?>
       <tr  onmouseout="javascript:hideDelete('<?php echo $e->id; ?>');" onmouseover="javascript:displayDelete('<?php echo $e->id; ?>');">
          <td colspan="4">
   
		 <script>
            $(function() {
            $("#question_edit_form_<?php echo $e->id; ?>").validationEngine({scroll:false});
            $("#question_edit_form_<?php echo $e->id; ?>").ajaxForm({
                success:function(o) {
                    if(o==1) {
                        dialogOkBox('Successfully Updated',{});
                        loadExamQuestions(<?php echo $e->exam_id; ?>);
                        
                    }else {
                        dialogOkBox(o,{});	
                    }		
                },
                beforeSubmit:function() {
                    showLoadingDialog('Saving...');	
                }
            });
            
            });
            </script>
            
            <div id="question_edit_form_dialog_<?php echo $e->id; ?>" style="display:none">
            <form class="question_edit_form" id="question_edit_form_<?php echo $e->id; ?>" name="form1" method="post" action="<?php echo url('settings/_edit_question'); ?>">
            <input type="hidden" name="id" value="<?php echo $e->id; ?>" />
            <input type="hidden" name="examination_id" value="<?php echo $e->exam_id; ?>" />
            <input type="hidden" name="order_by" value="<?php echo $e->order_by; ?>" />
            
              <table class="" width="400" border="0" cellpadding="3" cellspacing="3">
                 <tr>
                  <td width="0" align="right" valign="top">Question:</td>
                  <td valign="top"><textarea name="question" id="question" cols="45" class="validate[required]" rows="5"><?php echo $e->question; ?></textarea></td>
                </tr>
                <tr>
                  <td width="0" align="right" valign="top">Type:</td>
                  <td valign="top"><select onchange="javascript:loadQuickChoicesForm();" class="validate[required]" name="type" id="type">
                  <?php 
                  $essay = '';
                  $blank ='';
                  $choices= '';
                  if($e->type=='essay') {
                        $essay = 'selected="selected"';  	
                    }else if($e->type=='blank') {
                        $blank = 'selected="selected"';  	
                    }else if($e->type=='choices') {
                        $choices = 'selected="selected"';  	
                    }
                  ?>
                    <option value="">-- select type --</option>
                    <option <?php echo $choices; ?> value="choices">Multiple Choice</option>
                    <option <?php echo $essay; ?> value="essay">Essay</option>
                    <option <?php echo $blank; ?> value="blank">Blank</option>
                  </select></td>
                </tr>
                <tr>
                  <td width="0" align="right" valign="top">Answer:</td>
                  <td width="0" valign="top"><textarea name="answer" id="answer" cols="45" rows="5"><?php echo $e->answer; ?></textarea></td>
                </tr>
                <tr>
            
                  <td colspan="2" align="left" valign="top">
                  <table id="quick_choices_table" width="100%" border="1" style="display:none">
                    <tr>
                      <td colspan="2" align="center">*Please provide correct answer SAME with the answer above</td>
                      </tr>
                    <tr>
                      <td width="55%" align="right">Choice 1:</td>
                      <td width="45%"><input type="text" name="choice1" id="choice1" /></td>
                    </tr>
                    <tr>
                      <td align="right">Choice 2:</td>
                      <td><input type="text" name="choice2" id="choice2" /></td>
                    </tr>
                    <tr>
                      <td align="right">Choice 3:</td>
                      <td><input type="text" name="choice3" id="choice3" /></td>
                    </tr>
                    <tr>
                      <td align="right">Choice 4:</td>
                      <td><input type="text" name="choice4" id="choice4" /> </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="right" valign="top">&nbsp;</td>
                  <td valign="top"><input type="submit" name="button" id="button" value="Update" /></td>
                </tr>
              </table>
            </form>
            </div>   


          </td>
        </tr>
        
        <tr id="question_table_wrapper_<?php echo $e->id; ?>"  onmouseout="javascript:hideDelete('<?php echo $e->id; ?>');" onmouseover="javascript:displayDelete('<?php echo $e->id; ?>');">
          <td><bold><?php echo $numbering; ?>)</bold>&nbsp;<a href="javascript:void(0);" onclick="javascript:displayQuestionEditForm('<?php echo $e->id; ?>');"><?php echo htmlentities($e->question); ?></a></td>
          <td><?php echo htmlentities($e->answer); ?></td>
          <td><?php echo $e->type; ?></td>
          <td>
          <label class="delete_question_nav" id="<?php echo $e->id; ?>" > 
          <a href="javascript:void(0);" onclick="javascript:displayQuestionEditForm(<?php echo $e->id; ?>)">Edit</a> <a href="javascript:void(0);" onclick="javascript:loadQuestionDeleteDialog(<?php echo $e->id; ?>,<?php echo $e->exam_id; ?>);">Delete</a> 
          <?php if($numbering!=1) { ?>
          <a href="javascript:void(0);" onclick="javascript:moveQuestionUp(<?php echo $e->id; ?>,<?php echo $e->exam_id; ?>)">Move Up</a>
          <?php } ?>
          </label></td>
        </tr>
        <?php if($e->type=='choices') { ?>
         <tr>
		    <td colspan="4"><div id="choices_wrapper_<?php echo $e->id; ?>"><table width="736" border="0" align="left">
             <tr>
		        <td width="70">&nbsp;</td>
		        <td width="656"><input name="choice_<?php echo $e->id; ?>" type="text" id="choice_<?php echo $e->id; ?>" size="40" /> 
		          <a href="javascript:void(0);" onclick="javascript:addChoice(<?php echo $e->id; ?>);">Add Choice</a></td>
	          </tr>
            <?php 
			$choices = G_Exam_Choices_Finder::findByQuestionId($e->id);
			$num=0;
			foreach($choices as $key=>$value) {
			?>
		      <tr>
		        <td>&nbsp;</td>
		        <td onmouseout="javascript:hideChoiceDelete(<?php echo $value->id; ?>);" onmouseover="javascript:displayChoiceDelete(<?php echo $value->id; ?>);">
				<bold><?php echo Tools::change_to_letters($num); ?>)</bold>
				<?php echo htmlentities($value->choices); ?> 
                  <label id="option_<?php echo $value->id; ?>" class="delete_choice_nav"  >
                 <?php if(strtolower($e->answer)!=strtolower($value->choices)) { ?>
      			 <a href="javascript:void(0);" onclick="javascript:loadChoiceDeleteDialog(<?php echo $value->id; ?>,<?php echo $e->id; ?>);">Delete</a>
               	<?php }else {
					echo "<font color='gray'>Unable to delete</font>";
				} ?>
                <?php if($num!=0) { ?>
                  <a href="javascript:void(0);" onclick="javascript:moveChoiceUp(<?php echo $value->id; ?>,<?php echo $e->id; ?>)">Move Up</a>
                  <?php } ?>
                </label>
                </td>
	          </tr>
             <?php
			 $num++;
			  } ?>
	        </table>
            </div></td>
	    </tr>
        <?php } ?>

       <?php 
	   $ctr++;
	   }
	  if($ctr==0) { ?>
		  <tr>
          <td colspan="4"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
<script>
$(".delete_question_nav").hide();
$(".delete_choice_nav").hide();
</script>
