<!--<table width="1010" id="hor-minimalist-b"  border="0">-->
<table id="hor-minimalist-b" width="100%"  border="0">
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
       <tr id="question_edit_form_dialog_<?php echo $e->id; ?>" style="display:none;" onmouseout="javascript:hideDelete('<?php echo $e->id; ?>');" onmouseover="javascript:displayDelete('<?php echo $e->id; ?>');">
       <td colspan="4">
           <div id="form_main" class="popup_form">
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
                <form class="question_edit_form" id="question_edit_form_<?php echo $e->id; ?>" name="form1" method="post" action="<?php echo url('settings/_edit_question'); ?>">
                <input type="hidden" name="id" value="<?php echo $e->id; ?>" />
                <input type="hidden" name="examination_id" value="<?php echo $e->exam_id; ?>" />
                <input type="hidden" name="order_by" value="<?php echo $e->order_by; ?>" />
                <div id="form_default">
                  <table class="" width="100%" border="0" cellpadding="3" cellspacing="3">
                     <tr>
                      <td width="0" align="right" valign="top" class="field_label">Question:</td>
                      <td valign="top"><textarea name="question" id="question" class="validate[required]" style="min-width:250px;"><?php echo $e->question; ?></textarea></td>
                    </tr>
                    <tr>
                      <td width="0" align="right" valign="top" class="field_label">Answer:</td>
                      <td width="0" valign="top"><textarea name="answer" style="min-width:250px;" id="answer"><?php echo $e->answer; ?></textarea></td>
                    </tr>
                    <tr>
                      <td width="0" align="right" valign="top" class="field_label">Type:</td>
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
                      </select>
                      <table id="quick_choices_table" width="100%" border="1" style="display:none;">
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
                      </table>
                      </td>
                    </tr>
                    <tr>
                        <td class="field_label">&nbsp;</td>
                        <td><input type="submit" class="blue_button" name="button" id="button" value="Update" /></td>
                    </tr>
                    </table>
                </div><!-- #form_default.form_action_section -->
                </form>
            </div>
        </td>
        </tr>
        <tr class="form_table_inner" id="question_table_wrapper_<?php echo $e->id; ?>"  onmouseout="javascript:hideDelete('<?php echo $e->id; ?>');" onmouseover="javascript:displayDelete('<?php echo $e->id; ?>');">
          <td><?php echo $numbering; ?>)&nbsp;<a href="javascript:void(0);" onclick="javascript:displayQuestionEditForm('<?php echo $e->id; ?>');"><b class="question_item"><?php echo htmlentities($e->question); ?></b></a></td>
          <td><b><?php echo htmlentities($e->answer); ?></b></td>
          <td><?php echo $e->type; ?></td>
          <td style="height:22px; min-width:50px;">
          <label class="delete_question_nav" id="<?php echo $e->id; ?>">           
          <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:displayQuestionEditForm(<?php echo $e->id; ?>)" title="Edit"><i class="icon-pencil"></i></a>
          <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:loadQuestionDeleteDialog(<?php echo $e->id; ?>,<?php echo $e->exam_id; ?>);" title="Delete"><span class="icon-trash"></span></a>
          <?php if($numbering!=1) { ?>
          <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:moveQuestionUp(<?php echo $e->id; ?>,<?php echo $e->exam_id; ?>)" title="Move Up"><i class="icon-arrow-up"></i></a>
          <?php } ?>
          </label></td>
        </tr>
        <?php if($e->type=='choices') { ?>
         <tr class="form_table_choices_main">
		    <td colspan="4">
            <div class="choices_holder" id="choices_wrapper_<?php echo $e->id; ?>">
            <table width="100%" border="0" align="left" class="form_table_choices">
             <tr>
		        <td><input name="choice_<?php echo $e->id; ?>" type="text" id="choice_<?php echo $e->id; ?>" /> 
		          <a class="btn btn-small" href="javascript:void(0);" onclick="javascript:addChoice(<?php echo $e->id; ?>);" title="Add Choice"><i class="icon-plus"></i> Add Choice</a></td>
	          </tr>
				<?php 
                $choices = G_Exam_Choices_Finder::findByQuestionId($e->id);
                $num=0;
                foreach($choices as $key=>$value) {
                ?>
		      <tr class="list_choices">
		        <td style="min-height:22px;" onmouseout="javascript:hideChoiceDelete(<?php echo $value->id; ?>);" onmouseover="javascript:displayChoiceDelete(<?php echo $value->id; ?>);">
				<?php echo Tools::change_to_letters($num); ?>)&nbsp;&nbsp;<?php echo htmlentities($value->choices); ?> 
                <label id="option_<?php echo $value->id; ?>" class="delete_choice_nav" style="float:right;">
                  <?php if(strtolower($e->answer)!=strtolower($value->choices)) { ?>
      			 <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:loadChoiceDeleteDialog(<?php echo $value->id; ?>,<?php echo $e->id; ?>);" title="Delete"><span class="icon-trash"></span></a>
               	<?php }else {
					echo "<font color='gray'>Unable to delete</font>";
				} ?>
                <?php if($num!=0) { ?>
                  <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:moveChoiceUp(<?php echo $value->id; ?>,<?php echo $e->id; ?>)" title="Move Up"><i class="icon-arrow-up"></i></a>
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
		  <tr class="form_table_inner">
          <td colspan="4"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
<script>
$(".delete_question_nav").hide();
$(".delete_choice_nav").hide();
</script>
