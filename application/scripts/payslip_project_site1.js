function updatePayslips(from, to) {
	_updatePayslips(from, to, {
		onUpdated: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		},
		onUpdating: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Updating Payslips...');
		},
		onError: function(message) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showOkDialog(o.message);
		}
	});
}

function updateEmployeePayslip(employee_id, from, to) {
	_updateEmployeePayslip(employee_id, from, to, {
		onUpdated: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			//_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
			_showPayslip('#payslip_manage', employee_id, from, to, {})
		},
		onUpdating: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Updating...');
		},
		onBeforeUpdate: function() {
			return true;
		},
		onError: function(message) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function generateAndDownloadPayslip(from, to, salt) {
	var dialog_id = '#' + MAIN_DIALOG_ID;			
	$('#'+ DIALOG_CONTENT_HANDLER).html('<div align="center" id="'+ MAIN_DIALOG_ID +'"><br><br>Generating Payslip...<br><br></div>');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Downloading...',
		width: 250,
		height: 150,
		modal: true,
		resizable: false			
	});		
	$.post(base_url + 'payslip/generate', {from:from, to:to, salt:salt},
		function(){	
			var dialog_id = '#' + MAIN_DIALOG_ID;
			var $dialog = $(dialog_id);
			$dialog.dialog('destroy');
			$('#'+ MAIN_DIALOG_ID).remove();			
			location.href = base_url + 'reports/download_payroll_register?from='+ from +'&to=' + to;
		});	
}

function generatePayslipByMonthCutoffNumberYear(month, cutoff_number, year, q,frequency) {
	location.href = base_url + 'project_site/generate_payroll_option?month='+month+'&cutoff_number='+cutoff_number+'&year='+year+'&q='+q+'&frequency='+frequency;
	/*_generatePayslipByMonthCutoffNumberYear(month, cutoff_number, year, q,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Please wait...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});	
			var query = window.location.search;
            $.get(base_url + 'payroll_register/generation'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });	
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});*/
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function generatePayslipByMonthCutoffNumberYearSUB(month, cutoff_number, year, q) {
    showYesNoDialog('Timesheets must be finalized before generating payroll. Do you want to continue?', {
        onYes: function(){
            var dialog_id = '#' + MAIN_DIALOG_ID;
            $('#'+ DIALOG_CONTENT_HANDLER).html('<div align="center" id="'+ MAIN_DIALOG_ID +'"><br><br>Generating Payroll...<br><br></div>');
            var $dialog = $(dialog_id);
            $dialog.dialog({
                title: 'Please wait...',
                width: 250,
                height: 150,
                modal: true,
                resizable: false
            });

            $.post(base_url + 'payslip/generate_payslip', {month:month, cutoff_number:cutoff_number, year:year, q:q},
                function(){
                    //$.get(base_url + 'payslip/manage', {from:from, to:to, ajax:1},
                    //	function(o){
                    var dialog_id = '#' + MAIN_DIALOG_ID;
                    var $dialog = $(dialog_id);
                    $dialog.dialog('destroy');
                    $('#'+ MAIN_DIALOG_ID).remove();

                    var query = window.location.search;
                    $.get(base_url + 'payroll_register/generation'+ query, {ajax:1}, function(html_data){
                        $('#main').html(html_data);
                    });
                });
        }
    });
}

function generatePayslip(from, to, salt) {
	showYesNoDialog('Timesheets should be finalized before generating payslip. Do you want to continue?', {
		onYes: function(){
			var dialog_id = '#' + MAIN_DIALOG_ID;			
			$('#'+ DIALOG_CONTENT_HANDLER).html('<div align="center" id="'+ MAIN_DIALOG_ID +'"><br><br>Generating Payslip...<br><br></div>');
			var $dialog = $(dialog_id);
			$dialog.dialog({
				title: 'Please wait...',
				width: 250,
				height: 150,
				modal: true,
				resizable: false			
			});	
									
			$.post(base_url + 'payslip/generate', {from:from, to:to, salt:salt},
				function(){			
					//$.get(base_url + 'payslip/manage', {from:from, to:to, ajax:1},
					//	function(o){					
							var dialog_id = '#' + MAIN_DIALOG_ID;
							var $dialog = $(dialog_id);
							$dialog.dialog('destroy');
							$('#'+ MAIN_DIALOG_ID).remove();								
							_showEmployeeList('#payslip_manage', from, to, {});
							//location.href = base_url + 'payslip/manage?from='+ from +'&to=' + to +'&salt='+ salt;
					//	});
				});
		}
	});
}

function addDeduction(employee_id, from, to) {
	_addDeduction(employee_id, from, to, {
		onAdd: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$.post(base_url + 'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to, {}, function(o) {
				$('#payslip_manage').html(o);	
			});				
		},
		onError: function(o) {
			if (o.already_exist) {
				showOkDialog('Deduction already exists');
			}
		}		
	});	
}

function _addDeduction(employee_id, from, to, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Deduction';
	var width = 350;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}
	
	$.get(base_url + 'payslip/add_deduction?ajax=1', {employee_id:employee_id, from:from, to:to},
		function(data){
			closeDialog(dialog_id);
			$(dialog_id).html(data);
			dialogGeneric(dialog_id, {
				title: title,
				resizable: false,
				width: width,
				height: height,
				modal: true,
				form_id: '#add_earning_form'
			});
			
			$('#add_deduction_form').validationEngine({scroll:false});		
			$('#add_deduction_form').ajaxForm({
				success:function(o) {
					if (o.added) {							
						if (events) {
							events.onAdd(o);	
						}
					} else {
						if (events) {
							events.onError(o);	
						}	
					}				
				},
				dataType:'json',
				beforeSubmit: function() {
					return true;
				}
			});			
		});		
/*	var dialog_id = '#' + MAIN_DIALOG_ID;
	var $dialog = $(dialog_id);
	$dialog.dialog('destroy');
	$('#'+ MAIN_DIALOG_ID).remove();
	
	$.get(base_url + 'payslip/add_deduction?ajax=1', {employee_id:employee_id, from:from, to:to},
		function(o){
			$('#'+ DIALOG_CONTENT_HANDLER).html('<div id="'+ MAIN_DIALOG_ID +'">' + o + '</div>');
			var $dialog = $(dialog_id);
			$dialog.dialog({
				title: 'Add Deduction',
				width: 350,
				height: 230,
				modal: true,
				resizable: true,
				buttons: {
					'Save': function() {
						if ($('#deduction_name').val() == '' || $('#deduction_amount').val() == '') {
							alert('Please fill out all fields');
							return;
						}
						
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {	
								if (o.added) {							
									if (events) {
										events.onAdd(o);	
									}
								} else {
									if (events) {
										events.onError(o);	
									}
								}
							},
							dataType: 'json'
						});						
						$dialog.dialog('destroy');						
						$(dialog_id).remove();
					},
					Cancel: function() {
						$dialog.dialog('destroy');
						$(dialog_id).remove();
					}
				},
				close: function() {
					$dialog.dialog('destroy');
					$(dialog_id).remove();
				}				
			});				
		});	*/	
}

function changeDeductionAmount(label, variable, amount, employee_id, from, to) {
	_changeDeductionAmount(label, variable, amount, employee_id, from, to, {
		onChanged: function(){
			//showContent('#'+ CONTENT_BODY_ID, {title:'First Handler', url:'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to});	
			$.post(base_url + 'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to, {}, function(o) {
				$('#payslip_manage').html(o);	
			});			
		}			
	});
}

/*
function _changeDeductionAmount(label, variable, amount, employee_id, from, to, events) {
	var dialog_id = '#' + MAIN_DIALOG_ID;
	var $dialog = $(dialog_id);
	$dialog.dialog('destroy');
	$('#'+ MAIN_DIALOG_ID).remove();
	
	$.get(base_url + 'payslip/change_deduction_amount?ajax=1', {employee_id:employee_id, from:from, to:to, label:label, variable:variable, amount:amount},
		function(o){
			$('#'+ DIALOG_CONTENT_HANDLER).html('<div id="'+ MAIN_DIALOG_ID +'">' + o + '</div>');
			var $dialog = $(dialog_id);
			$dialog.dialog({
				title: 'Change Amount',
				width: 320,
				height: 180,
				modal: true,
				resizable: true,
				buttons: {
					'Save': function() {
						if ($('#deduction_amount').val() == '') {
							alert('Please enter amount');
							return;
						}
						
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {	
								if (o.changed) {							
									if (events) {
										events.onChanged(o);	
									}
								}
							},
							dataType: 'json'
						});						
						$dialog.dialog('destroy');						
						$(dialog_id).remove();
					},
					Cancel: function() {
						$dialog.dialog('destroy');
						$(dialog_id).remove();
					}
				},
				close: function() {
					$dialog.dialog('destroy');
					$(dialog_id).remove();
				}				
			});				
		});		
}
*/

function _changeDeductionAmount(label, variable, amount, employee_id, from, to, events) {
	var dialog_id = '#' + MAIN_DIALOG_ID;
	var $dialog = $(dialog_id);
	$dialog.dialog('destroy');
	$('#'+ MAIN_DIALOG_ID).remove();
	
	$.get(base_url + 'payslip/change_deduction_amount?ajax=1', {employee_id:employee_id, from:from, to:to, label:label, variable:variable, amount:amount},
		function(o){
			$('#'+ DIALOG_CONTENT_HANDLER).html('<div id="'+ MAIN_DIALOG_ID +'">' + o + '</div>');
			var $dialog = $(dialog_id);			
/*			dialogGeneric(dialog_id, {
				title: 'Change Amount',
				resizable: false,
				width: 320,
				height: 180,
				modal: true,
				form_id: '#change_deduction_amount_form'
			});*/
			
			$dialog.dialog({
				title: 'Change Amount',
				width: 320,
				height: 180,
				modal: true,
				resizable: true
			});
			
			//$('#change_deduction_amount_form').validationEngine({scroll:false});		
			$('#change_deduction_amount_form').ajaxForm({
				success:function(data) {
					if (data.changed) {						
						if (events) {
							events.onChanged(data);	
						}
					}				
					$dialog.dialog('destroy');						
					$(dialog_id).remove();
										
/*					if (o.added) {													
						if (events) {
							events.onAdd(o);	
						}
					} else {
						if (events) {
							events.onError(o);	
						}	
					}	*/			
				},
				dataType:'json',
				beforeSubmit: function() {
					if ($('#deduction_amount').val() == '') {
						alert('Please enter amount');
						return false;
					}
									
					return true;
				}
			});			
		});		
}

function removeDeduction(label, employee_id, from, to) {
	showYesNoDialog('Are you sure you want to remove this deduction?', {
		onYes: function(){
			_removeDeduction(label, employee_id, from, to, {
				onRemove: function() {
					var query = window.location.search;
					$.post(base_url + 'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to, {}, function(o) {
						$('#payslip_manage').html(o);	
					});					
				}
			});	
		}
	});		
/*	var ans = confirm('Are you sure you want to remove?');
	if (ans) {
		_removeDeduction(label, employee_id, from, to, {
			onRemove: function() {
				var query = window.location.search;
				//showContent('#'+ CONTENT_BODY_ID, {title:'First Handler', url:'payslip/show_payslip' + query + '&ajax=1', param: {}});						
				$.post(base_url + 'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to, {}, function(o) {
					$('#show_payslip').html(o);	
				});				
			}
		});	
	}	*/
}

function _removeDeduction(label, employee_id, from, to, events) {
	$.post(base_url + 'payslip/_remove_deduction', {label:label, employee_id:employee_id, from:from, to:to},
		function(o) {
			if (o.removed) {
				if (events) {
					events.onRemove();
				}
			}
		}
	, 'json');	
}

function addEarning(employee_id, from, to) {
	_addEarning(employee_id, from, to, {
		onAdd: function(){
			//showContent('#'+ CONTENT_BODY_ID, {title:'First Handler', url:'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to});	
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$.post(base_url + 'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to, {}, function(o) {
				$('#payslip_manage').html(o);	
			});				
		},
		onError: function(o) {
			if (o.already_exist) {
				//alert('Deduction is already existing');	
				showOkDialog('Earning already exists');
			}
		}		
	});
}

//function _addEarning(employee_id, from, to, events) {
//	var dialog_id = '#' + MAIN_DIALOG_ID;
//	var $dialog = $(dialog_id);
//	$dialog.dialog('destroy');
//	$('#'+ MAIN_DIALOG_ID).remove();
//	
//	$.get(base_url + 'payslip/add_earning?ajax=1', {employee_id:employee_id, from:from, to:to},
//		function(o){
//			$('#'+ DIALOG_CONTENT_HANDLER).html('<div id="'+ MAIN_DIALOG_ID +'">' + o + '</div>');			
//			var $dialog = $(dialog_id);
//			$(dialog_id + ' form').validationEngine({scroll:false});
//			$dialog.dialog({
//				title: 'Add Earning',
//				width: 350,
//				height: 260,
//				modal: true,
//				resizable: true,
//				buttons: {
//					'Save': function() {
//						if ($('#earning_name').val() == '' || $('#earning_amount').val() == '') {
//							alert('Please fill out all fields');
//							return;
//						}
//						
//						$(dialog_id + ' form').ajaxSubmit({
//							success:function(o) {	
//								if (o.added) {							
//									if (events) {
//										events.onAdd(o);	
//									}
//								} else {
//									if (events) {
//										events.onError(o);	
//									}	
//								}
//							},
//							dataType: 'json'
//						});						
//						$dialog.dialog('destroy');						
//						$(dialog_id).remove();
//					},
//					Cancel: function() {
//						$dialog.dialog('destroy');
//						$(dialog_id).remove();
//					}
//				},
//				close: function() {
//					$dialog.dialog('destroy');
//					$(dialog_id).remove();
//				}				
//			});				
//		});		
//}

function _addEarning(employee_id, from, to, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Earning';
	var width = 350;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}
	
	$.get(base_url + 'payslip/add_earning?ajax=1', {employee_id:employee_id, from:from, to:to},
		function(data){
			closeDialog(dialog_id);
			$(dialog_id).html(data);
			dialogGeneric(dialog_id, {
				title: title,
				resizable: false,
				width: width,
				height: height,
				modal: true,
				form_id: '#add_earning_form'
			});
			
			$('#add_earning_form').validationEngine({scroll:false});		
			$('#add_earning_form').ajaxForm({
				success:function(o) {
					if (o.added) {							
						if (events) {
							events.onAdd(o);	
						}
					} else {
						if (events) {
							events.onError(o);	
						}	
					}				
				},
				dataType:'json',
				beforeSubmit: function() {
					return true;
				}
			});			
		});		
}

function closeAddEarningDialog() {
	$('.formError').remove();
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#add_earning_form');
}

function closeChangeDeductionAmountDialog() {
	$('.formError').remove();
	var dialog_id = '#' + MAIN_DIALOG_ID;
	var $dialog = $(dialog_id);
	$dialog.dialog('destroy');
	$('#'+ MAIN_DIALOG_ID).remove();
}

function removeEarning(earning_label, employee_id, from, to) {
	showYesNoDialog('Are you sure you want to remove this earning?', {
		onYes: function(){
			_removeEarning(earning_label, employee_id, from, to, {
				onRemove: function() {
					var query = window.location.search;
					$.post(base_url + 'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to, {}, function(o) {
						$('#payslip_manage').html(o);	
					});					
				}
			});	
		}
	});	
//	var ans = confirm('Are you sure you want to remove?');
//	if (ans) {
//		_removeEarning(earning_label, employee_id, from, to, {
//			onRemove: function() {
//				var query = window.location.search;
//				//showContent('#'+ CONTENT_BODY_ID, {title:'First Handler', url:'payslip/show_payslip' + query + '&ajax=1', param: {}});
//				$.post(base_url + 'payslip/show_payslip?ajax=1&employee_id='+ employee_id +'&from='+ from +'&to='+ to, {}, function(o) {
//					$('#show_payslip').html(o);	
//				});					
//			}
//		});	
//	}	
}

function _removeEarning(earning_label, employee_id, from, to, events) {
	$.post(base_url + 'payslip/_remove_earning', {label:earning_label, employee_id:employee_id, from:from, to:to},
		function(o) {
			if (o.removed) {
				if (events) {
					events.onRemove();
				}
			}
		}
	, 'json');	
}

function changePeriodPayoutDate(from, to, current_payout_date) {
	_changePeriodPayoutDate(from, to, current_payout_date, {
		onSaved: function(o) {
			//$('#payout-date').html(o.payout_date);	
			var query = window.location.search;
			showContent('#'+ CONTENT_BODY_ID, {url:'payslip/manage' + query + '&ajax=1'});
		}
	});		
}

function _changePeriodPayoutDate(from, to, current_payout_date, events) {
	var dialog_id = '#' + MAIN_DIALOG_ID;
	var $dialog = $(dialog_id);
	$dialog.dialog('destroy');
	$('#'+ MAIN_DIALOG_ID).remove();
	
	$.get(base_url + 'payslip/dialog_change_period_payout_date', {from:from, to:to, current_payout_date:current_payout_date},
		function(o){
			$('#'+ DIALOG_CONTENT_HANDLER).html('<div id="'+ MAIN_DIALOG_ID +'">' + o + '</div>');
			$('#payout_date').datepicker({dateFormat: 'yy-mm-dd'});
			
			var $dialog = $(dialog_id);
			$dialog.dialog({
				title: 'Payout Date',
				width: 350,
				height: 180,
				modal: true,
				resizable: true,
				buttons: {
					'Save': function() {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {	
								if (o.saved) {							
									if (events) {
										events.onSaved(o);	
									}
								}
							},
							dataType: 'json'
						});						
						$dialog.dialog('destroy');						
						$(dialog_id).remove();
					},
					Cancel: function() {
						$dialog.dialog('destroy');
						$(dialog_id).remove();
					}
				},
				close: function() {
					$dialog.dialog('destroy');
					$(dialog_id).remove();
				}				
			});				
		});		
}

function emailPayslip() {
	var dialog_id = '#' + MAIN_DIALOG_ID;
	var $dialog = $(dialog_id);
	$dialog.dialog('destroy');
	$('#'+ MAIN_DIALOG_ID).remove();	
	
	$('#'+ DIALOG_CONTENT_HANDLER).html('<div id="'+ MAIN_DIALOG_ID +'">Are you sure you want to email this payslip?</div>');	
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Confirmation',
		width: 350,
		height: 180,
		modal: true,
		resizable: true,
		buttons: {
			'Yes': function() {
				showLoadingDialog('Sending...');	
				$.post(base_url + 'payslip/email',{},
				function(o){
					if(o.error==0){	
						$dialog.dialog('destroy');
						$(dialog_id).remove();													
						showOkDialog('Email was successfully sent');					
					}else{
						$dialog.dialog('destroy');
						$(dialog_id).remove();													
						showOkDialog('Cannot send email...');	
					}
				},'json');
			},
			No: function() {
				$dialog.dialog('destroy');
				$(dialog_id).remove();
			}
		},
		close: function() {
			$dialog.dialog('destroy');
			$(dialog_id).remove();
		}				
	});							
}

function processedPayrollBasicFormScripts() {
	$('#frm-processed-payroll').ajaxForm({
	    success:function(o) {
	    	var query = window.location.search;
            dialogOkBox(o.message,{ok_url:'payslip/processed_payroll'+ query});
	    },
	    dataType:'json',
	    beforeSubmit: function() {
	      if ($('#action').val() == '') {         
	        return false; 
	      }
	      showLoadingDialog('Processing...');
	      return true;
	    }
	}); 

	$(".btn-hold-deduction").click(function(){
		var action = $(this).attr("id");
		modalDialogYesNo(action); 
	});

	$(".btn-move-deduction").click(function(){
		var action 	= $(this).attr("id");
		var from 	= $("#from").val();
		var to 		= $("#to").val();
		modalMoveDeduction(action,from,to); 
	});

	$(".btn-show-filter-form").click(function(){
		$(this).hide();
		$(".btn-refresh-form").hide();
		$(".filter-option-wrapper").toggle(500);
	});

	$(".btn-hide-filter-form").click(function(){
		$(".filter-option-wrapper").hide();
		$(".btn-show-filter-form").fadeIn(2000);
		$(".btn-refresh-form").fadeIn(2000);
	});

	$(".btn-refresh-form").click(function(){
		var from = $("#from").val();
		var to = $("#to").val();
		var q = $("#q").val();
		loadProcessedPayrollListDt(from,to,q);
	});

	$(".btn-filter").click(function(){
		var from = $("#from").val();
		var to = $("#to").val();
		var q = $("#q").val();
		loadFilteredProcessedPayrollListDt(from,to,q);
	});

}

function modalDialogYesNo(action) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	$("#action").val(action);

	var message = 'Are you sure you want to <b>'+action+'</b> the selected deduction(s)?';

	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
	title: 'Notice',
	resizable: false,
	width: width,
	height: height,
	modal: true,
	close: function() {
	  $dialog.dialog("destroy");
	  $dialog.hide();
	  disablePopUp();
	},    
	buttons: {
	  'Yes' : function(){
	    $dialog.dialog("destroy");
	    $dialog.hide();
	    disablePopUp();
	    $("#submitBtn").trigger('click');   
	  },
	  'No' : function(){
	    $dialog.dialog("destroy");
	    $dialog.hide();
	    disablePopUp();

	  }       
	}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function modalMoveDeduction(action,from,to) {

    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var title = 'Move Deduction';
    var width = 450;
    var height = 'auto';
        
    $.post(base_url + 'payslip/_show_move_deduction_form',{from:from,to:to,action:action},function(data) {
        closeDialog(dialog_id);
        $(dialog_id).html(data);
        dialogGeneric(dialog_id, {
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true
        });
            
    }); 
}

function loadProcessedPayrollListDt(from,to,q) {
  $("#processed-payroll-wrapper").html(loading_image);  
  $.post(base_url + 'payslip/_processed_payroll_list_dt',{from:from,to:to,q:q },function(o) {
    $('#processed-payroll-wrapper').html(o);    
  }); 
}

function loadFilteredProcessedPayrollListDt(from,to,q) {
  var filter_amount = $("#filter_amount").val();
  var filter_operator = $("#filter_operator").val();
  var filter_field = $("#filter_field").val();

  $("#processed-payroll-wrapper").html(loading_image);  
  $.post(base_url + 'payslip/_processed_payroll_list_dt',{from:from,to:to,q:q,filter_operator:filter_operator,filter_amount:filter_amount,filter_field:filter_field },function(o) {
    $('#processed-payroll-wrapper').html(o);    
  }); 
}