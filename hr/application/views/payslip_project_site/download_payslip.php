<?php
   $extension = '';  	  
  if( $yearly_bonus ){
  	$extension = 'yearly_bonus_template';
  }

  if( $bonus_service_award ){
    $extension = 'bonus_template';
  }

  if($default_template) {
  	if( $extension != '' ){  		
      include('payslip_template/' . $extension . $default_template['id'] . '.php');  		
  	}else{  		
      include('payslip_template/template' . $default_template['id'] . '.php');
  	}
  } else {  	
      include('payslip_template/' . 'template1.php');
  }
?>  