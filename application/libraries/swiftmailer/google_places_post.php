<style>
.google-places-form {
	width:733px;
	margin:0 auto;
	padding:10px;
	border:1px solid #c7c7c7;
	border-radius:5px;
	-o-border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	-kthml-border-radius:5px;
	font-size:12px;
}
.oral-surgery-form tr {
	padding:5px;
}
.light-gray {
	background:#f6f6f6;
}
.dark-gray {
	background:#e2e0e0;
}
.form-title-category {
	background:url(../images/form-title-bg.png) no-repeat top center;
	height:47px;
	width:975px;
	margin:8px 0 8px -18px;
}
.form-title-category h1 {
	padding-bottom:7px;
	padding-left:20px;
	line-height:40px;
	margin:0;
	color:#fff;
	font-size:17px;
	text-transform:uppercase;
}
textarea {
    border: 1px solid #B2B2B2;
    border-radius: 4px 4px 4px 4px;
	line-height: 25px;
    margin: 2px 4px;
    min-height: 25px;
    padding: 0 3px;
}
input[type="text"] {
    border: 1px solid #B2B2B2;
    border-radius: 4px 4px 4px 4px;
	line-height: 25px;
    margin: 2px 3px;
    min-height: 25px;
    padding: 0 3px;
}
</style>




<?php 

$msg = '
	
			<div class="google-places-form">
	
				<table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Business Name:</td>
						<td height="35" style="vertical-align:middle;">'.$_POST['business_name'].'</td>
					</tr>
					
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Contact Name:</td>
						<td  style="vertical-align:middle;">'.$_POST['contact_name'].'</td>
					</tr>
				 </table>
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="light-gray" >
				
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Address Line 1:</td>
						<td  height="35" style="vertical-align:middle;" >'.$_POST['address_line1'].'</td>
					</tr>	
					
					<tr>	
						<td width="150px;"  height="28" style="vertical-align:middle;">&nbsp;Address Line 2:</td>
						<td  style="vertical-align:middle;">'.$_POST['address_line2'].'</td>
					</tr>
					
					<tr>	
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;City:</td>						
						<td  style="vertical-align:middle;">'.$_POST['city'].'</td>
					</tr>
									
				  </table>
				  
				  <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >  
					
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;State:</td>
						<td height="35" style="vertical-align:middle;">'.$_POST['state'].'</td>
					</tr>
					
					<tr>	
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Country Code:</td>
						<td style="vertical-align:middle;">'.$_POST['country_code'].'</td>
					</tr>
					
					<tr>	
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Postal Code:</td>
						<td style="vertical-align:middle;">'.$_POST['postal_code'].'</td>
					</tr>
					
     			  </table> 
				   
				   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="light-gray" >
					<tr>
						
						<td width="150px;" height="28" style="vertical-align:middle;" >&nbsp;Main Phone:</td>
						<td height="35" style="vertical-align:middle;" >'.$_POST['main_phone'].'</td>
					</tr>
					<tr>	
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Alt Phone:</td>
						<td style="vertical-align:middle;">'.$_POST['alt_phone'].'</td>
					</tr>
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Mobile Phone:</td>
						<td style="vertical-align:middle;">'.$_POST['mobile_phone'].' </td>
					</tr>

					</table>
					
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >  
					
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Fax Phone:</td>
						<td height="35" style="vertical-align:middle;">'.$_POST['fax_phone'].'</td>
					</tr>
					
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Home Page:</td>
						<td style="vertical-align:middle;">'.$_POST['home_page'].' </td>
					</tr>
					
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Email:</td>
						<td style="vertical-align:middle;">'.$_POST['email'].' </td>
					</tr>
					
					</table>
					
					
					
					
					
					
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="light-gray" >
    
				<tr>
					<td width="50%" height="35" style="vertical-align:bottom;">&nbsp;Hours<br />
						<table width="300">
							<tr>
								<td width="145">Monday</td>
								<td width="143">
									'.$_POST['hours_monday'].'
								</td>
							</tr>
							
							<tr>
								<td>Tuesday</td>
								<td>
									'.$_POST['hours_tuesday'].' 
								</td>
							</tr>
							
							<tr>
								<td>Wednesday</td>
								<td>
									'.$_POST['hours_wednesday'].'
								</td>
							</tr>
							
							<tr>
								<td>Thursday</td>
								<td>
									'.$_POST['hours_thursday'].'
								</td>
							</tr>
							
							<tr>
								<td>Friday</td>
								<td>
									'.$_POST['hours_friday'].'
								</td>
							</tr>
							
							<tr>
								<td>Saturday</td>
								<td>
									'.$_POST['hours_saturday'].'
								</td>
							</tr>
							
							<tr>
								<td>Sunday</td>
								<td>
									'.$_POST['hours_sunday'].'
								</td>
							</tr>
						</table>
				  </td>
				  
				  <td width="50%"> &nbsp;Categories <br />
						<table width="334">
							<tr>
								<td>'.$_POST['category1'].'</td>
							</tr>
							
							<tr>
			
								<td>'.$_POST['category2'].'</td>
							</tr>
							
							<tr>
								<td>'.$_POST['category3'].'</td>
							</tr>
							
							<tr>
								<td>'.$_POST['category4'].'</td>
							</tr>
							
							<tr>
								<td>'.$_POST['category5'].'</td>
							</tr>
						</table>
				  
				  </td>
				  
				</tr>
				
				</table>
				
				 <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >
					<tr>
						<td width="150px;" height="28" style="vertical-align:middle;">&nbsp;Payment Types</td>
						<td style="vertical-align:middle;">'.$_POST['payment_type'].' </td>
					</tr>
				</table>
				
				  <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="light-gray" >
					<tr>
						<td height="28" style="font-weight:bold;">&nbsp;Video links from youtube</td>
						<td></td>
					</tr>
				   </table>
				  <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >  
					<tr>
						<td height="40" colspan="2">'.$_POST['video_link1'].'</td>
					</tr>
				  </table>
				  
				  <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="light-gray" >  
					 <tr>
						<td height="40" colspan="2">'.$_POST['video_link2'].'</td>
					</tr>
				   </table>
				   
				   <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >
					 <tr>
						<td height="40" colspan="2">'.$_POST['video_link3'].'</td>
					</tr>
					</table>
					
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" class="light-gray" >
					 <tr>
						<td height="40" colspan="2">'.$_POST['video_link4'].' </td>
					 </tr>
					</table>
					
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >
						<tr>
							<td height="40" colspan="2">'.$_POST['video_link5'].' </td>
						</tr>
					</table>
					
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" class="light-gray" >
					<tr>
						<td height="28" style="font-weight:bold;">&nbsp;Additional Details</td>
						<td></td>
					</tr>
					</table>
					
					<table  width="100%" border="0" cellspacing="0" cellpadding="0" class="dark-gray" >
						<tr>
							<td colspan="2">'.$_POST['additional_details1'].'</td>
						</tr>
					</table>
				
				
				
				</div>
			';
			
						require_once 'swiftmailer/lib/swift_required.php';
					   $email = 'marlito.work@gmail.com';
					   $email2 = $to1;
					   $dsubject =  '[theonlineforms]Google places';	
	 
						//Create the message
						$message11 = Swift_Message::newInstance();
						//Create the Transport
						$transport11 = Swift_SmtpTransport::newInstance('localhost', 25);
						//Create the Mailer using your created Transport
						$mailer11 = Swift_Mailer::newInstance($transport11);
						//Give the message a subject
						$message11->setSubject($dsubject);	
						////Give the message a attached files
						//$message1->attach($attach);	
						
						
						if($_FILES['fileatt1']['error'] == 0){
							$file_name1 = $_FILES['fileatt1']['name'];
							$type1 = $_FILES['fileatt1']['type'];
							$path1 = $_FILES['fileatt1']['tmp_name'];
							$attachment1 = Swift_Attachment::fromPath($path1, $type1)->setFilename($file_name1);  
							$message11->attach($attachment1);
						}
						
						if($_FILES['fileatt2']['error'] == 0){
							$file_name2 = $_FILES['fileatt2']['name'];
							$type2 = $_FILES['fileatt2']['type'];
							$path2 = $_FILES['fileatt2']['tmp_name'];
							$attachment2 = Swift_Attachment::fromPath($path2, $type2)->setFilename($file_name2);  
							$message11->attach($attachment2);
						}
						
						if($_FILES['fileatt3']['error'] == 0){
							$file_name3 = $_FILES['fileatt3']['name'];
							$type3 = $_FILES['fileatt3']['type'];
							$path3 = $_FILES['fileatt3']['tmp_name'];
							$attachment3 = Swift_Attachment::fromPath($path3, $type3)->setFilename($file_name3);  
							$message11->attach($attachment3);
						}
						
						if($_FILES['fileatt4']['error'] == 0){
							$file_name4 = $_FILES['fileatt4']['name'];
							$type4 = $_FILES['fileatt4']['type'];
							$path4 = $_FILES['fileatt4']['tmp_name'];
							$attachment4 = Swift_Attachment::fromPath($path4, $type4)->setFilename($file_name4);  
							$message11->attach($attachment4);
						}
						
						if($_FILES['fileatt5']['error'] == 0){
							$file_name5 = $_FILES['fileatt5']['name'];
							$type5 = $_FILES['fileatt5']['type'];
							$path5 = $_FILES['fileatt5']['tmp_name'];
							$attachment5 = Swift_Attachment::fromPath($path5, $type5)->setFilename($file_name5);  
							$message11->attach($attachment5);
						}
						
						if($_FILES['fileatt6']['error'] == 0){
							$file_name6 = $_FILES['fileatt6']['name'];
							$type6 = $_FILES['fileatt6']['type'];
							$path6 = $_FILES['fileatt6']['tmp_name'];
							$attachment6 = Swift_Attachment::fromPath($path6, $type6)->setFilename($file_name6);  
							$message11->attach($attachment6);
						}
						
						if($_FILES['fileatt7']['error'] == 0){
							$file_name7 = $_FILES['fileatt7']['name'];
							$type7 = $_FILES['fileatt7']['type'];
							$path7 = $_FILES['fileatt7']['tmp_name'];
							$attachment7 = Swift_Attachment::fromPath($path7, $type7)->setFilename($file_name7);  
							$message11->attach($attachment7);
						}
						
						if($_FILES['fileatt8']['error'] == 0){
							$file_name8 = $_FILES['fileatt8']['name'];
							$type8 = $_FILES['fileatt8']['type'];
							$path8 = $_FILES['fileatt8']['tmp_name'];
							$attachment8 = Swift_Attachment::fromPath($path8, $type8)->setFilename($file_name8);  
							$message11->attach($attachment8);
						}
						
						if($_FILES['fileatt9']['error'] == 0){
							$file_name9 = $_FILES['fileatt9']['name'];
							$type9 = $_FILES['fileatt9']['type'];
							$path9 = $_FILES['fileatt9']['tmp_name'];
							$attachment9 = Swift_Attachment::fromPath($path9, $type9)->setFilename($file_name9);  
							$message11->attach($attachment9);
						}
						
						if($_FILES['fileatt10']['error'] == 0){
							$file_name10 = $_FILES['fileatt10']['name'];
							$type10 = $_FILES['fileatt10']['type'];
							$path10 = $_FILES['fileatt10']['tmp_name'];
							$attachment10 = Swift_Attachment::fromPath($path10, $type10)->setFilename($file_name10);  
							$message11->attach($attachment10);
						}
						
						
						
						//Set the From address with an associative array
						$message11->setFrom(array('info@onlineforms.com' => 'info@onlineforms.com'));
						//Set the To addresses with an associative array
						$message11->setTo(array($email => $email));
						//$message11->setTo(array($email2 => $email2));
						//$message11->setTo(array($email => $email)); 
						//Give it a body
						$message11->setBody($msg , 'text/html');
						//And optionally an alternative body
						$message11->addPart($msg , 'text/html');
						$numSent1 = $mailer11->send($message11);
						//echo $numSent1;
							header("location: ".$_POST['return_url1']);	

?>




